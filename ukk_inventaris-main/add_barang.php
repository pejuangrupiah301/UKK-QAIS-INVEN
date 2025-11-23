<?php
  session_start();

  if (!isset($_SESSION['login'])) {
      header("location: login.php");
      exit;
  }

  include("database.php");

  if (isset($_POST['submit'])) {

    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $jumlah = $_POST['jumlah'];
    $tersedia = $_POST['tersedia'];
    $lokasi = $_POST['lokasi'];
    $kode = $_POST['kode'];

    $query = "INSERT INTO barang (nama, deskripsi, jumlah, tersedia, lokasi, kode)
    VALUES ('$nama', '$deskripsi', '$jumlah', '$tersedia', '$lokasi', '$kode')";

    mysqli_query ($conn, $query);
    
    echo "<script>alert('Barang berhasil ditambahkan'); window.location='dashboard.php';</script>";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Barang | Inventaris</title>

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

/* ======================= CONTAINER ======================= */
.container {
    width: 400px;
    padding: 35px;
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
}

@keyframes rotateLight {
    0%   { transform: translateX(-50%) rotate(0deg); }
    100% { transform: translateX(-50%) rotate(360deg); }
}

/* ======================= TITLE ======================= */
h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #004aad;
    text-shadow: 0 0 6px rgba(0, 110, 255, 0.25);
}

/* ======================= LABEL ======================= */
label {
    font-weight: 600;
    color: #004aad;
}

/* ======================= INPUT & TEXTAREA ======================= */
input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 13px;
    margin-top: 6px;
    margin-bottom: 20px;
    border-radius: 10px;

    background: #f9fbff;
    border: 1px solid #bcd2f8;
    color: #003a80;
    outline: none;
    transition: 0.25s;
    font-size: 14px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
    min-height: 90px;
}

input:focus,
textarea:focus {
    border-color: #006eff;
    box-shadow: 0 0 12px rgba(0, 110, 255, 0.30);
}

/* ======================= BUTTON SIMPAN ======================= */
button {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;

    background: linear-gradient(135deg, #006eff, #003d82);
    color: #eaf3ff;
    font-weight: 600;
    font-size: 16px;

    cursor: pointer;
    transition: 0.25s;

    box-shadow: 
        0 0 14px rgba(0, 110, 255, 0.35),
        0 6px 20px rgba(0, 0, 0, 0.15);
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 0 22px rgba(0, 110, 255, 0.45),
        0 10px 28px rgba(0, 0, 0, 0.20);
}

/* ======================= LINK KEMBALI ======================= */
a {
    display: block;
    margin-top: 15px;
    text-align: center;

    font-weight: 600;
    color: #0058d4;
    text-decoration: none;

    padding: 10px 0;
    border-radius: 10px;
    transition: 0.25s;
}

a:hover {
    background: rgba(0, 110, 255, 0.12);
}

  </style>
</head>
<body>
  <div class="container">
    <h2>Tambah barang</h2>
    <form method="post" action="">

      <label for="nama">Nama Barang</label><br>
      <input type="text" name="nama" id="nama" required><br><br>

      <label for="kode">Kode Barang</label><br>
      <input type="text" name="kode" id="kode" required><br><br>

      <label for="deskripsi">Deskripsi</label><br>
      <textarea name="deskripsi" id="deskripsi" required></textarea><br><br>

      <label for="jumlah">Jumlah</label><br>
      <input type="number" name="jumlah" id="jumlah" value="1" min="1" required><br><br>

      <label for="tersedia">Tersedia</label><br>
      <input type="number" name="tersedia" id="tersedia" value="1" min="1" required><br><br>

      <label for="lokasi">Lokasi</label><br>
      <input type="text" name="lokasi" id="lokasi" required><br><br>

      <button type="submit" name="submit">Simpan</button>
      <a href="dashboard.php">Kembali</a>

    </form>
  </div>
</body>
</html>