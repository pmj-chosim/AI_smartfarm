<?php
include('connect.php');
if (isset($_POST['isi']) && isset ($_POST['tanggal_consul']) && isset ($_POST['id_user'])) {
    $isi = $_POST['isi'];
    $tanggal_consul = $_POST['tanggal_consul'];
    $id_user = $_POST['id_user'];
    $image = $_FILES['image']['name'];

  
   $imagePath = 'image_diag/'.$image;
   $tmp_name = $_FILES['image']['tmp_name'];

   move_uploaded_file($tmp_name, $imagePath);


    $sql = 
    
    "INSERT INTO consul(isi, tanggal_consul, status,id_user, foto_consul) VALUES ('{$isi}','{$tanggal_consul}','belum','{$id_user}', '{$image}')";

    $result = mysqli_query($db,$sql);
    if ($result) {
        echo json_encode("Succes");
       
    } else {
        echo json_encode("Failed");
    }  
   
}

?>