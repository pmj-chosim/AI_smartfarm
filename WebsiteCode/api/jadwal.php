<?php
header("Acces-Control-Allow-Origin: *");
include('connect.php');
if (isset($_POST['id_user'])&& isset($_POST['tanggal_mulai'])) {
    $id_user = $_POST['id_user'];
   $tanggal_mulai= $_POST['tanggal_mulai'];
   

    $sql = "SELECT * From jadwal inner join sesi_tanam on jadwal.id_sesi=sesi_tanam.id_sesi where jadwal.id_user = $id_user and jadwal.tanggal_mulai ='$tanggal_mulai' and sesi_tanam.status_sesi='belum'";
    $data = mysqli_query($db, $sql);
    $rows = array();

    while ($r = mysqli_fetch_assoc($data)) {
        $rows[] = $r;
    }
     print json_encode($rows);
   
}
?>
