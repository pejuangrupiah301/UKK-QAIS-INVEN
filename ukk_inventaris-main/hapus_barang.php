<?php
  session_start();

  if (!isset($_SESSION['login'])){
    header ('location: login.php');
    exit;
  }

  include ('database.php');

  $id = $_GET['id'];

  mysqli_query ($conn, "DELETE FROM barang WHERE id=$id");

  echo "<script>alert('Data berhasil dihapus'); window.location='dashboard.php';</script>";
  
?>