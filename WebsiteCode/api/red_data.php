<?php
include('connect.php');
$sql = "SELECT id_user, nama, password, username, tanggal_lahir, jenis_kelamin, alamat, caption, no_hp FROM user WHERE id_user = 'variabel";
$result = mysqli_query($db, $sql);
$array = array();
if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_array($result)){
        $data = array(
            'id_user' => $row['id_user'],
            'nama' => $row['nama'],
            'username' => $row['username'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'no_hp' => $row['no_hp'],
            'alamat' => $row['alamat'],
            'password' => $row['password'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'caption' => $row['caption'],
        );
    array_push($array, $data);
    }
}
echo json_encode($array);
?>