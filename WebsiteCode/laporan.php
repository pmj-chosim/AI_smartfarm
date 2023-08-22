<?php
include ('koneksi.php');
if(isset($_POST['submit'])) {
	$username = $_POST['username'];
	$nama = $_POST['nama'];
	$kelamin = $_POST['kelamin'];
	$alamat = $_POST['alamat'];
	$hp = $_POST['hp'];
	$lahir = $_POST['lahir'];
	$email = $_POST['email'];
	$pw = $_POST['pw'];
	$capt = $_POST['capt'];
	$lahan = $_POST['lahan'];

	if($username&&$nama&&$kelamin&&$alamat&&$hp&&$lahir&&$email&&$pw&&$capt&&$lahan){
		$query =  "INSERT INTO user VALUES ('3', '$username','$nama','$kelamin','$alamat', '$hp','$lahir','$email','$pw','$capt', '2', '', '$lahan')";
		$result = mysqli_query($koneksi,$query);
	}
}


if(isset($_POST['submit'])) {
	$nama = $_POST['nama'];
	
	$des = $_POST['des'];
	$tempat = $_POST['tempat'];
	$query =  "INSERT INTO user VALUES ('02', '$nama','$description','$tempat')";
	$result = mysqli_query($koneksi,$query);
	
}if ($idPilihan){
	$judul = $namalhn;
}else{
	$judul = "Pilih Lahan";
};
?>



<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8" />
		<title>Report</title>
		<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/SmartFarm-LOGO 1.png" />
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
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Report Data</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Report
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>

					<!-- Laporan Karyawan Berhenti -->

						<!-- Laporan Konsultasi Mulai -->
				
					<!-- Laporan konsultasi berhenti -->
					
					<!-- Laporan Lahan Mulai -->
					<div class="card-box mb-30">
						<div class="pd-20">
							<h4 class="text-blue h4">Soil Humidity</h4>
						</div>
						<div class="pb-20">
							<table class="data-table-export table stripe hover nowrap">
								<thead>
									<tr>
										<th class="table-plus datatable-nosort">Date</th>
										<th>Pot</th>
										<th>Description</th>
										<th>Result</th>
									</tr>
								</thead>
								<tbody>
								<?php
$queryLahan = mysqli_query($koneksi, "SELECT id_pot, pot_name, description FROM pot");
if (mysqli_num_rows($queryLahan) > 0) {
    while ($dataLahan = mysqli_fetch_array($queryLahan)) {
        $idLahan = $dataLahan['id_pot'];
        $namaLahan = $dataLahan['pot_name'];
        $deskripsi = $dataLahan['description'];

        // Retrieve session data for the current land (id_pot)
        $querySesi = mysqli_query($koneksi, "SELECT date_start, status FROM planting_session WHERE id_pot = '$idLahan'");
        if (mysqli_num_rows($querySesi) > 0) {
            while ($dataSesi = mysqli_fetch_array($querySesi)) {
                $tanggalStart = $dataSesi['date_start'];
                $statusSesi = $dataSesi['status'];
                ?>
                <tr>
                    <td class="table-plus"><?php echo $tanggalStart; ?></td>
                    <td><?php echo $namaLahan; ?></td>
                    <td><?php echo $deskripsi; ?></td>
                </tr>
                <?php
            }
        }
    }
}
?>

								</tbody>
							</table>
						</div>
					</div>
					<!-- Laporan Lahan Berhenti -->

					<div class="card-box mb-30">
						<div class="pd-20">
							<h4 class="text-blue h4">Prediction</h4>
						</div>
						<div class="pb-20">
							<table class="data-table-export table stripe hover nowrap">
								<thead>
									<tr>
									<th class="table-plus datatable-nosort">Date</th>
										<th>Pot</th>
										<th>Description</th>
										<th>Result</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$queryLahan = mysqli_query($koneksi, "SELECT id_pot, pot_name, description
									 FROM pot");
									if (mysqli_num_rows($queryLahan) > 0) {
										while ($dataLahan = mysqli_fetch_array($queryLahan)) {
											$idLahan = $dataLahan['id_pot'];
											$namaLahan = $dataLahan['pot_name'];
											$deskripsi = $dataLahan['description'];
									
											// Retrieve session data for the current land (id_pot)
											$querySesi = mysqli_query($koneksi, "SELECT date_start, status FROM planting_session WHERE id_pot = '$idLahan'");
											if (mysqli_num_rows($querySesi) > 0) {
												while ($dataSesi = mysqli_fetch_array($querySesi)) {
													$tanggalStart = $dataSesi['date_start'];
													$statusSesi = $dataSesi['status'];
													?>
													<tr>
														<td class="table-plus"><?php echo $tanggalStart; ?></td>
														<td><?php echo $namaLahan; ?></td>
														<td><?php echo $deskripsi; ?></td>
													</tr>
													<?php
												}
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

					</div>

					
					<!-- Laporan kegiatan Berhenti -->
		<!-- welcome modal end -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- buttons for Export datatable -->
		<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
		<!-- Datatable Setting js -->
		<script src="vendors/scripts/datatable-setting.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
