<?php
include('connect.php'); // Pastikan Anda memasukkan detail koneksi ke basis data di sini

$soilHumidity1 = $_POST['soilHumidity1'];
$soilHumidity2 = $_POST['soilHumidity2'];

$sql = "INSERT INTO soilHumidity (soilHumidity1, soilHumidity2) VALUES ('$soilHumidity1', '$soilHumidity2')";

if (mysqli_query($db, $sql)) {
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "message" => "Data soilHumidity berhasil ditambahkan",
        "data" => null
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Gagal menambahkan data soilHumidity",
        "data" => null
    ]);
}

mysqli_close($db);
?>
