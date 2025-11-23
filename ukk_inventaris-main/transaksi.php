<?php
  session_start();

  if (!isset($_SESSION['login'])) {
      header("location: login.php");
      exit;
  }

  include("database.php");

  $query = "SELECT transaksi.*, barang.nama AS nama_barang, barang.kode AS kode_barang FROM transaksi 
  JOIN barang ON transaksi.barang_id = barang_id ORDER BY transaksi.id DESC ";

  $result = mysqli_query ($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Barang | Inventaris</title>

  <style>
    /* ======================= GLOBAL ======================= */
body {
    margin: 0;
    padding: 40px 0;
    font-family: "Poppins", sans-serif;

    background: radial-gradient(circle at top,
        rgba(255, 255, 255, 1) 0%,
        rgba(230, 242, 255, 0.9) 35%,
        rgba(0, 110, 255, 0.25) 100%
    );

    display: flex;
    flex-direction: column;
    align-items: center;
}

/* ======================= TITLE ======================= */
h2 {
    color: #004aad;
    margin-bottom: 25px;
    text-shadow: 0 0 6px rgba(0, 110, 255, 0.25);
    font-weight: 600;
}

/* ======================= LINK KEMBALI ======================= */
a {
    font-weight: 600;
    color: #0058d4;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 10px;
    transition: 0.25s;
}

a:hover {
    background: rgba(0, 110, 255, 0.12);
    box-shadow: 0 4px 12px rgba(0, 110, 255, 0.25);
}

/* ======================= TABLE WRAPPER (BOX) ======================= */
table {
    width: 90%;
    max-width: 1000px;

    border-collapse: collapse;
    background: linear-gradient(135deg, #ffffff, #eaf3ff);

    border-radius: 18px;
    overflow: hidden;

    box-shadow:
        0 0 40px rgba(0, 110, 255, 0.15),
        0 20px 50px rgba(0, 0, 0, 0.15),
        inset 0 0 12px rgba(0, 110, 255, 0.10);
}

/* ======================= TABLE HEADER ======================= */
table th {
    padding: 14px;
    background: rgba(0, 110, 255, 0.10);
    color: #003a80;
    font-weight: 700;
    border-bottom: 2px solid #bcd2f8;
    text-align: left;
}

/* ======================= TABLE DATA ======================= */
table td {
    padding: 12px;
    border-bottom: 1px solid #dbe7ff;
    color: #003060;
}

/* ======================= ODD ROW STRIPES ======================= */
table tr:nth-child(even) {
    background: rgba(0, 110, 255, 0.05);
}

/* ======================= LAST ROW BORDER FIX ======================= */
table tr:last-child td {
    border-bottom: none;
}

  </style>
</head>
<body>
  <h2>Riwayat Transaksi Barang</h2>
  <a href="dashboard.php">Kembali ke Dashboard</a>
  <br><br>

  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>Tanggal dan Waktu</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>peminjam / Pengembali</th>
      <th>jenis</th>
      <th>Jumlah</th>
      <th>Catatan</th>
    </tr>

    <?php while ($data = mysqli_fetch_assoc($result)): ?>
    
    <tr>
      <td><?= $data['tanggal'] ?></td>
      <td><?= $data['kode_barang'] ?></td>
      <td><?= $data['nama_barang'] ?></td>
      <td><?= $data['peminjam'] ?></td>
      <td>
        <?php if ($data['jenis'] == 'pinjam'): ?>
          <span style="color: red; font-weight: semibold">Pinjam</span>
        <?php else: ?>
          <span style="color: green; font-weight: semibold">Kembali</span>
        <?php endif; ?>
      </td>
      <td><?= $data['jumlah'] ?></td>
      <td><?= $data['catatan'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>