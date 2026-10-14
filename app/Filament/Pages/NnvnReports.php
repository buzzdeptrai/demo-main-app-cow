<?php

namespace App\Filament\Pages;

use App\MiniApps\NnvnApisGo\Constants;
use App\MiniApps\NnvnApisGo\Models\BoxClick;
use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Models\Player;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NnvnReports extends Page
{
    private const CLEAR_PIN = '585668';

    private const BACKUP_DIR = 'nnvn-backups';

    // Thứ tự con → cha (dùng khi xóa). Restore sẽ đảo ngược (cha → con).
    private const DATA_TABLES = [
        'nnvn_box_clicks',
        'nnvn_quiz_answers',
        'nnvn_rounds',
        'nnvn_games',
        'nnvn_players',
    ];

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'NNVN Apis Go';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Reports';

    protected static ?string $slug = 'nnvn/reports';

    protected static string $view = 'filament.pages.nnvn-reports';

    protected function getActions(): array
    {
        $isAdmin = fn () => auth()->user()?->hasRole('admin');

        return [
            Actions\Action::make('clearTestData')
                ->label('Clear Test Data')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->visible($isAdmin)
                ->modalHeading('Xóa toàn bộ dữ liệu game test')
                ->modalButton('Backup & Xóa')
                ->modalSubheading('Tự động backup trước khi xóa players, games, rounds, box clicks, quiz answers. GIỮ lại box configs & quiz questions. Nhập PIN để xác nhận.')
                ->form([
                    TextInput::make('pincode')
                        ->label('PIN Code')
                        ->password()
                        ->required()
                        ->maxLength(10),
                ])
                ->action(fn (array $data) => $this->clearTestData($data)),

            Actions\Action::make('restoreBackup')
                ->label('Restore Backup')
                ->icon('heroicon-o-refresh')
                ->color('warning')
                ->visible($isAdmin)
                ->modalHeading('Khôi phục dữ liệu từ backup gần nhất')
                ->modalButton('Khôi phục')
                ->modalSubheading(fn () => $this->latestBackupLabel())
                ->form([
                    TextInput::make('pincode')
                        ->label('PIN Code')
                        ->password()
                        ->required()
                        ->maxLength(10),
                ])
                ->action(fn (array $data) => $this->restoreBackup($data)),
        ];
    }

    private function clearTestData(array $data): void
    {
        if (!$this->pinValid($data)) {
            return;
        }

        $backupFile = $this->backupTables();

        // Xóa theo thứ tự con → cha để tôn trọng foreign key.
        $deleted = [];
        foreach (self::DATA_TABLES as $table) {
            $deleted[$table] = DB::table($table)->delete();
        }

        Cache::forget('nnvn:gift_count');

        Notification::make()
            ->success()
            ->title('Đã xóa dữ liệu game test')
            ->body(sprintf(
                "Backup: %s\nPlayers: %d · Games: %d · Rounds: %d · Box clicks: %d · Quiz answers: %d",
                $backupFile,
                $deleted['nnvn_players'],
                $deleted['nnvn_games'],
                $deleted['nnvn_rounds'],
                $deleted['nnvn_box_clicks'],
                $deleted['nnvn_quiz_answers']
            ))
            ->persistent()
            ->send();
    }

    private function restoreBackup(array $data): void
    {
        if (!$this->pinValid($data)) {
            return;
        }

        $file = $this->latestBackupFile();

        if ($file === null) {
            Notification::make()
                ->danger()
                ->title('Không có backup')
                ->body('Chưa có file backup nào để khôi phục.')
                ->send();

            return;
        }

        $payload = json_decode(Storage::disk('local')->get($file), true);
        $tables = $payload['tables'] ?? [];

        DB::transaction(function () use ($tables) {
            // Xóa hiện tại (con → cha) rồi insert lại (cha → con).
            foreach (self::DATA_TABLES as $table) {
                DB::table($table)->delete();
            }

            foreach (array_reverse(self::DATA_TABLES) as $table) {
                $rows = $tables[$table] ?? [];
                $generated = $this->generatedColumns($table);

                if ($generated) {
                    $rows = array_map(
                        fn (array $row) => array_diff_key($row, array_flip($generated)),
                        $rows
                    );
                }

                foreach (array_chunk($rows, 500) as $chunk) {
                    DB::table($table)->insert($chunk);
                }
            }
        });

        Cache::forget('nnvn:gift_count');

        Notification::make()
            ->success()
            ->title('Đã khôi phục dữ liệu')
            ->body('Từ backup: ' . basename($file))
            ->persistent()
            ->send();
    }

    private function pinValid(array $data): bool
    {
        if (str_replace(' ', '', (string) $data['pincode']) === self::CLEAR_PIN) {
            return true;
        }

        Notification::make()
            ->danger()
            ->title('Sai PIN')
            ->body('PIN không đúng. Hủy thao tác.')
            ->send();

        return false;
    }

    private function backupTables(): string
    {
        $tables = [];
        foreach (self::DATA_TABLES as $table) {
            $tables[$table] = DB::table($table)->get()
                ->map(fn ($row) => (array) $row)
                ->all();
        }

        $filename = self::BACKUP_DIR . '/nnvn_backup_' . now()->format('Ymd_His') . '.json';

        Storage::disk('local')->put($filename, json_encode([
            'created_at' => now()->toDateTimeString(),
            'tables' => $tables,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return basename($filename);
    }

    private function generatedColumns(string $table): array
    {
        $rows = DB::select(
            'SELECT COLUMN_NAME FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND EXTRA LIKE ?',
            [$table, '%GENERATED%']
        );

        return array_map(fn ($r) => $r->COLUMN_NAME, $rows);
    }

    private function latestBackupFile(): ?string
    {
        $files = Storage::disk('local')->files(self::BACKUP_DIR);

        if (empty($files)) {
            return null;
        }

        rsort($files); // tên có timestamp Ymd_His → sort giảm = mới nhất đứng đầu

        return $files[0];
    }

    private function latestBackupLabel(): string
    {
        $file = $this->latestBackupFile();

        return $file
            ? 'Sẽ khôi phục từ: ' . basename($file) . '. Dữ liệu hiện tại bị ghi đè. Nhập PIN để xác nhận.'
            : 'CHƯA có backup nào. Hãy chạy Clear Test Data trước (nó tự tạo backup).';
    }

    protected function getViewData(): array
    {
        $boxClicks = $this->getBoxClickReport();
        $messageReport = $this->getMessageReport();
        $ecardReport = $this->getEcardReport();
        $quizStats = $this->getQuizReport();
        $summary = $this->getSummary();
        $leaderboard = $this->getLeaderboard();

        return compact('boxClicks', 'messageReport', 'ecardReport', 'quizStats', 'summary', 'leaderboard');
    }

    private function getBoxClickReport()
    {
        return DB::table('nnvn_box_clicks as c')
            ->leftJoin('nnvn_box_configs as b', 'c.url_opened', '=', 'b.url')
            ->select(
                'c.url_opened as url',
                DB::raw('MAX(b.label) as label'),
                DB::raw('COUNT(*) as total_clicks')
            )
            ->where('c.box_type', 'link')
            ->groupBy('c.url_opened')
            ->orderByDesc('total_clicks')
            ->get();
    }

    private function getMessageReport(): array
    {
        $byMessage = DB::table('nnvn_box_clicks')
            ->select(
                'message_text',
                DB::raw('COUNT(*) as clicks'),
                DB::raw('MAX(clicked_at) as last_click')
            )
            ->where('box_type', 'message')
            ->groupBy('message_text')
            ->orderByDesc('clicks')
            ->get();

        return [
            'by_message' => $byMessage,
            'total' => BoxClick::where('box_type', 'message')->count(),
        ];
    }

    private function getEcardReport(): array
    {
        // Nhãn vi theo key, để hiển thị thân thiện thay vì key thô.
        $labels = collect(Constants::BOX_ECARDS)
            ->mapWithKeys(fn ($e) => [$e['key'] => $e['vi']])
            ->all();

        $byKey = DB::table('nnvn_box_clicks')
            ->select(
                'ecard_key',
                DB::raw('COUNT(*) as clicks'),
                DB::raw('MAX(clicked_at) as last_click')
            )
            ->where('box_type', 'ecard')
            ->groupBy('ecard_key')
            ->orderByDesc('clicks')
            ->get()
            ->map(function ($row) use ($labels) {
                $row->label = $labels[$row->ecard_key] ?? null;

                return $row;
            });

        return [
            'by_key' => $byKey,
            'total' => BoxClick::where('box_type', 'ecard')->count(),
        ];
    }

    private function getQuizReport()
    {
        return DB::table('nnvn_quiz_answers as a')
            ->join('nnvn_quiz_questions as q', 'a.question_id', '=', 'q.id')
            ->select(
                'q.id',
                'q.question_vi',
                DB::raw('COUNT(*) as total_answers'),
                DB::raw('SUM(CASE WHEN a.is_correct = 1 THEN 1 ELSE 0 END) as correct_count'),
                DB::raw('SUM(CASE WHEN a.is_correct = 0 THEN 1 ELSE 0 END) as wrong_count'),
                DB::raw('ROUND(SUM(CASE WHEN a.is_correct = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as correct_rate')
            )
            ->groupBy('q.id', 'q.question_vi')
            ->orderBy('correct_rate')
            ->get();
    }

    private function getSummary(): array
    {
        $giftUsed = Game::where('gift_apis_found', true)
            ->where('status', 'completed')
            ->count();
        $giftLimit = Constants::MAX_GIFT_APIS_GLOBAL;

        return [
            'total_apis' => Game::where('status', 'completed')->sum('apis_found'),
            'total_games' => Game::where('status', 'completed')->count(),
            'total_box_clicks' => BoxClick::count(),
            'message_clicks' => BoxClick::where('box_type', 'message')->count(),
            'ecard_clicks' => BoxClick::where('box_type', 'ecard')->count(),
            'gift_used' => $giftUsed,
            'gift_limit' => $giftLimit,
            'gift_remaining' => $giftLimit - $giftUsed,
        ];
    }

    private function getLeaderboard()
    {
        return Player::where('total_sessions', '>', 0)
            ->orderByRaw('(round_apis_found + gift_apis_found) DESC')
            ->orderBy('best_total_time', 'asc')
            ->limit(20)
            ->get();
    }
}
