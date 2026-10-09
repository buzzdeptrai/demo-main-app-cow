<?php
/**
 * USAGE: php scripts/export_quiz_translation.php
 * Export 35 câu quiz song ngữ (VI/EN) ra file CSV để khách review bản dịch.
 * Output: storage/app/nnvn_quiz_translation.csv (UTF-8 BOM, mở được bằng Excel)
 */

require __DIR__ . '/../vendor/autoload.php';

use Database\Seeders\NnvnQuizQuestionSeeder;

$questions = NnvnQuizQuestionSeeder::questions();

$outPath = __DIR__ . '/../storage/app/nnvn_quiz_translation.csv';
$fp = fopen($outPath, 'w');

// UTF-8 BOM để Excel hiển thị tiếng Việt đúng
fwrite($fp, "\xEF\xBB\xBF");

$header = [
    'STT', 'Câu hỏi (VI)', 'Question (EN)',
    'A (VI)', 'A (EN)', 'B (VI)', 'B (EN)',
    'C (VI)', 'C (EN)', 'D (VI)', 'D (EN)',
    'E (VI)', 'E (EN)', 'Đáp án đúng',
];
fputcsv($fp, $header);

$letters = ['A', 'B', 'C', 'D', 'E'];

foreach ($questions as $i => $q) {
    $row = [$i + 1, $q['question_vi'], $q['question_en']];

    for ($j = 0; $j < 5; $j++) {
        $row[] = $q['options'][$j] ?? '';
        $row[] = $q['options_en'][$j] ?? '';
    }

    $row[] = $letters[$q['correct_index']] ?? '';
    fputcsv($fp, $row);
}

fclose($fp);

echo "Exported " . count($questions) . " questions to: $outPath\n";
