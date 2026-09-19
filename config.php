<?php
// config.php
session_start(); // Session WAJIB dimulai di sini agar semua file terhubung dengan status login

$host = '127.0.0.1';
$port = '8889'; // Sesuaikan port MAMP
$dbname = 'db_portofolio';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>