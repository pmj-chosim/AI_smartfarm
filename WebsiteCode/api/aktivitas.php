<?php
include('connect.php');
header("Acces-Control-Allow-Origin: *");
if (isset($_POST['id_jadwal']) && isset($_POST['id_user'])); {
    $id_jadwal = $_POST['id_jadwal'];
    $id_user= $_POST['id_user'];

    $sql = "UPDATE jadwal set status = 'Selesai' where id_jadwal='$id_jadwal' and id_user = '".$id_user."'";

    $result = mysqli_query($db,$sql);
    if ($result) {
        echo json_encode("Succes");
       
    } else {
        echo json_encode("Failed");
    }  
   
}
?>
