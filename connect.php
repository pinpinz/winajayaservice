<?php
//$server   = "localhost";
$server   ="heroic.jagoanhosting.com";
$database = "pureeid_Winajaya";
$username = "pureeid_admin";
$password = "Adminpuree12";

//connect db
try {
    $conn = new PDO("sqlsrv:Server=$server;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["status" => "error", "message" => $e->getMessage()]));
}
?>

<?php
$server   = "localhost"; // biasanya cukup 'localhost' di shared hosting
$database = "pureeid_Winajaya";
$username = "pureeid_admin";
$password = "Adminpuree12";

try {
    $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Jika berhasil, bisa tampilkan pesan (opsional)
    // echo json_encode(["status" => "success", "message" => "Connected to MySQL"]);
} catch (PDOException $e) {
    die(json_encode(["status" => "error", "message" => $e->getMessage()]));
}
?>

