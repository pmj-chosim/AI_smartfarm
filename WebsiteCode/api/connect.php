<?php 

$db = mysqli_connect('localhost','wstifdi1_edifarm','Polije1234','wstifdi1_edifarm');
// $db = mysqli_connect('localhost','root','','edifarm');
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
 
?>