<?php
header("Content-Type: application/json; charset=UTF-8");
include "connect.php";

// Ambil request JSON
$input = json_decode(file_get_contents("php://input"), true);

$idUser      = $input['idUser']      ?? null;
$namaPegawai = $input['namaPegawai'] ?? null;
$username    = $input['username']    ?? null;
$password    = $input['password']    ?? null;
$levelUser   = $input['levelUser']   ?? null;
$userCreate  = $input['userCreate']  ?? null; // siapa yang buat user ini (optional)

if (!$idUser || !$namaPegawai || !$username || !$password || !$levelUser) {
    echo json_encode(["status" => "error", "message" => "Input tidak lengkap"]);
    exit;
}

try {
    // Hash password pakai MD5 (sesuai DB sekarang, walau tidak disarankan di produksi)
    $password_md5 = md5($password);

    $sql = "INSERT INTO users (idUser, namaPegawai, username, password, levelUser, lastAktif, isAktif, tglCreate, userCreate)
            VALUES (:idUser, :namaPegawai, :username, :password, :levelUser, NULL, 1, NOW(), :userCreate)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idUser', $idUser);
    $stmt->bindParam(':namaPegawai', $namaPegawai);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password_md5);
    $stmt->bindParam(':levelUser', $levelUser);
    $stmt->bindParam(':userCreate', $userCreate);

    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "message" => "Registrasi berhasil",
        "user" => [
            "idUser" => $idUser,
            "namaPegawai" => $namaPegawai,
            "username" => $username,
            "levelUser" => $levelUser,
            "isAktif" => 1
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
