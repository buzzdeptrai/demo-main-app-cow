# CoWork API - Laravel 8 Admin + API

---

## PART 1: GLOBAL PROJECT

---

### Project Overview
Hệ thống Admin Panel + REST API cho quản lý Multi Mini Apps.
- **Framework**: Laravel 8.83.29 (bắt buộc, không thay đổi)
- **PHP**: 8.1.2 (VPS) / 8.3 (local) — đã set `platform.php: 8.1.2` trong composer.json
- **DB**: MySQL 8 trên VPS `14.225.211.84`
- **Admin Panel**: Filament v2 (brand: CoWork Admin)
- **Auth**: Laravel Sanctum (token-based)

### URLs
- **Production**: https://cowork-api.vusol.io.vn
- **Admin Panel**: https://cowork-api.vusol.io.vn/admin
- **GitHub**: https://github.com/buzzdeptrai/demo-main-app-cow

### VPS
- **IP**: 14.225.211.84 (hyte-ubuntu-pfxu)
- **SSH**: `ssh root@14.225.211.84`
- **Web root**: `/var/www/cowork-api`
- **Nginx config**: `/etc/nginx/sites-available/cowork-api`
- **PHP-FPM**: 8.1 (`/run/php/php8.1-fpm.sock`)

### Database
- **Host**: 127.0.0.1 (local trên VPS)
- **DB**: cowork_api
- **User**: cowork
- **Password**: CoWork@202u926!

### Admin Credentials
| Role | Email | Password | Quyền |
|------|-------|----------|-------|
| admin | vuquocvietkg@gmail.com | vietVu@main01227 | Full access |
| client | client@nnvn.com | Client@nnvn2026 | Chỉ NNVN Apis Go |

### Roles & Access Control
- **admin** — full access tất cả resources + widgets
- **manager** — full access trừ Roles management
- **client** — chỉ thấy nhóm "NNVN Apis Go" trong sidebar (dùng `shouldRegisterNavigation()` Filament v2)
- Non-NNVN resources dùng `shouldRegisterNavigation()` để ẩn khỏi client
- Non-NNVN widgets dùng `canView()` để ẩn khỏi client

### Tech Stack
| Package | Version | Mục đích |
|---------|---------|----------|
| filament/filament | ^2.0 | Admin panel CRUD |
| laravel/sanctum | ^2.11 | API token auth |
| spatie/laravel-permission | ^5.0 | Role/permission |
| spatie/laravel-medialibrary | ^9.0 | File upload |
| spatie/laravel-activitylog | ^4.0 | Admin action log |
| laravel/telescope | ^4.0 | Debug (dev only) |

### Architecture
```
app/
├── Filament/
│   ├── Resources/          # UserResource, MiniAppResource, RoleResource, ActivityResource
│   ├── Resources/Nnvn/     # NnvnPlayerResource, NnvnGameResource, NnvnQuizQuestionResource, NnvnBoxConfigResource
│   ├── Pages/              # NnvnReports (custom report page)
│   └── Widgets/            # StatsOverview, NnvnStatsWidget, LatestActivities, MiniAppStatus
├── MiniApps/
│   ├── MiniAppServiceProvider.php  # Auto-discover mini app routes
│   └── NnvnApisGo/                # Domain-specific mini app (see Part 2)
├── Http/Controllers/Api/V1/       # Core API controllers
├── Models/                        # User, MiniApp, MiniAppSetting, ApiRequestLog
├── Services/                      # AuthService, UserService, MiniAppService
├── Repositories/                  # Repository pattern
└── Traits/                        # ApiResponse trait
```

### Mini Apps Concept
Mỗi mini app nằm trong `app/MiniApps/{AppName}/` với cấu trúc riêng.
`MiniAppServiceProvider` auto-discover routes.php từ mỗi folder → register prefix `/api/v1/app/{slug}`.
Slug = kebab-case của folder name (VD: `NnvnApisGo` → `nnvn-apis-go`).

### Key Decisions
- **Filament v2** (không phải v3) — vì Laravel 8
- **FilamentUser interface** — `canAccessFilament()` cho phép admin/manager/client login
- **shouldRegisterNavigation()** — ẩn resources khỏi client (KHÔNG dùng `canAccess()` — Filament v2 không support)
- **Telescope dev only** — load qua AppServiceProvider khi `local` env + class exists
- **Platform PHP 8.1.2** — composer.json lock packages tương thích VPS
- **Footer branding** — VuSol contact via `Filament::registerRenderHook('footer.end')`
- **No FilamentInfoWidget** — removed, no Filament logo in footer

### Deploy Flow
```bash
# Local: commit + push (dùng /commit skill)

# VPS:
cd /var/www/cowork-api
git pull origin main
php artisan migrate --force
php artisan db:seed --class=SomeSeeder   # nếu có seeder mới
php artisan config:cache
php artisan route:clear                  # QUAN TRỌNG khi thêm page/resource mới
php artisan view:clear
```

### Common Commands
```bash
# Local dev
php artisan serve --port=8000

# Seed fresh
php artisan migrate:fresh --seed

# Create Filament resource
php artisan make:filament-resource ModelName --generate
```

---

## PART 2: MINI APP — NNVN Apis Go

---

### Overview
Game tìm ong Apis ẩn cho Novo Nordisk. Player đăng ký bằng email, chơi game tìm ong qua 16 boxes (1 apis + 15 links), trả lời quiz bilingual.

### Base URL
`/api/v1/app/nnvn-apis-go`

### Structure
```
app/MiniApps/NnvnApisGo/
├── Constants.php
├── routes.php
├── Controllers/        # PlayerController, GameController, LeaderboardController, QuizController, BoxController
├── Services/           # GameService, LeaderboardService
├── Models/             # Player, Game, Round, QuizQuestion, QuizAnswer, BoxConfig, BoxClick
├── Requests/           # RegisterPlayerRequest, StartGameRequest, SubmitRoundRequest, CompleteGameRequest, AnswerQuizRequest, BoxClickRequest
├── Resources/          # PlayerResource, GameResource, LeaderboardResource
└── Exceptions/         # GameException (MAX_APIS_REACHED, GAME_NOT_ACTIVE, INVALID_ROUND_TIME, ROUND_EXISTS)
```

### DB Tables (prefix `nnvn_`)
| Table | Mô tả |
|-------|--------|
| nnvn_players | name, email, total_apis_found, total_sessions, best_total_time |
| nnvn_games | player_id, status (playing/completed/abandoned), total_time, apis_found, gift_apis_found, quiz_count, completed_at |
| nnvn_rounds | game_id, round_number (1-2), time_seconds, quiz_used |
| nnvn_quiz_questions | question_vi, question_en, options (JSON), options_en (JSON), correct_index, is_active |
| nnvn_quiz_answers | game_id, question_id, answer_index, is_correct |
| nnvn_box_configs | box_index (0-14), url, label, is_active |
| nnvn_box_clicks | game_id, round_number, box_index, is_apis, is_correct, url_opened, clicked_at |

### API Endpoints (11 routes)
```
# Player
POST   /players/register         # {name, email, phone}
GET    /players/me?email=        # Player profile

# Game
POST   /games/start              # {player_id} → trả abandoned_game_id nếu có game cũ bị abandon
POST   /games/{id}/rounds        # {round_number, time_seconds, quiz_used}
POST   /games/{id}/complete      # {gift_apis_found: bool}

# Leaderboard
GET    /leaderboard              # Top players (email masked)
GET    /leaderboard/me?email=    # My rank

# Quiz (bilingual vi/en)
GET    /quiz/random?lang=vi|en   # Random question + options theo ngôn ngữ
POST   /quiz/answer              # {question_id, answer_index, game_id} → lưu vào nnvn_quiz_answers

# Boxes (16 boxes: 15 links + 1 apis)
GET    /boxes/config             # 15 box URLs + random apis_index
POST   /boxes/click              # {game_id, round_number, box_index} → track click
```

### Game Flow
```
Register → Start Game → [Game cũ bị abandon nếu có]
  → Round 1: Click boxes (track clicks) → Tìm Apis
  → Quiz (optional, bilingual)
  → Round 2: Click boxes → Tìm Apis
  → Complete Game {gift_apis_found}
  → Leaderboard update
```

### Game Logic
- **Start Game**: Abandon game cũ (status=playing) với completed_at=NOW(), response trả abandoned_game_id
- **Abandoned**: KHÔNG tính apis, sessions, leaderboard. Chỉ ghi nhận để tracking
- **Complete**: Tính apis_found = APIS_PER_SESSION + (gift ? 1 : 0), cập nhật player stats
- **Leaderboard**: Rank theo best_total_time, email masked (vd: "ngu...com")

### Constants
```php
MAX_APIS = 50           // Tổng số ong tối đa mỗi player
TOTAL_ROUNDS = 2        // Số round mỗi game
APIS_PER_SESSION = 3    // +1 nếu gift_apis_found
TIMER_SECONDS = 30
MIN_ROUND_TIME = 2.0    // Giây
MAX_ROUND_TIME = 30.0   // Giây
```

### Admin Panel (Filament v2)
| Resource/Page | URL | Quyền | Chức năng |
|--------------|-----|-------|-----------|
| NnvnPlayerResource | /admin/nnvn/players | view only | Xem players + games relation |
| NnvnGameResource | /admin/nnvn/games | view only | Xem games + rounds relation, filter status/date |
| NnvnQuizQuestionResource | /admin/nnvn/quiz-questions | full CRUD | Quản lý câu hỏi bilingual |
| NnvnBoxConfigResource | /admin/nnvn/box-configs | full CRUD | Quản lý 15 box URLs |
| NnvnReports | /admin/nnvn/reports | view | Report: Box clicks, Quiz stats, Summary |
| NnvnStatsWidget | /admin (dashboard) | view | 6 cards: Players, Games, APIs, Avg Time, Quiz Answers, Box Clicks |

### Seeders
```bash
php artisan db:seed --class=NnvnQuizQuestionSeeder   # 35 câu quiz bilingual (vi/en)
php artisan db:seed --class=NnvnBoxConfigSeeder       # 15 box configs placeholder URLs
php artisan db:seed --class=ClientRoleSeeder           # Role 'client'
php artisan db:seed --class=ClientAccountSeeder        # client@nnvn.com / Client@nnvn2026
```

### Quiz Translation Export/Import
- Quiz data (35 câu) nằm trong static method `NnvnQuizQuestionSeeder::questions()` — dùng chung cho seeder + export.
- **Export cho brand review bản dịch:**
  ```bash
  php scripts/export_quiz_translation.php   # → storage/app/nnvn_quiz_translation.csv (UTF-8 BOM)
  php scripts/export_quiz_xlsx.php          # → storage/app/nnvn_quiz_translation.xlsx (highlight đáp án đúng)
  ```
  Format cột: STT | Câu hỏi VI | Question EN | A–E (VI/EN cạnh nhau) | Đáp án đúng (A–E).
- **TODO — import ngược sau khi brand edit xong:** cần viết script `scripts/import_quiz_translation.php` đọc file .xlsx/.csv (cùng format cột khi export) để cập nhật lại `question_vi/en`, `options/options_en` vào DB `nnvn_quiz_questions` (match theo STT = thứ tự câu). Lưu ý giữ nguyên `correct_index` theo cột "Đáp án đúng".

### No Auth Required
Email-based identification, CORS-friendly cho game frontend. Không cần Sanctum token.
