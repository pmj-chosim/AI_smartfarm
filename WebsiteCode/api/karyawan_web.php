<?php
header("Acces-Control-Allow-Origin: *");
include('connect.php');
   

    $sql = "SELECT * FROM user INNER JOIN lahan on user.id_lahan=lahan.id_lahan INNER JOIN jenis ON user.id_jenis = jenis.id_jenis where id_level = '2'";
    $data = mysqli_query($db, $sql);
    $rows = array();

    while ($r = mysqli_fetch_array($data)) {
        $rows[] = $r;
        // print_r($r);
    }
     print json_encode($rows, true);
   
// }
?>
