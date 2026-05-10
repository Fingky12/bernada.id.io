<?php

session_start();
require_once 'config/koneksi.php';

// ✅ FIX: Kalau sudah login, BLOK semua proses login & register
// User harus logout dulu sebelum bisa login akun lain
if (isset($_SESSION['name'])) {
  $_SESSION['alerts'][] = [
    'type'    => 'error',
    'message' => 'Kamu sudah login! Logout dulu untuk ganti akun.'
  ];
  header('Location: dashboard.php');
  exit();
}

  if (isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
      $_SESSION['alerts'][] = [
        'type' => 'error',
        'message' => 'Email Is Already Registered!'
      ];
      $_SESSION['active_form'] = 'register';
    } else {
      $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
      $_SESSION['alerts'][] = [
        'type' => 'success',
        'message' => 'Registration Successful'
      ];
      $_SESSION['active_form'] = 'login';
    }

    header('Location: dashboard.php?login=success');
    exit();
  }

  if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    $user = $result->num_rows > 0 ? $result->fetch_assoc() : null;

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['name'] = $user['name'];
    $_SESSION['alerts'][] = [
        'type' => 'success',
        'message' => 'Login Successful'
    ];
    header('Location: dashboard.php');
    exit();
  }
  else {
    $_SESSION['alerts'][] = [
        'type' => 'error',
        'message' => 'Incorret email or password!'
    ];
$_SESSION['active_form'] = 'login';
  }
  header('Location: dashboard.php');
  exit();
  }




  
?>