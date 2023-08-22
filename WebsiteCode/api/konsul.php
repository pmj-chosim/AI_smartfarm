<?php
header("Acces-Control-Allow-Origin: *");
include('connect.php');

   

    $sql = "SELECT * FROM consul  INNER JOIN user ON consul.id_user=user.id_user INNER JOIN lahan ON user.id_lahan = lahan.id_lahan INNER JOIN jenis ON user.id_jenis = jenis.id_jenis WHERE consul.status='belum'";
    $data = mysqli_query($db, $sql);
    $rows = array();

    while ($r = mysqli_fetch_assoc($data)) {
        $rows[] = $r;
    }
     print json_encode($rows);
   

?>
