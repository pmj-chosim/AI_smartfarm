<?php
session_start();
include('koneksi.php');
$idUser = $_SESSION["idUser"];
if(isset($_POST['update'])) {
	$id = $idUser;
	$user = $_POST['username'];
	$nama = $_POST['nama'];
	$jeniskel = $_POST['jeniskel'];
	$alamat = $_POST['alamat'];
	$no_hp = $_POST['no_hp'];
	$ttl = $_POST['ttl'];
	$email = $_POST['email'];
	$capt = $_POST['capt'];
	$name = $_FILES['foto']['name'];
	$data= $_FILES['foto']["tmp_name"];
	$path = "api/image/$name";

	if($name){
		$query1 = "SELECT Foto FROM user WHERE user.id_user = '$idUser'";
		$exe = mysqli_query($koneksi, $query1);
		if($exe){
			$row = mysqli_fetch_array($exe);
			$old_path = $row['Foto'];
			if(file_exists($old_path)){
				unlink($old_path);
			}
		}
		$query =  "UPDATE `user` SET `username` = '$user', `nama` = '$nama',  `alamat` = '$alamat', `no_hp` = '$no_hp', `tanggal_lahir` = '$ttl', `email` = '$email', `caption` = '$capt', `Foto` = '$path' WHERE `user`.`id_user` = '$id';";
		// file_put_contents($path, base64_decode($data));
		if (move_uploaded_file($data, $path)) {
		} else {
			$msg = "Failed to upload image";
		}
		$result = mysqli_query($koneksi,$query);
		$_SESSION["fotoUser"] = $path;
	}else{
		$query =  "UPDATE `user` SET `username` = '$user', `nama` = '$nama',  `alamat` = '$alamat', `no_hp` = '$no_hp', `tanggal_lahir` = '$ttl', `email` = '$email', `caption` = '$capt' WHERE `user`.`id_user` = '$id';";
		$result = mysqli_query($koneksi,$query);
	};
	$_SESSION["namaUser"] = $nama;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Edifarm</title>
		<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/logo_edifarm.png"/>
		<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/logo_edifarm.png"/>
		<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/logo_edifarm.png"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css"/>
		<link rel="stylesheet" type="text/css" href="src/plugins/cropperjs/dist/cropper.css"/>
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
									<h4>Profile</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Profile
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
							<?php 
								$query = mysqli_query($koneksi,"SELECT * FROM user where id_user = '$idUser'");
								if(mysqli_num_rows($query)>0){
							
									while($data = mysqli_fetch_array($query)){
										$id=$data["id_user"];
										$jeniskel=$data["jenis_kelamin"];
										 $user=$data["username"];
										 $nama=$data["nama"];
										 $alamat=$data["alamat"];
										 $no_hp=$data["no_hp"];
										 $ttl=$data["tanggal_lahir"];
										 $email=$data["email"];
										 $capt=$data["caption"];
									}}
								?>		
								<div class="profile-photo">
									
									<!-- <div class="fa fa-pencil "> -->
										<!-- <input class="" type="file" name="foto" id="foto"> -->
										<!-- <i class="fa fa-pencil"></i> -->
										<!-- <input type="file" name="foto" id="foto"> -->
									<!-- </div> -->
									<img src="<?php echo $_SESSION["fotoUser"];?>" alt="" class="avatar-photo"/>	
									
									
								</div>
								<h5 class="text-center h5 mb-0"><?php echo $nama;?></h5>
								<p class="text-center text-muted font-14">
									<?php echo $user?>
								</p>
								<div class="profile-info">
									<h5 class="mb-20 h5 text-blue">Informasi Kontak</h5>
									<ul>
										<li>
											<span>Email Address:</span>
											<?php echo $email?>
										</li>
										<li>
											<span>Jenis Kelamin:</span>
											<?php echo $jeniskel?>
										</li>
										<li>
											<span>Tanggal Lahir:</span>
											<?php echo $ttl?>
										</li>
										<li>
											<span>No. Handphone:</span>
											<?php echo $no_hp?>
										</li>
										<li>
											
											<span>Alamat:</span>
											<?php echo $alamat?><br />
										</li>
										<li>
											<span>Caption</span>
											<?php echo $capt?><br />
									</ul>
								</div>
								<div class="profile-social">
									<h5 class="mb-20 h5 text-blue"></h5>
									<ul class="clearfix">
										<li>
										</li>
										<li>

										</li>
										<li>
											
										</li>
										<li>
											
										</li>
										<li>
											
										</li>
										<li>
											
										</li>
										<li>
											
										</li>
										<li>
											
										</li>
										<li>
											
										</li>
										<li>
											
										</li>
									</ul>
								</div>
								<div class="profile-skills">
									<h5 class="mb-20 h5 text-blue"></h5>
									<h6 class="mb-5 font-14"></h6>
									<div class="" style="">
									</div>
									<h6 class="mb-5 font-14"></h6>
									<div class="" style="">
									</div>
									<h6 class="mb-5 font-14"></h6>
									<div class="" style="">
									</div>
									<h6 class="mb-5 font-14"></h6>
									<div class="" style="">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
							<div class="card-box height-100-p overflow-hidden">
								<div class="profile-tab height-100-p">
									<div class="tab height-100-p">
										<ul class="nav nav-tabs customtab" role="tablist">
											
											<li class="nav-item">
												<a
													class="nav-link"
													data-toggle="tab"
													href="#setting"
													role="tab"
													>Edit Profil</a
												>
											</li>
										</ul>
									
											<!-- Setting Tab start -->
											<div
												class="tab-pane fade  "
												id="setting"
												role="tabpanel"
											>
												<div class="profile-setting">
													<form action="profile.php" method="POST"  enctype="multipart/form-data">
														<ul class="profile-edit-list row">
															<li class="weight-500 col-md-6">
																
																<div class="form-group">
																	<label>Ganti Foto Profil</label>
																	<input class="" type="file" name="foto" id="foto">
																	</div>

																<div class="form-group">
																	<label>Nama Lengkap</label>
																	<input
																		class="form-control form-control-lg"
																		type="text"
																		value="<?php echo $nama?>"
																		name = "nama"
																	/>
																</div>
																<div class="form-group">
																	<label>Username</label>
																	<input
																		class="form-control form-control-lg"
																		type="text"
																		value="<?php echo $user?>"
																		name = "username"
																	/>
																</div>
																<div class="form-group">
																	<label>Email</label>
																	<input
																		class="form-control form-control-lg"
																		type="email"
																		value="<?php echo $email?>"
																		name = "email"
																	/>
																</div>
																<div class="form-group">
																	<label>Tanggal Lahir</label>
																	<input
																		class="form-control form-control-lg "
																		type="date"
																		value="<?php echo $ttl?>"
																		name = "ttl"
																	/>
																</div>
																<div class="form-group">
																	<label>Jenis Kelamin</label>
																	<div class="d-flex">
																		<div
																			class="custom-control custom-radio mb-5 mr-20"
																		>
																			<input
																				type="radio"
																				id="customRadio4"
																				name = "jeniskel"
																				class="custom-control-input"
																				value="Laki-laki"
																				<?php if($jeniskel=="Laki-laki") echo "checked"?>
																				disabled
																			/>
																			<label
																				class="custom-control-label weight-400"
																				for="customRadio4"
																				>Laki-laki</label
																			>
																		</div>
																		<div
																			class="custom-control custom-radio mb-5"
																		>
																			<input
																				type="radio"
																				id="customRadio5"
																				name = "jeniskel"
																				class="custom-control-input"
																				value = "Perempuan"
																				<?php if($jeniskel=="Perempuan") echo "checked"?>
																				disabled
																			/>
																			<label
																				class="custom-control-label weight-400"
																				for="customRadio5"
																				>Perempuan</label
																			>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label>No. Handphone</label>
																	<input
																		class="form-control form-control-lg"
																		type="text"
																		value="<?php echo $no_hp?>"
																		name = "no_hp"
																	/>
																</div>
																<div class="form-group">
																	<label>Alamat</label>
																	<textarea class="form-control" value="" name = "alamat"><?php echo $alamat?></textarea>
																	</div>
																
																	
																<div class="form-group">
																	<label>Caption</label>
																	<textarea class="form-control" value="" name = "capt"><?php echo $capt?></textarea>
																	</div>
																
																

																<div class="form-group mb-0">
																	<input
																		type="submit"
																		
																		class="btn btn-primary"
																		name="update"
																		value="Perbarui"
																	/>
																</div>
														</form>
		</button>
		<!-- welcome modal end -->
		<!-- js -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/cropperjs/dist/cropper.js"></script>
	</body>
</html>
