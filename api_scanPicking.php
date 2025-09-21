<?php
header("Content-Type: application/json; charset=UTF-8");
include "connect.php"; // koneksi via PDO

$input = json_decode(file_get_contents("php://input"), true);

$flag     = $input['flag']     ?? null;
$nomor    = $input['nomor']    ?? null;
$kodeCust = $input['kodeCust'] ?? null;
$sitePlan = $input['sitePlan'] ?? null;
$QR       = $input['QR']       ?? null;
$bobot    = $input['bobot']    ?? null;
$idUser   = $input['idUser']   ?? null;

//validasi
if (!$flag || !$nomor || !$kodeCust || !$sitePlan || !$QR || !$bobot || !$idUser) {
    echo json_encode(["status" => "error", "message" => "Input tidak lengkap"]);
    exit;
}

try {
    $stmt = $conn->prepare("EXEC scan_QR_picking ?, ?, ?, ?, ?, ?, ?");
    $stmt->execute([$flag, $nomor, $kodeCust, $sitePlan, $QR, $bobot, $idUser]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data"   => $data
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
