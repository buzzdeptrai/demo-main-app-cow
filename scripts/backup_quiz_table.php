<?php
/**
 * USAGE: php scripts/backup_quiz_table.php
 * Tạo bảng backup của nnvn_quiz_questions trước khi re-seed/update.
 * Tên backup có timestamp nên không ghi đè bản cũ: nnvn_quiz_questions_backup_YmdHis
 * Chạy local (nối DB VPS qua .env) hoặc trên VPS đều được.
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$source = 'nnvn_quiz_questions';
$backup = $source . '_backup_' . date('Ymd_His');

if (!Schema::hasTable($source)) {
    fwrite(STDERR, "!! Bảng nguồn '$source' không tồn tại.\n");
    exit(1);
}

$srcCount = DB::table($source)->count();

// Tạo bảng backup giống cấu trúc + copy toàn bộ dữ liệu
DB::statement("CREATE TABLE `$backup` LIKE `$source`");
DB::statement("INSERT INTO `$backup` SELECT * FROM `$source`");

$bkCount = DB::table($backup)->count();

echo "Source : $source ($srcCount rows)\n";
echo "Backup : $backup ($bkCount rows)\n";

if ($srcCount !== $bkCount) {
    fwrite(STDERR, "!! CẢNH BÁO: số dòng không khớp — kiểm tra lại!\n");
    exit(1);
}

echo "OK: backup thành công, số dòng khớp.\n";
echo "Restore (nếu cần): INSERT INTO $source SELECT * FROM $backup; (sau khi TRUNCATE $source)\n";
