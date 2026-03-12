# LinkVault Architecture

## Overview

LinkVault is a Laravel 12 bookmark manager using a service-layer architecture. Controllers are thin, business logic lives in services, authorization is policy-based, and metadata scraping is event-driven via dispatched jobs.

## System Architecture

```mermaid
flowchart TB
    subgraph Client["Client Layer"]
        Browser["Browser (Blade + Alpine.js)"]
        APIClient["API Client (Sanctum)"]
    end

    subgraph App["Application Layer"]
        WebRoutes["Web Routes"]
        APIRoutes["API Routes /api/v1/"]
        ShareRoutes["Public Share Routes"]
        
        subgraph Controllers
            BC["BookmarkController"]
            CC["CollectionController"]
            TC["TagController"]
            IC["ImportController"]
            SC["ShareController"]
            AC["Api/V1/* Controllers"]
        end
        
        subgraph Services
            BS["BookmarkService"]
            BIS["BookmarkImportService"]
            MSS["MetadataScraperService"]
        end
        
        subgraph Auth["Authorization"]
            BP["BookmarkPolicy"]
            CP["CollectionPolicy"]
        end
    end

    subgraph Domain["Domain Layer"]
        subgraph Models
            User --> Bookmark
            User --> Collection
            User --> Tag
            Bookmark <-->|pivot| Collection
            Bookmark <-->|pivot| Tag
        end
        
        BCE["BookmarkCreated Event"]
        DMS["DispatchMetadataScrape Listener"]
        SBM["ScrapeBookmarkMetadata Job"]
    end

    subgraph Infra["Infrastructure"]
        DB[("PostgreSQL")]
        Redis[("Redis (cache)")]
    end

    Browser --> WebRoutes --> BC & CC & TC & IC
    Browser --> ShareRoutes --> SC
    APIClient --> APIRoutes --> AC
    
    BC & CC --> BP & CP
    BC --> BS
    IC --> BIS
    AC --> BS
    
    BS --> BCE --> DMS --> SBM --> MSS
    
    Models --> DB
    App --> Redis
```

## Database Schema

```mermaid
erDiagram
    users ||--o{ bookmarks : "has many"
    users ||--o{ collections : "has many"
    users ||--o{ tags : "has many"
    bookmarks }o--o{ collections : "bookmark_collection"
    bookmarks }o--o{ tags : "bookmark_tag"
    
    users {
        bigint id PK
        string name
        string email UK
        string password
        timestamp email_verified_at
        timestamps created_at
    }
    
    bookmarks {
        bigint id PK
        bigint user_id FK
        text url "unique per user"
        string title "nullable, scraped"
        text description "nullable, scraped"
        text favicon_url "nullable, scraped"
        text og_image_url "nullable, scraped"
        string site_name "nullable, scraped"
        boolean is_archived "default false"
        timestamp meta_scraped_at "null until scraped"
        tsvector searchable "generated stored"
        timestamps created_at
    }
    
    collections {
        bigint id PK
        bigint user_id FK
        string name
        string slug "unique per user"
        text description "nullable"
        string color "hex, default #3B82F6"
        string share_token "32-char, nullable, unique"
        int position "for ordering"
        timestamps created_at
    }
    
    tags {
        bigint id PK
        bigint user_id FK
        string name
        string slug "unique per user"
        timestamps created_at
    }
```

**Indexes:** unique on `(user_id, url)` for bookmarks, unique on `(user_id, slug)` for collections and tags, `GIN` index on `searchable` for full-text search, index on `(user_id, is_archived)`.

## Sharing Flow

```mermaid
sequenceDiagram
    actor Owner
    participant CC as CollectionController
    participant Col as Collection
    participant DB as Database
    actor Visitor
    participant SC as ShareController

    Owner->>CC: POST /collections/{id}/share
    CC->>Col: generateShareToken()
    Col->>DB: UPDATE share_token = Str::random(32)
    CC-->>Owner: Redirect with share URL

    Owner->>Owner: Copy /share/{token} to clipboard

    Visitor->>SC: GET /share/{token}
    Note over SC: No auth required
    SC->>DB: WHERE share_token = ?
    SC-->>Visitor: Rendered public view

    Owner->>CC: DELETE /collections/{id}/share
    CC->>Col: revokeShareToken()
    Col->>DB: UPDATE share_token = null
    Note over Visitor: Old links return 404
```

## Metadata Scraping Pipeline

```mermaid
sequenceDiagram
    participant BS as BookmarkService
    participant Event as BookmarkCreated
    participant Listener as DispatchMetadataScrape
    participant Job as ScrapeBookmarkMetadata
    participant MSS as MetadataScraperService
    participant URL as External URL

    BS->>Event: event(new BookmarkCreated($bookmark))
    Event->>Listener: handle()
    Listener->>Job: dispatch()
    Note over Job: Runs sync by default (QUEUE_CONNECTION=sync)
    Job->>MSS: scrape($url)
    MSS->>URL: HTTP GET (10s timeout, browser User-Agent)
    URL-->>MSS: HTML response
    MSS->>MSS: Regex extract: og:title, og:description, og:image, og:site_name, favicon
    MSS-->>Job: metadata array
    Job->>Job: Update bookmark (user data takes priority via ??)
    Note over Job: 3 retries with backoff [10s, 60s, 300s]
```

With `QUEUE_CONNECTION=sync` (current default), scraping runs inline during the bookmark creation request. To run scraping in the background, set `QUEUE_CONNECTION=database` and start a worker with `php artisan queue:work`.

`MetadataScraperService` uses regex extraction with fallback chains: `og:title` → `<title>`, `og:description` → `<meta description>`, `<link rel="icon">` → `<link rel="apple-touch-icon">` → `/favicon.ico`. All relative URLs are resolved against the base URL. On failure, returns hostname as `site_name` and `/favicon.ico` fallback.

## Directory Structure

```
app/
├── Events/
│   └── BookmarkCreated.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/
│   │   │   ├── BookmarkController.php   # JSON responses
│   │   │   ├── CollectionController.php
│   │   │   ├── ImportController.php
│   │   │   ├── TagController.php
│   │   │   └── TokenController.php      # Sanctum token create/revoke
│   │   ├── Auth/                        # Breeze auth controllers
│   │   ├── BookmarkController.php       # Blade views
│   │   ├── CollectionController.php     # includes share/unshare methods
│   │   ├── ImportController.php
│   │   ├── ProfileController.php        # Breeze profile management
│   │   ├── ShareController.php          # Public token-based access
│   │   └── TagController.php
│   ├── Requests/
│   │   ├── ImportBookmarksRequest.php   # file: html/htm, max 5MB
│   │   ├── StoreBookmarkRequest.php     # url unique per user
│   │   ├── StoreCollectionRequest.php
│   │   └── UpdateBookmarkRequest.php    # authorize via BookmarkPolicy
│   └── Resources/
│       ├── BookmarkResource.php         # whenLoaded for tags/collections
│       ├── CollectionResource.php       # includes share_token, is_shared
│       └── TagResource.php
├── Jobs/
│   └── ScrapeBookmarkMetadata.php       # 3 tries, backoff [10, 60, 300]
├── Listeners/
│   └── DispatchMetadataScrape.php
├── Models/
│   ├── Bookmark.php                     # belongsToMany tags, collections
│   ├── Collection.php                   # generateShareToken(), revokeShareToken(), isShared()
│   ├── Tag.php                          # auto-slug in booted()
│   └── User.php                         # hasMany bookmarks, collections, tags
├── Policies/
│   ├── BookmarkPolicy.php               # Owner-only for all operations
│   └── CollectionPolicy.php             # Owner-only (share bypasses policies)
├── Providers/
│   ├── AppServiceProvider.php
│   └── EventServiceProvider.php         # BookmarkCreated → DispatchMetadataScrape
└── Services/
    ├── BookmarkImportService.php        # Netscape HTML parser, 1000 limit
    ├── BookmarkService.php              # list, create, update, search, toggleArchive
    └── MetadataScraperService.php       # Regex OG/meta extraction with fallbacks
```

## Key Design Decisions

**Share tokens over public booleans.** Collections use a 32-char random `share_token` instead of an `is_public` flag. This provides unguessable URLs without exposing user identities. Revoking sets the token to null; old links immediately 404. The share page bypasses policies entirely since it uses token lookup, not model binding with auth.

**Dual search strategy.** The `searchable` column is a PostgreSQL `tsvector` generated stored column with a GIN index, enabling ranked full-text search via `plainto_tsquery` and `ts_rank`. `BookmarkService::search()` checks the DB driver at runtime and falls back to `LIKE`-based search on SQLite (used in tests).

**Service layer.** Controllers delegate to `BookmarkService` and `BookmarkImportService`. This keeps controllers thin and lets the web and API controllers share the same business logic.

**Event-driven scraping.** `BookmarkCreated` → `DispatchMetadataScrape` → `ScrapeBookmarkMetadata` job. User-provided title and description take priority over scraped values (via `??` operator). Scraping failures are graceful — bookmarks work fine with partial or no metadata. With `QUEUE_CONNECTION=sync`, scraping runs inline. Switch to `database` for background processing.

**Tag auto-creation.** `BookmarkService::syncTags()` uses `Tag::firstOrCreate()` by user ID and slug. Users type comma-separated names in the form, new tags are created on the fly, existing ones are matched by slug.

## API Routes

All under `/api/v1/`, authenticated via `auth:sanctum` middleware except token creation.

| Resource | Method | Endpoint |
|----------|--------|----------|
| Token | `POST` | `/api/v1/tokens` |
| Token | `DELETE` | `/api/v1/tokens` |
| User | `GET` | `/api/v1/user` |
| Bookmarks | `GET/POST` | `/api/v1/bookmarks` |
| Bookmarks | `GET/PUT/DELETE` | `/api/v1/bookmarks/{id}` |
| Bookmarks | `POST` | `/api/v1/bookmarks/{id}/archive` |
| Search | `GET` | `/api/v1/search?q=` |
| Collections | `GET/POST` | `/api/v1/collections` |
| Collections | `GET/PUT/DELETE` | `/api/v1/collections/{id}` |
| Tags | `GET` | `/api/v1/tags` |
| Tags | `DELETE` | `/api/v1/tags/{id}` |
| Import | `POST` | `/api/v1/import` |
| Share | `GET` | `/share/{token}` (public, no auth) |

## Security Model

- **Web routes:** Session cookie + CSRF token, `auth` + `verified` middleware
- **API routes:** Sanctum bearer token, `auth:sanctum` middleware
- **Share routes:** No auth — unguessable 32-char token lookup only
- **Policies:** Owner-only for all bookmark and collection operations
- **Import limits:** HTML/HTM files only, 5MB max, 1000 bookmarks per import