<?php
$file = 'data.json';
if (!file_exists($file)) {
    echo "data.json tidak ditemukan.";
    exit;
}
$data = json_decode(file_get_contents($file), true);
if (!is_array($data)) $data = [];

$changed = 0;
for ($i=0; $i<count($data); $i++) {
    if (!isset($data[$i]['date']) || empty($data[$i]['date'])) {
        $data[$i]['date'] = date('Y-m-d H:i:s'); 
        $changed++;
    }
}
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
echo "Selesai. Menambahkan date ke $changed entri.";
