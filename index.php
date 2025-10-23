<?php
$file = 'data.json';
if (!file_exists($file)) file_put_contents($file, '[]');
$data = json_decode(file_get_contents($file), true);

// Inisialisasi variabel
$total = 0;
$total_income = 0;
$total_expense = 0;
$per_category = [];
$month_income = 0;
$month_expense = 0;
$nowMonth = date('m');
$nowYear = date('Y');

// Hitung total dan kategori
foreach ($data as $item) {
    $amt = floatval($item['amount'] ?? 0);
    $type = $item['type'] ?? 'expense';
    $cat = $item['category'] ?? 'Lainnya';

    if ($type === 'income') {
        $total += $amt;
        $total_income += $amt;
    } else {
        $total -= $amt;
        $total_expense += $amt;
    }

    if (!isset($per_category[$cat])) $per_category[$cat] = 0;
    $per_category[$cat] += ($type === 'income') ? $amt : -$amt;
}

// Hitung data bulanan
foreach ($data as $item) {
    $dateStr = $item['date'] ?? null;
    if (!$dateStr) continue;
    $t = strtotime($dateStr);
    if ($t === false) continue;

    if (date('m', $t) === $nowMonth && date('Y', $t) === $nowYear) {
        if (($item['type'] ?? 'expense') === 'income') $month_income += floatval($item['amount'] ?? 0);
        else $month_expense += floatval($item['amount'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>💼 FinLink — Smart Budget Tracker</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h1>💼 FinLink — Smart Budget Tracker</h1>

    <form action="add_transaction.php" method="POST">
      <input type="text" name="desc" placeholder="Deskripsi" required>
      <input type="number" name="amount" placeholder="Jumlah (Rp)" required>
      <select name="type" required>
        <option value="income">Pemasukan</option>
        <option value="expense">Pengeluaran</option>
      </select>
      <select name="category" required>
        <option value="Gaji">Gaji</option>
        <option value="Makanan">Makanan</option>
        <option value="Transportasi">Transportasi</option>
        <option value="Hiburan">Hiburan</option>
        <option value="Lainnya">Lainnya</option>
      </select>
      <button type="submit">Tambah</button>
    </form>

    <h2>💰 Ringkasan Keuangan</h2>
    <p><strong>Total Saldo:</strong> Rp<?= number_format($total, 0, ',', '.') ?></p>
    <p>Pemasukan: Rp<?= number_format($total_income, 0, ',', '.') ?> | Pengeluaran: Rp<?= number_format($total_expense, 0, ',', '.') ?></p>
    <p><strong>Bulan Ini:</strong> Pemasukan Rp<?= number_format($month_income, 0, ',', '.') ?> | Pengeluaran Rp<?= number_format($month_expense, 0, ',', '.') ?></p>

    <?php if ($total_expense > $total_income): ?>
      <p style="color:red;">⚠️ Pengeluaran kamu lebih besar dari pemasukan bulan ini!</p>
    <?php endif; ?>

    <h2>📊 Pengeluaran per Kategori</h2>
    <ul>
      <?php foreach ($per_category as $cat => $val): ?>
        <li><?= htmlspecialchars($cat) ?>: Rp<?= number_format($val, 0, ',', '.') ?></li>
      <?php endforeach; ?>
    </ul>

    <h2>📋 Daftar Transaksi</h2>
    <table>
      <tr>
        <th>Tanggal</th>
        <th>Deskripsi</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Jumlah</th>
        <th>Aksi</th>
      </tr>
      <?php foreach (array_reverse($data) as $index => $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['date'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['desc'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['category'] ?? '-') ?></td>
        <td><?= htmlspecialchars($row['type'] ?? '-') ?></td>
        <td>Rp<?= number_format($row['amount'] ?? 0, 0, ',', '.') ?></td>
        <td><a href="delete.php?i=<?= $index ?>">Hapus</a></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>
