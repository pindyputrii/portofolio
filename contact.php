<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
   
    $nama = htmlspecialchars(trim($_POST["nama"] ?? ''));
    $email = htmlspecialchars(trim($_POST["email"] ?? ''));
    $pesan = htmlspecialchars(trim($_POST["pesan"] ?? ''));


    if (empty($nama) || empty($email) || empty($pesan)) {
        echo json_encode(["success" => false, "message" => "Semua kolom wajib diisi."]);
        exit;
    }

    $host = '127.0.0.1;port=8889';
    $dbname = 'db_portofolio'; 
    $user_db = 'root';
    $pass_db = 'root'; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user_db, $pass_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO pesan_kontak (nama, email, pesan) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $email, $pesan]);

        echo json_encode([
            "success" => true,
            "message" => "Thank you for reaching out, pesan Anda telah tersimpan.",
            "data" => [
                "name" => $nama,
                "email" => $email,
                "inquiry_type" => "data",
                "message" => $pesan
            ]
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error database: " . $e->getMessage()]);
    }
} else {

    echo json_encode(["success" => false, "message" => "Metode tidak diizinkan. Gunakan POST."]);
}
?>