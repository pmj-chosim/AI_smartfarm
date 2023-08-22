<?php 
include('koneksi.php');
require('limitKata.php');
$limit = new limit();

$sukses = "";
$error = "";

if (isset($_POST['submit'])) {
    $nama_padi = $_POST['namaPadi'];
    $description = $_POST['des'];
	$duration = $_POST['durations'];

    $query = "INSERT INTO `variety` (`name_variety`, `description`, `plant_duration`) VALUES ('$nama_padi', '$description', '$duration')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: padi.php"); // Redirect to prevent form resubmission
        exit();
    }
}

if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM variety WHERE id_variety = $id";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: padi.php"); // Redirect after deleting
        exit();
    }
}
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Variety</title>
		<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/SmartFarm-LOGO 1.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
		<link rel="stylesheet" type="text/css" href="src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.css"/>
		
		<script>
			function copyForm(){
				$("#asli")
				.clone()
				.appendTo($("#dinamis"))
			};
			function copyForm1(){
				$("#aslipupuk")
				.clone()
				.appendTo($("#dinamispupuk"))
			};
			function copyForm2(){
				$("#aslipestisida")
				.clone()
				.appendTo($("#dinamispestisida"))
			}
		</script>
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
									<h4>Variety</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Variety
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<?php 
						$query = "SELECT * FROM variety";
						$result = mysqli_query($koneksi,$query);
						while($row = mysqli_fetch_array($result)){
							$id=$row["name_variety"];
							$des=$row["description"];
						?>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
							<div class="card card-box text-center">
								<img
									class="card-img-top"
									src="vendors/images/ciherang.jpg"
									alt="Card image cap"
								/>
								<div class="card-body">
									<h5 class="card-title weight-500 text-left"><?= $row["id_variety"];?></h5>
									<p class="card-text text-left"><?= $limit->limit_kata($des,5) ;?></p>
									<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#detailPadi<?=$id?>">Detail</a>
									<div class="modal fade" id="detailPadi<?= $id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<form action="padi.php" method="POST">
												<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Detail Padi</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<input hidden type="text" name="id" value="<?= $id?>" />
													<p class="card-text text-left"><?=$des ?></p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
													<button type="hapus" name="hapus" class="btn btn-primary" onclick="return confirm('Yakin ingin hapus data?')">Hapus</button>
												</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>		
		<div class="add-modal-kar">
			<button href="#" class="welcome-modal-btn" data-toggle="modal" data-target="#exampleModal">
			(+) add
		</button></div>
		
				<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form action="padi.php" method="POST">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Add New Variety</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
					<ul class="nav nav-tabs customtab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#detail" role="tab">Variety</a>
							</li>
						</ul>
						<div class="tab-content">
							<!-- Setting Tab start -->
							<div class="tab-pane fade show active" id="detail" role="tabpanel">
								<div class="profile-setting">
									<ul class="profile-edit-list row">
										<li class="weight-100 col-md-12">
											<div class="form-group">
												<label>Plant Variety</label>
												<input class="form-control form-control-lg" type="text" name="namaPadi" required/>
											</div>
											<div class="form-group">
												<label>Plant Duration</label>
												<input class="form-control form-control-lg" type="text" name="durations" onkeypress="return inputAngka(event)" required/>
											</div>	
											<div class="form-group">
												<label>Description</label>
												<input class="form-control form-control-lg" type="text" name="des" required/>
											
											</div>
										</li>
									</ul>
								</div>
							</div>
							<!-- Setting Tab End -->
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
						<button type="submit" name="submit" class="btn btn-primary">Simpan</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script>
		<script src="vendors/scripts/advanced-components.js"></script>
		<script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
		<script src="src/plugins/sweetalert2/sweet-alert.init.js"></script> 
		<script>
		

			function inputAngka(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}
		</script>
			<script>
    function copyForm(){
        $("#dinamisContainer").empty(); // Bersihkan konten sebelumnya
        $("#asli").clone().appendTo($("#dinamisContainer"));
    }

    function copyForm1(){
        $("#dinamisContainer").empty(); // Bersihkan konten sebelumnya
        $("#aslipupuk").clone().appendTo($("#dinamisContainer"));
    }

    function copyForm2(){
        $("#dinamisContainer").empty(); // Bersihkan konten sebelumnya
        $("#aslipestisida").clone().appendTo($("#dinamisContainer"));
    }
</script>
	</body>
</html>