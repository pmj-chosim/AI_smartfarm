<?php
header("Acces-Control-Allow-Origin: *");
include('connect.php');
if (isset($_POST['id_user'])) {
    $id_user = $_POST['id_user'];
   
   

    $sql = "SELECT Round(COUNT(*) / (SELECT COUNT(*) FROM jadwal) * 100) AS prosentase FROM jadwal inner join sesi_tanam on jadwal.id_sesi=sesi_tanam.id_sesi WHERE jadwal.status = 'selesai' and jadwal.id_user = $id_user AND sesi_tanam.status_sesi='belum'";
    $data = mysqli_query($db, $sql);
    $rows = array();

    while ($r = mysqli_fetch_assoc($data)) {
        $rows[] = $r;
    }
     print json_encode($rows);
   
}
?>
