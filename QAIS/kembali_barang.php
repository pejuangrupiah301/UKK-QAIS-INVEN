<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include("database.php");

// Ambil ID barang dari URL
$id = $_GET['id'];

// Ambil data barang berdasarkan ID
$data = mysqli_query($conn, "SELECT * FROM barang WHERE id=$id");
$barang = mysqli_fetch_assoc($data);

// Jika barang tidak ditemukan
if (!$barang) die("Barang tidak ditemukan");

// Hitung jumlah barang yang sedang dipinjam
$dipinjam = $barang['jumlah'] - $barang['tersedia'];
$error = "";

/* ====================================================
   AMBIL PEMINJAM TERAKHIR DARI TABEL TRANSAKSI
   (transaksi jenis 'pinjam' untuk barang ini)
======================================================*/
$qPeminjam = mysqli_query($conn, "
    SELECT peminjam 
    FROM transaksi 
    WHERE barang_id = $id AND jenis='pinjam'
    ORDER BY id DESC LIMIT 1
");

$trxPeminjam = mysqli_fetch_assoc($qPeminjam);
$peminjam_terakhir = $trxPeminjam ? $trxPeminjam['peminjam'] : null;

// Jika tombol submit ditekan
if (isset($_POST['submit'])) {

    // Ambil data dari form
    $peminjam = trim($_POST['peminjam']);
    $jumlah = $_POST['jumlah'];
    $catatan = $_POST['catatan'];

    // Validasi input kosong / jumlah tidak valid
    if ($peminjam === "" || $jumlah <= 0) {
        $error = "Isi nama pengembali & jumlah yang benar";
    }
    // Cek apakah tidak ada barang yang sedang dipinjam
    elseif ($dipinjam <= 0) {
        $error = "Tidak ada barang yang sedang dipinjam";
    }
    /* =====================================================
       VALIDASI: Nama pengembali harus sama dengan peminjam
       strcasecmp = membandingkan string tanpa membedakan 
       huruf besar & kecil
    =======================================================*/
    elseif (strcasecmp($peminjam, $peminjam_terakhir) !== 0) {
        $error = "Nama pengembali tidak sesuai dengan peminjam terakhir ($peminjam_terakhir). Barang tidak bisa dikembalikan.";
        
        // Notifikasi alert
        echo "<script>alert('Barang tidak bisa dikembalikan! Nama tidak sesuai.');</script>";
    }
    // Jika jumlah pengembalian lebih besar dari barang yang dipinjam
    elseif ($jumlah > $dipinjam) {
        $error = "Jumlah pengembalian melebihi barang yang dipinjam";
    }
    else {
        /* =====================================================
           SIMPAN TRANSAKSI PENGEMBALIAN KE DATABASE
        ======================================================*/
        mysqli_query($conn, "
            INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan)
            VALUES ($id, '$peminjam', 'kembali', $jumlah, '$catatan')
        ");

        /* =====================================================
           UPDATE jumlah barang tersedia (tersedia + jumlah)
        ======================================================*/
        mysqli_query($conn, "
            UPDATE barang 
            SET tersedia = LEAST(jumlah, tersedia + $jumlah) 
            WHERE id = $id
        ");

        // Notifikasi berhasil dan kembali ke dashboard
        echo "<script>alert('Barang berhasil dikembalikan'); window.location='dashboard.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kembali Barang | Inventaris</title>

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
        rgba(230, 242, 255, 0.95) 30%,
        rgba(0, 110, 255, 0.18) 100%
    );

    font-family: "Poppins", sans-serif;
}

/* ======================= BOX CONTAINER ======================= */
.container {
    width: 92%;
    max-width: 520px;
    padding: 36px;
    border-radius: 18px;
    position: relative;
    background: linear-gradient(135deg, #ffffff, #eaf3ff);
    box-shadow:
        0 0 40px rgba(0, 110, 255, 0.12),
        0 18px 40px rgba(0, 0, 0, 0.10),
        inset 0 0 10px rgba(0, 110, 255, 0.06);
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
        0 0 25px rgba(0, 110, 255, 0.28),
        0 0 45px rgba(0, 110, 255, 0.18);
    pointer-events: none;
}

/* ======================= LIGHT MUTER ======================= */
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
    pointer-events: none;
}

@keyframes rotateLight {
    0%   { transform: translateX(-50%) rotate(0deg); }
    100% { transform: translateX(-50%) rotate(360deg); }
}

/* ======================= TITLE ======================= */
.container h2 {
    text-align: center;
    margin-bottom: 10px;
    font-weight: 700;
    color: #004aad;
    text-shadow: 0 0 6px rgba(0, 110, 255, 0.22);
    font-size: 20px;
}

/* ======================= INFO BOX ======================= */
.info {
    background: #f4fbff;
    border-left: 4px solid #3f7bff;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 18px;
    color: #003a80;
    font-weight: 600;
}

/* ======================= FORM ELEMENTS ======================= */
label {
    color: #003a80;
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
}

input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 14px;
    border-radius: 10px;
    background: #f9fbff;
    border: 1px solid #cfe4ff;
    color: #003a80;
    outline: none;
    transition: 0.18s;
    font-size: 14px;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus {
    border-color: #006eff;
    box-shadow: 0 0 12px rgba(0, 110, 255, 0.18);
}

textarea {
    min-height: 100px;
    resize: vertical;
}

/* ======================= ERROR MESSAGE ======================= */
.error {
    margin-bottom: 12px;
    padding: 10px 12px;
    border-radius: 8px;
    background: rgba(255, 60, 60, 0.10);
    border-left: 4px solid #ff4d4d;
    color: #8b0000;
    font-weight: 700;
    box-shadow: 0 0 10px rgba(255, 60, 60, 0.06);
    animation: shake 0.25s ease;
}

@keyframes shake {
    0%,100% { transform: translateX(0); }
    25% { transform: translateX(-6px); }
    75% { transform: translateX(6px); }
}

/* ======================= ACTIONS ======================= */
button {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #006eff, #003d82);
    color: #eaf3ff;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: 0.18s;
    box-shadow:
        0 0 14px rgba(0, 110, 255, 0.25),
        0 8px 20px rgba(0,0,0,0.08);
}

button:hover {
    transform: translateY(-3px);
    box-shadow:
        0 0 22px rgba(0, 110, 255, 0.35),
        0 12px 28px rgba(0,0,0,0.12);
}

/* ======================= LINK KEMBALI ======================= */
.container a {
    display: block;
    text-align: center;
    margin-top: 12px;
    text-decoration: none;
    color: #006eff;
    font-weight: 700;
    font-size: 15px;
    text-shadow: 0 0 6px rgba(0, 110, 255, 0.28);
    transition: 0.18s;
}

.container a:hover {
    color: #003d82;
    text-decoration: underline;
    transform: translateY(-2px);
}

/* ======================= RESPONSIVE ======= */
@media (max-width: 480px) {
    .container { padding: 22px; width: 92%; }
    .container h2 { font-size: 18px; }
    input[type="text"], input[type="number"], textarea { padding: 10px; }
}

  </style>
</head>
<body>
  <div class="container">
    <h2>Kembalikan Barang: <?= $barang['nama'] ?></h2>
    <p><b>Stok yang dipinjam saat ini: <?= $dipinjam ?></b></p>

    <form action="" method="POST">
      
      <label for="peminjam">Nama Pengembali:</label><br>
      <input type="text" name="peminjam" id="peminjam" required><br><br>

      <label for="jumlah">Jumlah yang Dikembalikan:</label><br>
      <input type="number" name="jumlah" value="1" min="1" max="<?= $dipinjam ?>" required><br><br>

      <label for="catatan">Catatan:</label><br>
      <textarea name="catatan" id="catatan" required></textarea><br><br>

      <button type="submit" name="submit">Kembalikan</button>
      <a href="dashboard.php">Kembali</a>

    </form>
  </div>
</body>
</html>