<?php
header("Acces-Control-Allow-Origin: *");
include('connect.php');
if (isset($_POST['id_lahan']) && isset($_POST['id_user'])) {
    $id_lahan = $_POST['id_lahan'];
    $id_user = $_POST['id_user'];

    $sql = "SELECT * From jadwal inner join sesi_tanam on jadwal.id_sesi=sesi_tanam.id_sesi where jadwal.id_lahan = $id_lahan AND jadwal.status = 'Selesai' AND jadwal.kegiatan = 'irigasi' AND jadwal.id_user = $id_user and sesi_tanam.status_sesi='belum'";
    $data = mysqli_query($db, $sql);
    $rows = array();

    while ($r = mysqli_fetch_assoc($data)) {
        $rows[] = $r;
    }
     print json_encode($rows);
   
}
?>
