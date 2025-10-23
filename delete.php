<?php
$file = 'data.json';
if (!file_exists($file)) file_put_contents($file, '[]');
$data = json_decode(file_get_contents($file), true);

if (isset($_GET['i'])) {
    $i = intval($_GET['i']);
    array_splice($data, $i, 1);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

header('Location: index.php');
exit;
?>
