<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = 'data.json';
    if (!file_exists($file)) file_put_contents($file, '[]');
    $data = json_decode(file_get_contents($file), true);

    $new = [
        'desc' => $_POST['desc'] ?? '',
        'amount' => floatval($_POST['amount'] ?? 0),
        'type' => $_POST['type'] ?? 'expense',
        'category' => $_POST['category'] ?? 'Lainnya',
        'date' => date('Y-m-d H:i:s')
    ];

    $data[] = $new;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: index.php');
    exit;
}
?>
