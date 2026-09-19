<?php
require 'config.php';

// Jika sudah login, tendang langsung ke halaman admin
if (isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit();
}

$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    // Cek kecocokan di database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        // LOGIN SUKSES: Simpan data ke session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: admin.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login System - Pindy</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f6f5f0; }
        /* Lebar diubah jadi 450px dan padding jadi 50px */
        .login-box { background: white; padding: 50px; border: 1px solid #1a1a1a; box-shadow: 10px 10px 0px #f29bbd; width: 450px; text-align: center; }
        /* Padding input diubah jadi 15px dan ukuran font diperbesar */
        .login-box input { width: 100%; padding: 15px; margin-bottom: 20px; border: 1px solid #1a1a1a; box-sizing: border-box; font-size: 1.1rem; }
        /* Padding dan ukuran teks tombol diperbesar */
        .btn-pink { width: 100%; padding: 15px; font-size: 1.1rem; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="margin-bottom: 30px; font-family: serif; font-size: 2.5rem;">Login Admin</h2>
        
        <?php if($error): ?>
            <p style='color:red; font-size: 1rem; margin-bottom: 15px;'><?= $error ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn-pink">Masuk</button>
        </form>
    </div>
</body>
</html>