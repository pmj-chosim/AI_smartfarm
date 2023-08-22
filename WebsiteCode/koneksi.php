<?php 

$koneksi = mysqli_connect("localhost","root","","edifarm_knu");
// $koneksi = mysqli_connect('localhost','wstifdi1_edifarm','Polije1234','wstifdi1_edifarm');

if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
	// $user  = 'root';
	// $pass = '';
	// try {
	// 	// buat koneksi dengan database
	// 	$koneksi = new PDO('mysql:host=localhost;dbname=edifarm;',$user,$pass);
	// 	// set error mode
	// 	$koneksi->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	// }catch (PDOException $e) {
	// 	// tampilkan pesan kesalahan jika koneksi gagal
	// 	print "Koneksi atau query bermasalah : " . $e->getMessage() . "<br/>";
	// 	die();
	// }
 
?>