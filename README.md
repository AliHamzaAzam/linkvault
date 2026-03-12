# LinkVault

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-blue.svg)](https://postgresql.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A self-hosted bookmark manager with full-text search, automatic metadata scraping, collections, tagging, and shareable collection links. Built with Laravel, Blade, Tailwind CSS, and Alpine.js.

## Features

- **Smart Bookmarking** -- Save URLs with automatic metadata extraction (title, description, favicon, OG images)
- **Full-Text Search** -- PostgreSQL `tsvector` ranked search across titles, descriptions, and URLs, with `LIKE` fallback for SQLite
- **Collections** -- Organize bookmarks into color-coded folders with ordering
- **Tagging** -- Flexible tagging with auto-creation from comma-separated input
- **Browser Import** -- Import bookmarks from Chrome, Firefox, Safari, or Edge HTML exports (folders become collections)
- **Shareable Collections** -- Generate unique share links (`/share/abc123`) for any collection; revoke anytime
- **REST API** -- Full CRUD API with Sanctum token authentication

## Quick Start

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 20+
- PostgreSQL 16 and Redis (via Docker or local install)

### Setup

```bash
git clone https://github.com/AliHamzaAzam/linkvault.git
cd linkvault

# Start PostgreSQL and Redis
docker compose up -d

# Install dependencies, generate key, run migrations, build assets
composer setup

# Seed demo data (optional)
php artisan db:seed

# Start all services (server, queue, logs, vite)
composer dev
```

Visit `http://localhost:8000` and register an account, or log in with `ali@linkvault.dev` / `password` if you seeded.

### Without Docker

If running PostgreSQL and Redis locally, update `.env` with your connection details. The app also works with SQLite (`DB_CONNECTION=sqlite`) -- full-text search falls back to `LIKE`-based matching.

## Architecture

See [ARCHITECTURE.md](ARCHITECTURE.md) for detailed diagrams and technical documentation.

The app uses a service-layer pattern: controllers are thin, business logic lives in `BookmarkService` and `BookmarkImportService`. Authorization is handled by `BookmarkPolicy` and `CollectionPolicy`. Metadata scraping is event-driven -- `BookmarkCreated` fires `DispatchMetadataScrape`, which dispatches the `ScrapeBookmarkMetadata` job (runs synchronously by default, or in the background with a queue worker).

Sharing bypasses the auth/policy system entirely -- `ShareController` looks up collections by their 32-character random token, no login required.

## API

All endpoints require a Sanctum bearer token unless noted.

```bash
# Create a token
curl -X POST http://localhost:8000/api/v1/tokens \
  -H "Content-Type: application/json" \
  -d '{"email": "ali@linkvault.dev", "password": "password", "device_name": "cli"}'
```

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/tokens` | Create access token (no auth) |
| `DELETE` | `/api/v1/tokens` | Revoke current token |
| `GET` | `/api/v1/user` | Get authenticated user |
| `GET` | `/api/v1/bookmarks` | List bookmarks (paginated) |
| `POST` | `/api/v1/bookmarks` | Create bookmark |
| `GET` | `/api/v1/bookmarks/{id}` | Get bookmark |
| `PUT` | `/api/v1/bookmarks/{id}` | Update bookmark |
| `DELETE` | `/api/v1/bookmarks/{id}` | Delete bookmark |
| `POST` | `/api/v1/bookmarks/{id}/archive` | Toggle archive |
| `GET` | `/api/v1/search?q={query}` | Search bookmarks |
| `GET` | `/api/v1/collections` | List collections |
| `POST` | `/api/v1/collections` | Create collection |
| `GET` | `/api/v1/collections/{id}` | Get collection |
| `PUT` | `/api/v1/collections/{id}` | Update collection |
| `DELETE` | `/api/v1/collections/{id}` | Delete collection |
| `GET` | `/api/v1/tags` | List tags |
| `DELETE` | `/api/v1/tags/{id}` | Delete tag |
| `POST` | `/api/v1/import` | Import bookmarks (HTML file) |
| `GET` | `/share/{token}` | View shared collection (no auth) |

## Testing

```bash
php artisan test
```

Tests run against SQLite in-memory and cover bookmark CRUD, collection management, sharing (generate/revoke/public access), browser import parsing, search, and profile management.

## Roadmap

- [ ] Browser extension for one-click saving
- [ ] AI-powered auto-tagging
- [ ] RSS feeds per collection
- [ ] Export to Markdown, JSON, HTML
- [ ] Collaboration on shared collections

## License

[MIT](LICENSE)