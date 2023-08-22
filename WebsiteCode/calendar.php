<?php 
require('koneksi.php');

if(isset($_GET['idlhn'])){
	$idPilihan = $_GET['idlhn'];
}else{
	$idPilihan = null;
};

//Tambah preset jadwal
if(isset($_POST['tambahJadwal'])) {
	$lahan = $_POST['lahanPilih'];
	$jenis = $_POST['padiPilih'];
	$startTgl = $_POST['tanggalMulai'];
	

		$endTgl = date('Y-m-d');
		
		$query =  "INSERT INTO `planting_session` (`date_start`, `date_finish`, `id_variety`, `id_pot`) VALUES ('$startTgl','$endTgl','$jenis', '$lahan')";
		$result = mysqli_query($koneksi, $query);
	
		if ($result) { // Pastikan query INSERT berhasil sebelum melanjutkan
			$query3 = "SELECT id_session FROM planting_session ORDER BY id_session DESC";
			$result3 = mysqli_query($koneksi, $query3);
			$row1 = mysqli_fetch_assoc($result3);
			$idbaru = $row1["id_session"];
	
			$query4 = mysqli_query($koneksi, "SELECT * FROM activities where id_variety ='$jenis'");
			if (mysqli_num_rows($query4) > 0) {
				while ($data = mysqli_fetch_array($query4)) {
			$activities =$data["activities"];
			$hst1=$data["hst"];
			$hst2=$hst1 - 1;
			$tgl = date('Y-m-d', strtotime("+$hst2 days", strtotime($startTgl)));
			$tgl2 = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
			$query5 =  "INSERT INTO `schedule` (`activities`, `date_start`, `date_start`, `status`, `id_session`, `id_pot`) VALUES ( '$activities','$tgl','$tgl2', 'belum', '$idbaru', '$lahan')";
			$result1 = mysqli_query($koneksi,$query5); 
			
		} }

		$updateQuery = "UPDATE pot SET status = 'Filled' WHERE id_pot = '$lahan'";
        mysqli_query($koneksi, $updateQuery);
	};

}
//tambah jadwal satuan
if(isset($_POST['tambah'])) {
	// Define query7 to fetch the latest id_sesi
	$query7 = "SELECT id_session FROM planting_session ORDER BY id_session DESC LIMIT 1";
    $result7 = mysqli_query($koneksi, $query7);
    
    if ($result7) {
        $data5 = mysqli_fetch_array($result7);
        $idlama = $data5["id_session"];

        $activities = $_POST['activities'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $lahan = $_POST['lahan'];
        
        $query = "INSERT INTO `schedule` (`activities`, `date_start`, `date_finish`, `status`, `id_session`, `id_pot`) VALUES ('$activities','$start','$end', 'yet', '$idlama', '$lahan')";
        $result = mysqli_query($koneksi, $query);} 
}
if(isset($_POST['Update'])) {
	$id2 = $_POST['idJadwal'];

	$activities = $_POST['title'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$query9 =  "UPDATE schedule set activities = '$activities', date_start= '$start', date_finish = '$end' WHERE `schedule`.`id_schedule` = $id2";
	$result = mysqli_query($koneksi,$query9);
}
//hapus jadwal
if(isset($_POST['hapus'])) {
	$id = $_POST['idJadwal'];
	$query =  "DELETE FROM `schedule` WHERE id_schedule = $id";
	$result = mysqli_query($koneksi,$query); 

}
//drag n drop
if(isset($_POST['id'])) {
	$id2 = $_POST['id'];
	$activities = $_POST['title'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$query =  "UPDATE schedule set activities = '$activities', date_start= '$start', date_finish = '$end' WHERE `jadwal`.`id_schedule` = '$id2'";
	$result = mysqli_query($koneksi,$query);
}


$sql5 = "SELECT * FROM schedule right JOIN pot ON schedule.id_pot=pot.id_pot where pot.id_pot = '$idPilihan'";
$result8 = mysqli_query($koneksi,$sql5);
$dataArr = array();
if(mysqli_num_rows($result8)>0){ 
while($data = mysqli_fetch_array($result8)){
	$namalhn = $data['pot_name'];
	$dataArr[] = array(
		'id' => $data['id_schedule'],
		'title' => $data['activities'],
		'start' => $data['date_start'],
		'end' => $data['date_finish'],
	);
}};
if ($idPilihan){
	$judul = $namalhn;
} else{
	$judul = "Choose Pot";
};
 ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Calendar</title>
		<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/SmartFarm-LOGO 1.png" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="src/plugins/fullcalendar/fullcalendar.css"
		/>
		<link rel="stylesheet" type="text/css" href="src/plugins/jquery-steps/jquery.steps.css" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
	</head>
	<body>

		<?php include 'header.php'; ?>
		
		<div class="right-sidebar">
		<?php include 'rightbar.php'; ?>
		</div>

		<?php include 'sidebar.php'; ?>
		<div class="mobile-menu-overlay"></div>

		<div class="mobile-menu-overlay"></div>
		<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
				<div class="page-header">
						<div class="row">
							<div class="col-md-6 col-sm-12">
								<div class="title">
									<h4>Schedule</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Schedule
										</li>
									</ol>
								</nav>
							</div>
							<div class="col-md-6 col-sm-12 text-right">
								<div class="dropdown">
									<a
										class="btn btn-primary dropdown-toggle"
										href="#"
										role="button"
										data-toggle="dropdown"
									>
									<?php echo $judul ?>
									</a>
									<ul class="dropdown-menu dropdown-menu-right">
									<?php 
									$query1 = mysqli_query($koneksi,"SELECT * FROM pot where status='Filled'");
									if(mysqli_num_rows($query1)>0){ 
									?>
									<?php
										while($data1 = mysqli_fetch_array($query1)){
											$namalahan1=$data1["pot_name"];
											$idlahan1=$data1["id_pot"];
									?>		
										<li><a class="dropdown-item" href="calendar.php?idlhn=<?=$idlahan1;?>"><?php echo $namalahan1 ?></a></li>
									<?php  
										} 
									} 
									?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="pd-20 card-box mb-30">
						<div class="calendar-wrap">
							<div id="calendar"></div>
						</div>
						<!-- calendar modal -->
						<div
							id="modal-view-event"
							class="modal modal-top fade calendar-modal"
						>
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<form action="calendar.php?idlhn=<?=$idPilihan;?>" method="POST">
										<div class="modal-body">
											<h4 class="text-blue h4 mb-10">Detail Schedule</h4>
										
											<div class="form-group">
												
												<input type="hidden" class="idJadwal form-control" name="idJadwal" id="user" />
											</div>
											<div class="form-group">
												<label>activities</label>
												<input type="text" class="title form-control" name="title" id="title" required/>
											</div>
											<div class="form-group">
												<label>Start Date</label>
												<input type="datetime" class="tanggalstr form-control datetime" name="start" id="activities" value="" />
											</div>
											<div class="form-group">
												<label>Finish</label>
												<input type="datetime" class="tanggalend form-control datetime" name="end" id="activities" value="" />
											</div>
											<div class="form-group">
												<input hidden type="text" class="form-control" readOnly name="lahan" id="user" value="<?php echo $idPilihan ?>" />
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary"  id="tombol_form" name="Update">
												Update
											</button>
											<button
												type="button"
												class="btn btn-primary"
												data-dismiss="modal"
											>
												Cancel
											</button>
											<button type="submit" class="btn btn-primary"  id="tombol_form" name="hapus">
												Delete
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div
							id="modal-view-event-add"
							class="modal modal-top fade calendar-modal"
						>
							<div class="modal-dialog modal-dialog-centered">
								<div class="modal-content">
									<form action="calendar.php?idlhn=<?=$idPilihan;?>" method="POST">
										<div class="modal-body">
											<h4 class="text-blue h4 mb-10">Add New Schedule</h4>
											<input type="hidden" class="form-control" name="idJadwal" id="user" required/>
											<div class="form-group">
												<label>Activities</label>
												<input type="text" class="form-control" name="activities" id="activities" required/>
											</div>
											<div class="form-group">
												<label>Start Date</label>
												<input type="text" class="tanggalstr form-control" readOnly name="start" id="activities" value="" />
											</div>
											<div class="form-group">
												<label>Finish Date</label>
												<input type="text" class="tanggalend form-control" readOnly name="end" id="activities" value="" />
											</div>
											<div class="form-group">
												<label></label>
												<input type="text" class="form-control" name="lahan" hidden id="lahan" value="<?php echo $idPilihan ?>" />
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary"  id="tombol_form" name="tambah">
												Save
											</button>
											<button
												type="button"
												class="btn btn-primary"
												data-dismiss="modal"
											>
												Cancel
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<button 
		href="#"
		class="welcome-modal-btn"
		data-toggle="modal" data-target="#exampleModal"
			>
			(+) Add
		</button>

		<div class="modal fade bs-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">New Schedule</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="calendar.php?idlhn=<?=$idPilihan;?>" method="POST">
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Pot</label>
								<div class="col-sm-12 col-md-10 ">
								<select class="form-control selectpicker" name="lahanPilih" title="Available Pot" required>
									
								<?php 
								$query = mysqli_query($koneksi,"SELECT * FROM pot where status = 'Empty'");
								if(mysqli_num_rows($query)>0){ 
								?>
								<?php
									while($data2 = mysqli_fetch_array($query)){
										$namalahan=$data2["pot_name"];
										$idlahan2=$data2["id_pot"];
								?>		
									<option value="<?php echo $idlahan2 ?>"><?php echo $namalahan ?></option>
									<?php  
									} 
							 	} 
								?>
								</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Variety</label>
								<div class="col-sm-12 col-md-10 ">
								<select class="form-control selectpicker" name="padiPilih" title="Choose Variety" required>
									
								<?php 
								$query2 = mysqli_query($koneksi,"SELECT * FROM variety");
								if(mysqli_num_rows($query2)>0){ 
								?>
								<?php
									while($data1 = mysqli_fetch_array($query2)){
										$namajenis=$data1["name_variety"];
										$idjenis=$data1["id_variety"];
								?>		
									<option value="<?php echo $idjenis ?>"><?php echo $namajenis ?></option>
									<?php  
									} 
							 	} 
								?>
								</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label">Start Date</label>
								<div class="col-sm-12 col-md-10">
									<input
										class="form-control date"
										placeholder="Pilih tanggal lahir"
										type="date"
										name="tanggalMulai"
										required
									/>
								</div>
							</div>
							</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal" alt="add-modal-kar" >Cancel</button>
									<input type="submit" name="tambahJadwal" class="btn btn-primary" value="Save" id="sa-success">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<button
			type="button"
			id="success-modal-btn"
			hidden
			data-toggle="modal"
			data-target="#success-modal"
			data-backdrop="static"
		>
			Launch modal
		</button>
		<div
			class="modal fade"
			id="success-modal"
			tabindex="-1"
			role="dialog"
			aria-labelledby="exampleModalCenterTitle"
			aria-hidden="true"
		>
			<div
				class="modal-dialog modal-dialog-centered max-width-400"
				role="document"
			>
				<div class="modal-content">
					<div class="modal-body text-center font-18">
						<h3 class="mb-20">Data terkirim!</h3>
						<div class="mb-30 text-center">
							<img src="vendors/images/success.png" />
						</div>
						Berhasil membuat sesi tanam baru
					</div>
					<div class="modal-footer justify-content-center">
						<a href="calendar.php" class="btn btn-primary">Done</a>
					</div>
				</div>
			</div>
		</div>
		

		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/fullcalendar/fullcalendar.min.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/jquery-steps/jquery.steps.js"></script>
		<script src="vendors/scripts/steps-setting.js"></script>
		
		
		<!-- <script src="vendors/scripts/calendar-setting.js"></script> -->
		<script>
		jQuery("#calendar").fullCalendar({
			themeSystem: "bootstrap4",
			businessHours: false,
			defaultView: "month",
			
			header: {
				left: "title",
				center: "month,agendaWeek,agendaDay",
				right: "today prev,next",
			},
			events: <?php echo json_encode($dataArr); ?>,

			selectable: true,
			selecHelper: true,
			editable: true,
			select: function(start, end, allDay){
				var lahan = "<?php echo $idPilihan ?>";
				if(lahan==""){
					alert("Choose a New Schedule First on add Button!");
				}else{
					var start =$.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
				var end =$.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
				$(".tanggalstr").val(start);
				$(".tanggalend").val(end);
				$("#modal-view-event-add").modal();
				};
				
				
			},
			
			eventDrop: function(event){
				var start =$.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
				var end =$.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
				var title = event.title;
				var id = event.id;
				
				$.ajax({
					url: "calendar.php",
					type: "POST",
					data: {
						title: title,
						start:start,
						end:end,
						id:id
					}
					
				});
			},
			// dayClick: function () {
				
			// },
			eventClick: function (event, jsEvent, view) {
				jQuery(".event-icon").html("<i class='fa fa-" + event.icon + "'></i>");
				var start =$.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
				var end =$.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
				$(".idJadwal").val(event.id);
				$(".idkaryawan").val(event.karyawan);
				$(".tanggalstr").val(start);
				$(".tanggalend").val(end);
				$(".title").val(event.title);
				jQuery("#modal-view-event").modal();
			},
		});</script>
	</body>
</html>
