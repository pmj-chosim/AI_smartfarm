<?php
header("Acces-Control-Allow-Origin: *");
include('connect.php');
if(!$db)
{
	echo "Database connection failed";
}
    $sql = "SELECT * FROM user WHERE id_user = 1 ";
    $data = mysqli_query($db, $sql);
    $rows = array();

    while ($r = mysqli_fetch_assoc($data)) {
        $rows[] = $r;
    }
     print json_encode($rows);
?>
