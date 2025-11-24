<?php
  session_start();
  include ('database.php');

  if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password_input = $_POST['password'];

    $query = mysqli_query ($conn, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc ($query);

    if ($data){

      if (password_verify($password_input, $data ['password'])){
        $_SESSION ['username'] = $data ['username'];
        $_SESSION ['fullname'] = $data ['fullname'];
        $_SESSION ['login'] = true;

        header ("location: dashboard.php");
        exit;

      }else {
        $error = "Password salah";
      }
    }else {
      $error = "Username salah";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Inventaris</title>

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
    width: 380px;
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

/* ======================= INPUT ======================= */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 13px;
    margin-bottom: 18px;
    border-radius: 10px;

    background: #f9fbff;
    border: 1px solid #bcd2f8;
    color: #003a80;
    outline: none;
    transition: 0.25s;
    font-size: 14px;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #006eff;
    box-shadow: 0 0 12px rgba(0, 110, 255, 0.30);
}

/* ======================= BUTTON LOGIN ======================= */
input[type="submit"] {
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

input[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 0 22px rgba(0, 110, 255, 0.45),
        0 10px 28px rgba(0, 0, 0, 0.20);
}

/* ======================= ERROR MESSAGE ======================= */
.error {
    margin-top: 15px;
    padding: 12px 14px;
    border-radius: 10px;

    background: rgba(255, 60, 60, 0.12);
    border-left: 4px solid #ff2a2a;

    color: #b10000;
    font-weight: 600;

    box-shadow: 0 0 12px rgba(255, 40, 40, 0.25);

    animation: shake 0.25s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

  </style>
</head>
<body>
  <div class="container">
    <form method="post" action="">
        <h2>Login</h2>
        <input type="text" name="username" placeholder="Masukkan Username" require><br>
        <input type="password" name="password" placeholder="Masukkan Password" require><br>
        <input type="submit" name="login" value="LOGIN">
    </form>
    <?php if (isset($error))echo "<p style='color:red'>$error</p>"; ?>
</body>
</html>