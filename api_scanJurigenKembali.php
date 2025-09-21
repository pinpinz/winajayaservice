<?php
header("Content-Type: application/json; charset=UTF-8");
include "connect.php"; 

$input = json_decode(file_get_contents("php://input"), true);

$flag   = $input['flag']   ?? null;
$nomor  = $input['nomor']  ?? null;
$QR     = $input['QR']     ?? null;
$idUser = $input['idUser'] ?? null;

if (!$flag || !$nomor || !$QR || !$idUser) {
    echo json_encode(["status" => "error", "message" => "Input tidak lengkap"]);
    exit;
}

try {
    $stmt = $conn->prepare("EXEC scan_QR_jurigen_kembali ?, ?, ?, ?");
    $stmt->execute([$flag, $nomor, $QR, $idUser]);

   
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data"   => $data
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status"  => "error",
        "message" => $e->getMessage()
    ]);
}
?>
