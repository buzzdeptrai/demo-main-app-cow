<?php
/**
 * USAGE: php scripts/export_quiz_xlsx.php
 * Export 35 câu quiz song ngữ (VI/EN) ra file Excel .xlsx thật, highlight đáp án đúng (xanh lá).
 * Output: storage/app/nnvn_quiz_translation.xlsx
 * Không cần PHPSpreadsheet — build xlsx (zip + XML) trực tiếp, chỉ cần ext zip.
 */

require __DIR__ . '/../vendor/autoload.php';

use Database\Seeders\NnvnQuizQuestionSeeder;

$questions = NnvnQuizQuestionSeeder::questions();
$outPath = __DIR__ . '/../storage/app/nnvn_quiz_translation.xlsx';

$headers = [
    'STT', 'Câu hỏi (VI)', 'Question (EN)',
    'A (VI)', 'A (EN)', 'B (VI)', 'B (EN)',
    'C (VI)', 'C (EN)', 'D (VI)', 'D (EN)',
    'E (VI)', 'E (EN)', 'Đáp án đúng',
];
$letters = ['A', 'B', 'C', 'D', 'E'];

// Style index: 0=default, 1=header, 2=body, 3=correct(green), 4=center
function esc(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function colLetter(int $i): string
{
    $s = '';
    for ($n = $i; $n >= 0; $n = intdiv($n, 26) - 1) {
        $s = chr(65 + ($n % 26)) . $s;
    }
    return $s;
}

function cell(int $col, int $row, string $text, int $style): string
{
    $ref = colLetter($col) . $row;
    return '<c r="' . $ref . '" t="inlineStr" s="' . $style . '">'
        . '<is><t xml:space="preserve">' . esc($text) . '</t></is></c>';
}

// --- Build sheet rows ---
$rows = '';
$r = 1;

// Header row
$rows .= '<row r="' . $r . '" ht="30" customHeight="1">';
foreach ($headers as $c => $h) {
    $rows .= cell($c, $r, $h, 1);
}
$rows .= '</row>';
$r++;

foreach ($questions as $q) {
    $correct = (int) $q['correct_index'];
    $correctVi = 3 + $correct * 2; // column index of correct VI option
    $correctEn = $correctVi + 1;

    $rows .= '<row r="' . $r . '">';

    $values = [
        0 => (string) ($r - 1),
        1 => $q['question_vi'],
        2 => $q['question_en'],
    ];
    for ($j = 0; $j < 5; $j++) {
        $values[3 + $j * 2] = $q['options'][$j] ?? '';
        $values[4 + $j * 2] = $q['options_en'][$j] ?? '';
    }
    $values[13] = $letters[$correct] ?? '';

    foreach ($values as $c => $val) {
        $style = 2; // body
        if ($c === 0 || $c === 13) {
            $style = 4; // center
        }
        if ($c === $correctVi || $c === $correctEn || ($c === 13)) {
            $style = 3; // highlight correct
        }
        $rows .= cell($c, $r, (string) $val, $style);
    }

    $rows .= '</row>';
    $r++;
}

// Column widths
$cols = '<cols>'
    . '<col min="1" max="1" width="5" customWidth="1"/>'
    . '<col min="2" max="3" width="45" customWidth="1"/>'
    . '<col min="4" max="13" width="38" customWidth="1"/>'
    . '<col min="14" max="14" width="11" customWidth="1"/>'
    . '</cols>';

$sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
    . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
    . $cols
    . '<sheetData>' . $rows . '</sheetData>'
    . '</worksheet>';

// --- Styles ---
$stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
    . '<fonts count="2">'
    . '<font><sz val="11"/><name val="Calibri"/></font>'
    . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
    . '</fonts>'
    . '<fills count="4">'
    . '<fill><patternFill patternType="none"/></fill>'
    . '<fill><patternFill patternType="gray125"/></fill>'
    . '<fill><patternFill patternType="solid"><fgColor rgb="FF305496"/></patternFill></fill>'   // header blue
    . '<fill><patternFill patternType="solid"><fgColor rgb="FFC6EFCE"/></patternFill></fill>'   // correct green
    . '</fills>'
    . '<borders count="2">'
    . '<border><left/><right/><top/><bottom/><diagonal/></border>'
    . '<border><left style="thin"><color rgb="FFBFBFBF"/></left><right style="thin"><color rgb="FFBFBFBF"/></right><top style="thin"><color rgb="FFBFBFBF"/></top><bottom style="thin"><color rgb="FFBFBFBF"/></bottom></border>'
    . '</borders>'
    . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
    . '<cellXfs count="5">'
    . '<xf xfId="0" fontId="0" fillId="0" borderId="0"/>'                                                                                   // 0 default
    . '<xf xfId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'  // 1 header
    . '<xf xfId="0" fontId="0" fillId="0" borderId="1" applyBorder="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>'     // 2 body
    . '<xf xfId="0" fontId="0" fillId="3" borderId="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>'  // 3 correct green
    . '<xf xfId="0" fontId="0" fillId="0" borderId="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'  // 4 center
    . '</cellXfs>'
    . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
    . '</styleSheet>';

// --- Workbook / rels / content types ---
$workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
    . '<sheets><sheet name="Quiz Translation" sheetId="1" r:id="rId1"/></sheets>'
    . '</workbook>';

$workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
    . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
    . '</Relationships>';

$rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
    . '</Relationships>';

$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
    . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
    . '<Default Extension="xml" ContentType="application/xml"/>'
    . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
    . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
    . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
    . '</Types>';

// --- Zip it up ---
@unlink($outPath);
$zip = new ZipArchive();
if ($zip->open($outPath, ZipArchive::CREATE) !== true) {
    fwrite(STDERR, "Cannot create xlsx\n");
    exit(1);
}
$zip->addFromString('[Content_Types].xml', $contentTypes);
$zip->addFromString('_rels/.rels', $rootRels);
$zip->addFromString('xl/workbook.xml', $workbookXml);
$zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRels);
$zip->addFromString('xl/styles.xml', $stylesXml);
$zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
$zip->close();

echo "Exported " . count($questions) . " questions to: $outPath\n";
