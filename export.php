<?php
$file = 'data.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

$filename = 'finlink_transactions_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

echo "\xEF\xBB\xBF";

$out = fopen('php://output', 'w');
fputcsv($out, ['Deskripsi','Jumlah','Jenis','Kategori','Tanggal']);

foreach ($data as $row) {
    fputcsv($out, [
        $row['desc'] ?? '',
        $row['amount'] ?? '',
        $row['type'] ?? '',
        $row['category'] ?? '',
        $row['date'] ?? ''
    ]);
}
fclose($out);
exit;
