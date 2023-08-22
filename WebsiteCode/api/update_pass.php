<?php
include('connect.php');
if (isset($_POST['id_user']) && isset($_POST['username']) && isset($_POST['password'])) {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);



    $sql = "UPDATE user SET username = '$username' ,  password ='$password' WHERE id_user = '$id_user'";

    $result = mysqli_query($db,$sql);
    if ($result) {
        $data = [
            'success' => true,
            'message' => 'Edit account succesful'
        ];
    } else {
        $data = [
            'success' => false,
            'message' => 'Edit account failed'
        ];
    }
    echo json_encode($data);
} else {
    $data = [
        'succes' => false,
        'message' => 'Please fill all the fields'
    ];

    echo json_encode($data);
}

?>