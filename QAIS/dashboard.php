<?php
  session_start();

  if (!isset($_SESSION['login'])){
    header ('location: login.php');
    exit;
  }

  include ('database.php');

  $result = mysqli_query ($conn, "SELECT * FROM barang ORDER BY id DESC")

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barang | Inventaris</title>

  <style>
    /* ======================= GLOBAL ======================= */
body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;

    background: radial-gradient(circle at top,
        rgba(255, 255, 255, 1) 0%,
        rgba(230, 242, 255, 0.9) 35%,
        rgba(0, 110, 255, 0.25) 100%
    );

    font-family: "Poppins", sans-serif;
}

/* ======================= BOX CONTAINER ======================= */
.container {
    width: 92%;
    max-width: 1100px;
    padding: 40px;
    border-radius: 18px;
    position: relative;

    background: linear-gradient(135deg, #ffffff, #eaf3ff);

    box-shadow:
        0 0 40px rgba(0, 110, 255, 0.15),
        0 20px 50px rgba(0, 0, 0, 0.15),
        inset 0 0 12px rgba(0, 110, 255, 0.10);

    overflow: hidden;
}

/* ======================= CYBER EDGE BORDER ======================= */
.container::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 18px;

    background: linear-gradient(
        120deg,
        rgba(0, 110, 255, 0.85),
        rgba(0, 110, 255, 0.25),
        rgba(0, 110, 255, 0.85)
    );
    padding: 2px;

    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
            mask-composite: exclude;

    box-shadow:
        0 0 25px rgba(0, 110, 255, 0.35),
        0 0 45px rgba(0, 110, 255, 0.25);

    pointer-events: none;
}

/* ======================= LIGHT ROTATING GLOW ======================= */
.container::before {
    content: "";
    position: absolute;
    top: -60px;
    left: 50%;
    width: 210px;
    height: 210px;
    transform: translateX(-50%);
    border-radius: 50%;

    background: radial-gradient(
        rgba(0, 120, 255, 0.18),
        transparent 70%
    );

    filter: blur(26px);
    animation: rotateLight 14s linear infinite;

    pointer-events: none;   /* ← WAJIB */
}


@keyframes rotateLight {
    0%   { transform: translateX(-50%) rotate(0deg); }
    100% { transform: translateX(-50%) rotate(360deg); }
}

/* ======================= TITLE ======================= */
.container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #004aad;
    text-shadow: 0 0 6px rgba(0, 110, 255, 0.25);
}

/* ======================= TOP MENU LINKS ======================= */
.menu {
    text-align: center;
    margin-bottom: 25px;
}

.menu a {
    margin: 0 12px;
    text-decoration: none;
    color: #006eff;
    font-weight: 700;
    font-size: 15px;
    transition: 0.25s;
}

.menu a:hover {
    color: #003d82;
    text-decoration: underline;
    transform: translateY(-2px);
}

/* ======================= TABLE ======================= */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: #f9fbff;
    border-radius: 14px;
    overflow: hidden;

    box-shadow:
        0 0 20px rgba(0, 110, 255, 0.15),
        inset 0 0 8px rgba(0, 110, 255, 0.1);
}

table th {
    padding: 13px;
    background: linear-gradient(135deg, #006eff, #003d82);
    color: #eaf3ff;
    font-weight: 600;
    text-align: left;
}

table td {
    padding: 12px;
    color: #003a80;
    border-bottom: 1px solid #d9e6ff;
}

table tr:nth-child(even) {
    background: #eef4ff;
}

table tr:hover {
    background: #d9e9ff;
    transition: 0.25s;
}

/* ======================= ACTION LINKS ======================= */
.action a {
    text-decoration: none;
    font-weight: 700;
    color: #006eff;
    margin: 0 4px;
    transition: 0.25s;
}

.action a:hover {
    color: #003d82;
    text-decoration: underline;
    transform: translateY(-2px);
}

  </style>
</head>
<body>
<div class="container">

    <h2>Data Barang Inventaris</h2>

    <div class="menu">
        <a href="add_barang.php">Tambah Barang</a>
        <a href="transaksi.php">Lihat Transaksi</a>
        <a href="logout.php">Logout</a>
    </div>

    <table>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Jumlah</th>
            <th>Tersedia</th>
            <th>Lokasi</th>
            <th>Aksi</th>
        </tr>

        <?php while ($data = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $data['kode'] ?></td>
            <td><?= $data['nama'] ?></td>
            <td><?= $data['deskripsi'] ?></td>
            <td><?= $data['jumlah'] ?></td>
            <td><?= $data['tersedia'] ?></td>
            <td><?= $data['lokasi'] ?></td>
            <td class="action">
                <a href="edit_barang.php?id=<?= $data['id'] ?>">Edit</a> |
                <a href="hapus_barang.php?id=<?= $data['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a> |
                <a href="pinjam_barang.php?id=<?= $data['id'] ?>">Pinjam</a>
                <?php $dipinjam = $data['jumlah'] - $data['tersedia']; ?>
                <?php if ($dipinjam > 0): ?>
                    | <a href="kembali_barang.php?id=<?= $data['id'] ?>">Kembalikan</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</div>
</body>
