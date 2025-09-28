<?php
header("Content-Type: application/json; charset=UTF-8");
include "connect.php"; // pastikan $conn (PDO) sudah terhubung

// Ambil request JSON
$input = json_decode(file_get_contents("php://input"), true);

$username = isset($input['username']) ? trim($input['username']) : null;
$password = isset($input['password']) ? $input['password']       : null;

if (!$username || !$password) {
    echo json_encode(["status" => "error", "message" => "Input tidak lengkap. Dibutuhkan: username dan password."]);
    exit;
}

try {
    // Password di-hash MD5 (sesuai database sekarang)
    $password_md5 = md5($password);

    // Sesuaikan nama tabel dan kolom
    $sql = "SELECT idUser, namaPegawai, username, levelUser, isAktif, lastAktif
            FROM users
            WHERE username = :username
              AND password = :password
              AND isAktif = 1
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password_md5);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update lastAktif
        $update = $conn->prepare("UPDATE users SET lastAktif = NOW() WHERE idUser = :idUser");
        $update->bindParam(':idUser', $user['idUser']);
        $update->execute();

        echo json_encode([
            "status" => "success",
            "message" => "Login berhasil",
            "user" => $user
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Username/password salah atau akun tidak aktif"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan pada server"
    ]);
    // Di produksi sebaiknya error detail disimpan di log, bukan dikirim ke client
}
?>
