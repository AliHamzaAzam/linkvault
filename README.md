# LinkVault

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15+-blue.svg)](https://postgresql.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A modern, self-hosted bookmarking application with full-text search, metadata scraping, collections, and API access.

## Features

- **Smart Bookmarking** — Save URLs with automatic metadata extraction (title, description, favicon, OG images)
- **Full-Text Search** — PostgreSQL-powered search across titles, descriptions, and URLs
- **Collections** — Organize bookmarks into color-coded folders
- **Tagging** — Flexible tagging system with auto-creation
- **Import/Export** — Import from Chrome/Firefox/Safari HTML exports
- **Public Profiles** — Share curated bookmarks via vanity URLs (`@username`)
- **REST API** — Full-featured API with Sanctum authentication
- **Queue-Based** — Metadata scraping runs in background queues

## Quick Start

### Prerequisites

- PHP 8.3+
- PostgreSQL 15+
- Redis (for queues/cache)
- Node.js 20+ (for asset building)

### Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/linkvault.git
cd linkvault

# Start with Docker (recommended)
docker compose up -d

# Or manually:
# 1. Copy environment file
cp .env.example .env

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Generate app key
php artisan key:generate

# 4. Run migrations
php artisan migrate

# 5. Start queue worker
php artisan queue:work

# 6. Serve the application
php artisan serve
```

Visit `http://localhost:8000` and register an account.

## API Reference

All API endpoints require authentication via Sanctum token.

### Authentication

| Method | URI | Description |
|--------|-----|-------------|
| POST | `/api/v1/tokens` | Create access token |
| DELETE | `/api/v1/tokens` | Revoke current token |

### Bookmarks

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/v1/bookmarks` | List bookmarks (paginated) |
| POST | `/api/v1/bookmarks` | Create new bookmark |
| GET | `/api/v1/bookmarks/{id}` | Get bookmark details |
| PUT | `/api/v1/bookmarks/{id}` | Update bookmark |
| DELETE | `/api/v1/bookmarks/{id}` | Delete bookmark |
| POST | `/api/v1/bookmarks/{id}/archive` | Toggle archive status |
| GET | `/api/v1/search?q={query}` | Search bookmarks |

### Collections

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/v1/collections` | List collections |
| POST | `/api/v1/collections` | Create collection |
| GET | `/api/v1/collections/{id}` | Get collection |
| PUT | `/api/v1/collections/{id}` | Update collection |
| DELETE | `/api/v1/collections/{id}` | Delete collection |

### Tags

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/v1/tags` | List tags |
| DELETE | `/api/v1/tags/{id}` | Delete tag |

### Import

| Method | URI | Description |
|--------|-----|-------------|
| POST | `/api/v1/import` | Import bookmarks from HTML file |

### Example Usage

```bash
# Create API token
curl -X POST http://localhost:8000/api/v1/tokens \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password",
    "device_name": "CLI"
  }'

# Create bookmark
curl -X POST http://localhost:8000/api/v1/bookmarks \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://laravel.com",
    "title": "Laravel Framework",
    "tags": "php,framework"
  }'

# Search bookmarks
curl "http://localhost:8000/api/v1/search?q=laravel" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Architecture

See [ARCHITECTURE.md](ARCHITECTURE.md) for detailed technical documentation.

**Key Technologies:**
- Laravel 11 with Service Layer pattern
- PostgreSQL with tsvector full-text search
- Laravel Sanctum for API authentication
- Tailwind CSS + Alpine.js frontend
- Queue workers for async metadata scraping

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter BookmarkTest

# Run with coverage
php artisan test --coverage
```

## Roadmap

- [ ] **Browser Extension** — One-click bookmark saving
- [ ] **AI Auto-Tagging** — Automatic tag suggestions via LLM
- [ ] **RSS Feeds** — Subscribe to collection updates
- [ ] **Collaboration** — Shared collections with permissions
- [ ] **Export** — Export to various formats (Markdown, JSON, etc.)

## License

LinkVault is open-sourced software licensed under the [MIT license](LICENSE).
