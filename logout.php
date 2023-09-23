<?php 
// File         : logout.php
// Deskripsi    : Untuk logout (menghapus session yang dibuat saat login)

session_start();
if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
    session_destroy();
}
header('Location: login.php');
?>
