<?php
require 'config.php';

// 1. PROTEKSI HALAMAN: Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. LOGIKA HAPUS (DELETE)
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM pesan_kontak WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php?status=deleted");
    exit();
}

// 3. LOGIKA UBAH (UPDATE)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];
    
    $stmt = $pdo->prepare("UPDATE pesan_kontak SET nama = ?, email = ?, pesan = ? WHERE id = ?");
    $stmt->execute([$nama, $email, $pesan, $id]);
    header("Location: admin.php?status=updated");
    exit();
}

// 4. AMBIL DATA UNTUK TAMPIL (SELECT)
$stmt = $pdo->query("SELECT * FROM pesan_kontak ORDER BY id ASC");
$data = $stmt->fetchAll();

// 5. LOGIKA UNTUK FORM EDIT
$edit_data = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM pesan_kontak WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $edit_data = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Pindy Putri</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container { padding: 40px; max-width: 1200px; margin: auto; }
        .form-section { background: #fff; padding: 30px; border: 1px solid #1a1a1a; margin-bottom: 30px; box-shadow: 5px 5px 0px #f29bbd; }
        .badge-status { padding: 15px; background: #f29bbd; margin-bottom: 20px; border: 1px solid #1a1a1a; font-weight: bold; text-align: center; }
        .action-links a { margin-right: 15px; text-decoration: none; color: #1a1a1a; font-weight: bold; font-size: 0.9rem; letter-spacing: 1px; }
        .action-links a:hover { color: #f29bbd; }
        .btn-pink { background-color: #f29bbd; border: 1px solid #1a1a1a; padding: 10px 20px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; border-bottom: 1px solid #1a1a1a; background-color: #f6f5f0;">
    <span style="font-family: 'Qwitcher Grypen', cursive; font-size: 2.5rem; color: #f29bbd;">Admin Panel</span>
    <nav>
        <ul style="list-style: none; display: flex; gap: 30px; margin: 0; align-items: center;">
            <li style="font-weight: 600;">Halo, <?= $_SESSION['username']; ?></li>
            <li><a href="logout.php" style="color: #cc0000; font-weight: bold; text-decoration: none; letter-spacing: 1px;">LOGOUT</a></li>
        </ul>
    </nav>
</header>

<div class="admin-container">
    <h2 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 30px;">Manajemen Pesan Kontak</h2>

    <?php if(isset($_GET['status'])): ?>
        <div class="badge-status">
            <?= $_GET['status'] == 'deleted' ? 'Pesan berhasil dihapus!' : 'Pesan berhasil diperbarui!' ?>
        </div>
    <?php endif; ?>

    <?php if($edit_data): ?>
    <div class="form-section">
        <h3 style="font-family: 'Playfair Display', serif; margin-bottom: 20px;">Ubah Data #<?= $edit_data['id'] ?></h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <div class="form-group">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nama Pengirim</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($edit_data['nama']) ?>" required style="background-color: #f6f5f0; width: 100%; padding: 10px; border: 1px solid #ccc; box-sizing: border-box;">
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($edit_data['email']) ?>" required style="background-color: #f6f5f0; width: 100%; padding: 10px; border: 1px solid #ccc; box-sizing: border-box;">
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Isi Pesan</label>
                <textarea name="pesan" rows="4" required style="background-color: #f6f5f0; width: 100%; padding: 10px; border: 1px solid #ccc; box-sizing: border-box;"><?= htmlspecialchars($edit_data['pesan']) ?></textarea>
            </div>
            <div style="margin-top: 20px;">
                <button type="submit" name="update" class="btn-pink">Simpan Perubahan</button>
                <a href="admin.php" style="margin-left: 20px; color: #555; text-decoration: underline; font-weight: 600;">Batal Edit</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <table style="background-color: #fff; width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="border-bottom: 2px solid #1a1a1a; text-align: left;">
                <th style="padding: 15px 10px; width: 5%;">ID</th>
                <th style="padding: 15px 10px; width: 20%;">Nama</th>
                <th style="padding: 15px 10px; width: 20%;">Email</th>
                <th style="padding: 15px 10px; width: 40%;">Isi Pesan Lengkap</th>
                <th style="padding: 15px 10px; width: 15%; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($data) > 0): ?>
                <?php foreach($data as $row): ?>
                <tr style="border-bottom: 1px solid #eee; vertical-align: top;">
                    <td style="padding: 15px 10px;"><?= $row['id'] ?></td>
                    <td style="padding: 15px 10px; font-weight: 600;"><?= htmlspecialchars($row['nama']) ?></td>
                    <td style="padding: 15px 10px;"><?= htmlspecialchars($row['email']) ?></td>
                    <td style="padding: 15px 10px; line-height: 1.5;"><?= nl2br(htmlspecialchars($row['pesan'])) ?></td>
                    <td class="action-links" style="padding: 15px 10px; text-align: center;">
                        <a href="admin.php?action=edit&id=<?= $row['id'] ?>">EDIT</a>
                        <a href="admin.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus pesan?')" style="color: #cc0000;">DELETE</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #888;">Belum ada pesan kontak yang masuk.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>