<?php
header("Acces-Control-Allow-Origin: *");
include('connect.php');
if (isset($_POST['id_lahan'])) {
    $id_lahan = $_POST['id_lahan'];
   
   

    $sql = "SELECT * From lahan where id_lahan = $id_lahan";
    $data = mysqli_query($db, $sql);
    $rows = array();

    while ($r = mysqli_fetch_assoc($data)) {
        $rows[] = $r;
    }
     print json_encode($rows);
   
}
?>
