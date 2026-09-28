# CoWork API - Laravel 8 Admin + API

## Project Overview
Hệ thống Admin Panel + REST API cho quản lý Mini Apps.
- **Framework**: Laravel 8.83.29 (bắt buộc, không thay đổi)
- **PHP**: 8.1.2 (VPS) / 8.3 (local) — đã set `platform.php: 8.1.2` trong composer.json
- **DB**: MySQL 8 trên VPS `14.225.211.84`
- **Admin Panel**: Filament v2
- **Auth**: Laravel Sanctum (token-based)

## URLs
- **Production**: https://cowork-api.vusol.io.vn
- **Admin Panel**: https://cowork-api.vusol.io.vn/admin
- **GitHub**: https://github.com/buzzdeptrai/demo-main-app-cow

## VPS
- **IP**: 14.225.211.84 (hyte-ubuntu-pfxu)
- **SSH**: `ssh root@14.225.211.84`
- **Web root**: `/var/www/cowork-api`
- **Nginx config**: `/etc/nginx/sites-available/cowork-api`
- **PHP-FPM**: 8.1 (`/run/php/php8.1-fpm.sock`)

## Database
- **Host**: 127.0.0.1 (local trên VPS)
- **DB**: cowork_api
- **User**: cowork
- **Password**: CoWork@202u926!

## Admin Credentials
- **Email**: vuquocvietkg@gmail.com
- **Password**: vietVu@main01227
- **Roles**: admin (full), manager (limited), user (view only)

## Tech Stack
| Package | Version | Mục đích |
|---------|---------|----------|
| filament/filament | ^2.0 | Admin panel CRUD |
| laravel/sanctum | ^2.11 | API token auth |
| spatie/laravel-permission | ^5.0 | Role/permission |
| spatie/laravel-medialibrary | ^9.0 | File upload |
| spatie/laravel-activitylog | ^4.0 | Admin action log |
| laravel/telescope | ^4.0 | Debug (dev only) |

## Architecture
```
app/
├── Filament/Resources/     # Admin CRUD (UserResource, MiniAppResource, RoleResource, ActivityResource)
├── Http/Controllers/Api/V1/ # API controllers
├── Http/Middleware/         # IdentifyMiniApp, CheckMiniAppStatus, ApiRateLimit, LogApiRequest
├── Http/Resources/          # API JSON resources
├── Http/Requests/           # Form validation
├── Models/                  # User, MiniApp, MiniAppSetting, ApiRequestLog
├── Services/                # AuthService, UserService, MiniAppService, MediaService
├── Repositories/            # Repository pattern (interfaces + implementations)
└── Traits/                  # ApiResponse trait
```

## API Routes
```
POST   /api/v1/auth/login          # Public
POST   /api/v1/auth/register       # Public
GET    /api/v1/auth/me             # Auth required
POST   /api/v1/auth/logout         # Auth required

# Admin routes (role: admin|manager)
CRUD   /api/v1/users
CRUD   /api/v1/mini-apps
POST   /api/v1/mini-apps/{id}/tokens
DELETE /api/v1/mini-apps/{id}/tokens/{tid}
POST   /api/v1/mini-apps/{id}/media

# Per-app routes (token + middleware chain)
GET    /api/v1/app/{slug}/
GET    /api/v1/app/{slug}/settings
GET    /api/v1/app/{slug}/media
```

## Mini Apps Concept
Mỗi mini app (bot, web app, mobile app) kết nối vào hệ thống qua API token riêng.
Admin quản lý: tạo app → cấp token → app gọi API → hệ thống rate limit + log.

## Key Decisions
- **Filament v2** (không phải v3) — vì Laravel 8
- **FilamentUser interface** — User model implement `canAccessFilament()` để cho phép login production
- **Namespace tách biệt**: `App\Filament\Resources\UserResource` (admin) vs `App\Http\Resources\UserResource` (API)
- **Telescope dev only** — load qua AppServiceProvider khi `local` env + class exists
- **Platform PHP 8.1.2** — composer.json lock packages tương thích VPS

## Deploy Flow
```bash
# Local: commit + push
git add -A && git commit -m "message" && git push origin main

# VPS:
cd /var/www/cowork-api
git pull origin main
php artisan migrate --force    # nếu có migration mới
php artisan config:clear
```

## Common Commands
```bash
# Local dev
php artisan serve --port=8000

# Seed fresh
php artisan migrate:fresh --seed

# Create Filament resource
php artisan make:filament-resource ModelName --generate

# Test API
curl -s -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"vuquocvietkg@gmail.com","password":"vietVu@main01227"}'
```

## Mini App: NNVN Apis Go

Game tìm ong Apis ẩn cho Novo Nordisk. Player đăng ký bằng email, chơi game tìm ong, trả lời quiz.

**Base URL**: `/api/v1/app/nnvn-apis-go`

**Structure**: `app/MiniApps/NnvnApisGo/` (Controllers, Services, Models, Requests, Resources, Exceptions, Constants, routes.php)

**DB tables** (prefix `nnvn_`): players, games, rounds, quiz_questions

**API Endpoints**:
```
POST   /players/register         # {name, email, phone}
GET    /players/me?email=        # Player profile
POST   /games/start              # {player_id}
POST   /games/{id}/rounds        # {round_number, time_seconds, quiz_used}
POST   /games/{id}/complete      # {gift_apis_found: bool}
GET    /leaderboard              # Top players
GET    /leaderboard/me?email=    # My rank
GET    /quiz/random?lang=vi|en   # Random question
POST   /quiz/answer              # {question_id, answer_index, game_id}
```

**Game Flow**: Register → Start → Round 1 → (Quiz) → Round 2 → Complete → Leaderboard

**Constants**:
- MAX_APIS = 50 (tổng số ong tối đa mỗi player)
- TOTAL_ROUNDS = 2 (số round mỗi game)
- APIS_PER_SESSION = 3 (+1 nếu gift_apis_found)
- TIMER_SECONDS = 30
- Round time: 2-30 giây

**No auth required** — email-based identification, CORS-friendly cho game frontend.

## Plan
Chi tiết implementation plan: `thoughts/shared/plans/2026-09-28-laravel8-admin-api.md`
