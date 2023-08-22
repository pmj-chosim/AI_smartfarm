<?php


include 'connect.php';
if (isset($_POST['idUser'])) {
    $idUser = $_POST['idUser'];
} else return;
if (isset($_POST['nama_siswa'])) {
    $nama = $_POST['nama_siswa'];
} else return;
if (isset($_POST['notelp'])) {
    $notelp = $_POST['notelp'];
} else return;
if (isset($_POST['data'])) {
    $data = $_POST['data'];
} else return;
if (isset($_POST['name'])) {
    $name = $_POST['name'];
} else return;

$path = "image/$name";
$query = "SELECT Foto FROM user WHERE user.id_user = '$idUser'";
$exe = mysqli_query($db, $query);
if($exe){
    $row = mysqli_fetch_array($exe);
    $old_path = $row['Foto'];
    if(file_exists($old_path)){
        unlink($old_path);
    }
}
$query = "UPDATE user SET `nama_siswa` = '$nama',`notelp` = '$notelp',`gambar` = '$path' WHERE `data_siswa`.`NIS` = '$NIS'";
// $query = "UPDATE `data_siswa` SET `gambar` = '$path' WHERE `data_siswa`.`NIS` = '$NIS'";
file_put_contents($path, base64_decode($data));
$arr = [];
$exe = mysqli_query($db, $query);
if ($exe) {
    $arr['success'] = "true";
} else
    $arr['success'] = "false";
echo json_encode($arr); 
?>