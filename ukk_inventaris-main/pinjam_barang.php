<?php
  session_start();
  if (!isset($_SESSION['login'])) {
      header("Location: login.php");
      exit;
  }

  include("database.php");

  $id = $_GET['id'];

  $barang = mysqli_query ($conn, "SELECT * FROM barang WHERE id=$id");
  $data = mysqli_fetch_assoc ($barang);

  if (!$barang) die("Barang tidak ditemukan");

  $error = "";

  if (isset($_POST['submit'])) {
    $peminjam = $_POST['peminjam'];
    $jumlah = $_POST['jumlah'];
    $catatan = $_POST['catatan'];

    if ($peminjam == "" || $jumlah <= 0) {
      $error = "Isi peminjam dan jumlah dengan benar";
    }
    elseif ($jumlah > $data['tersedia']) {
      $error = "Jumlah melebihi stok tersedia";
    }
    else {
      mysqli_query ($conn, "INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan)
      VALUES ($id, '$peminjam', 'pinjam', $jumlah, '$catatan')");

      mysqli_query ($conn, "UPDATE barang SET tersedia = tersedia - $jumlah WHERE id=$id");

      echo "<script>alert('Barang berhasil dipinjam'); window.location='dashboard.php';</script>";

    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pinjam Barang | Inventaris</title>

  <style>
    /* ======================= GLOBAL ======================= */
body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;

    background: radial-gradient(circle at top,
        rgba(255,255,255,1) 0%,
        rgba(230,242,255,0.95) 30%,
        rgba(0,110,255,0.18) 100%
    );

    font-family: "Poppins", sans-serif;
}
/* ======== BUTTON PINJAM ======== */
button {
    width: 100%;
    padding: 12px;
    background: #006eff;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

button:hover {
    background: #0052c4;
    box-shadow: 0 0 14px rgba(0,110,255,0.28);
}

/* ======== LINK KEMBALI ======== */
a {
    display: block;
    margin-top: 14px;
    text-align: center;
    text-decoration: none;
    color: #0058d4;
    font-weight: 600;
    padding: 10px;
    border-radius: 8px;
    transition: 0.2s;
}

a:hover {
    background: #e8f0ff;
}


.stok {
  background: #e9f1ff;
  padding: 8px;
  border-radius: 5px;
  font-size: 14px;
  color: #333;
  text-align: center;
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
        0 0 40px rgba(0,110,255,0.12),
        0 18px 40px rgba(0,0,0,0.10),
        inset 0 0 10px rgba(0,110,255,0.06);
    overflow: hidden;
}

/* ======================= CYBER EDGE BORDER ======================= */
.container::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 18px;
    background: linear-gradient(120deg,
        rgba(0,110,255,0.85),
        rgba(0,110,255,0.25),
        rgba(0,110,255,0.85)
    );
    padding: 2px;
    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
            mask-composite: exclude;
    box-shadow:
        0 0 25px rgba(0,110,255,0.28),
        0 0 45px rgba(0,110,255,0.18);
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
    background: radial-gradient(rgba(0,120,255,0.18), transparent 70%);
    filter: blur(26px);
    animation: rotateLight 14s linear infinite;
    pointer-events: none;
}

@keyframes rotateLight {
    0% { transform: translateX(-50%) rotate(0deg); }
    100% { transform: translateX(-50%) rotate(360deg); }
}

/* ======================= TITLE ======================= */
.container h2 {
    text-align: center;
    margin-bottom: 10px;
    font-weight: 700;
    color: #004aad;
    text-shadow: 0 0 6px rgba(0,110,255,0.22);
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
    box-shadow: 0 0 12px rgba(0,110,255,0.18);
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
    backg

  </style>
</head>
<body>
  <div class="container">
    <h2>pinjam Barang: <?= $data['nama'] ?></h2>
    <p class="stok">Stok Tersedia <b><?= $data['tersedia'] ?></b></p>

    <?php if ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="post">
      <label for="nama">Nama Peminjam</label><br>
      <input type="text" name="peminjam" require><br><br>

      <label for="jumlah">Jumlah</label><br>
      <input type="number" name="jumlah" value="1" min="1" max="<?- $data['tersedia'] ?>" require><br><br>

      <label for="catatan">Catatan</label><br>
      <textarea name="catatan" id="catatan" require></textarea><br><br>

      <button type="submit" name="submit" value="Pinjam">Pinjam</button>
      <a href="dashboard.php">Kembali</a>

    </form>
  </div>
</body>
</html>