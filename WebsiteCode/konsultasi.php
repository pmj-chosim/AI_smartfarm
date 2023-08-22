<?php
include('koneksi.php');
require('auth.php');
include('limitKata.php');
$limit = new limit();
if(isset($_POST['update'])) {
	$id = $_POST ['id'];
	

	$query =  "UPDATE `consul` SET `status` = 'selesai' where `consul`.`id_consul` = '$id'";
	$result = mysqli_query($koneksi,$query);
}
// var_dump($rows);

// $ch = curl_init(); 
// curl_setopt($ch, CURLOPT_URL, "https://67ed-114-125-77-60.ap.ngrok.io/edifarm-web/EDIFARM/api/test.php");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// $output = curl_exec($ch); 
// curl_close($ch);      
// $rows = json_decode($output, true);

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Konsultasi</title>
		<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/logo_edifarm.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/logo_edifarm.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/logo_edifarm.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
		<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
	</head>
	<body>
		<?php include 'header.php'; ?>
		
		<div class="right-sidebar">
		<?php include 'rightbar.php'; ?>
		</div>

		<?php include 'sidebar.php'; ?>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="title">
								<h4>Konsultasi</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item">
										<a href="dashboard.php">Dashboard</a>
									</li>
									<li class="breadcrumb-item active" aria-current="page">
										Konsultasi
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="row clearfix">
					<?php 
						$query = "SELECT * FROM consul inner JOIN user on consul.id_user=user.id_user INNER JOIN lahan ON user.id_lahan=lahan.id_lahan where consul.status='belum'";
						$result = mysqli_query($koneksi,$query);
						while($row = mysqli_fetch_array($result)){
							$lahan=$row["nama_lahan"];
							$foto=$row["foto_consul"];
							$isi=$row["isi"];
						?> 
						<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
						<div class="card card-box text-center">
							<div class=" d-flex justify-content-between pb-10">
								<img class="card-img-top" src="api/image_diag/<?=$foto?>" alt=""/>
								
							</div>
							<div class="card-body">
								<h5 class="card-title weight-500 text-left"><?=$lahan?></h5>
								<p class="card-text text-left"><?=$limit->limit_kata($isi,3)?></p>
								<div>
									<a href="#" class="btn btn-primary " data-toggle="modal" data-target="#detail<?= $row['id_consul'];?>" >Detail</a>
									<div class="modal fade" id="detail<?= $row['id_consul'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<form action="konsultasi.php" method="POST">
												<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Detail Konsultasi</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<input hidden type="text" name="id" value="<?= $row['id_consul'];?>" />
													<p class="card-text text-left"><?= $row["tanggal_consul"];?></p>
													<p class="card-text text-left"><?= $row["isi"];?></p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="update" name="update" class="btn btn-primary" onclick="return confirm('Apakah yakin permasalahan sudah teratasi?')">Selesaikan</button>
												</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				};?>
				</div>
			</div>
		</div>
		<?php if(@$_SESSION['sukses']){ ?>
            <script>
                Swal.fire({            
                    icon: 'success',                   
                    title: 'Sukses',    
                    text: 'data berhasil dihapus',                        
                    timer: 3000,                                
                    showConfirmButton: false
                })
            </script>
        <?php unset($_SESSION['sukses']); } ?>
		<!-- js -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
		<script src="src/plugins/sweetalert2/sweet-alert.init.js"></script>
		
	</body>
</html>
