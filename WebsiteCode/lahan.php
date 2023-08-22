<?php 
require('koneksi.php');
include('limitKata.php');
$limit = new limit();
 // Check If form submitted, insert form data into users table.
 if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $des = $_POST['des'];
    
    $name = $_FILES['image']['name'];
    $tmp_path = $_FILES['image']['tmp_name'];
    $ext = pathinfo($name, PATHINFO_EXTENSION); // Get file extension
    $filename = time() . '.' . $ext; // Generate unique filename
    
    $path = "vendors/images/$filename";
    
    // Move uploaded image to the desired location
    move_uploaded_file($tmp_path, $path);
    
    $query = "INSERT INTO `pot` (`id_pot`, `pot_name`, `description`, `image`, `status`) VALUES (NULL, '$nama', '$des', '$path', 'Empty')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Redirect to a different page after successful data submission
        header("Location: lahan.php");
        exit(); // Make sure to exit after sending the redirect header
    }
}
 if(isset($_POST['update'])) {
	$id=$_POST['id'];
	$nama = $_POST['nama'];
	 $des = $_POST['des'];
	$query =  "UPDATE `pot` SET `pot_name`='$nama',`description`='$des' WHERE id_pot='$id'";
	$result = mysqli_query($koneksi,$query);
}
if(isset($_POST['delete'])){
	$id=$_POST['id'];
	$query =  "DELETE FROM pot WHERE id_pot='$id'";
	$result = mysqli_query($koneksi,$query);
}

 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Pot</title>
		<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/SmartFarm-LOGO 1.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
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
									<h4>Pot</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Pot
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<?php 
						$query = "SELECT * FROM pot";
						$result = mysqli_query($koneksi,$query);
						while($row= $row = mysqli_fetch_array($result)){
							$id=$row["id_pot"];
							$status=$row["status"];
							$des=$row['description'];
							$img=$row['image'];
						?>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
							<div class="card card-box text-center">
								<div class=" d-flex justify-content-between pb-10">
									<img class="card-img-top" src= "<?php echo $img; ?>" alt=""/>
									
								</div>
								<div class="card-body">
									<h5 class="card-title weight-500 text-left"><?php echo $row["pot_name"];?></h5>
									<p class="card-text text-left"><?= $limit->limit_kata($des,5) ;?></p>
									<p class="card-text text-left"><?= $status?></p>
									<div>
										<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#detail<?= $row["id_pot"];?>" >Detail</a>
										<div
											class="modal fade bs-example-modal-lg"
											id="detail<?php echo $row["id_pot"];?>"
											tabindex="-1"
											role="dialog"
											aria-labelledby="myLargeModalLabel"
											aria-hidden="true"
										>
											<div class="modal-dialog modal-lg modal-dialog-centered">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title" id="myLargeModalLabel">
														<?= $row["pot_name"];?>
														</h4>
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
															×
														</button>
													</div>
													<div class="modal-body">
														<form action="lahan.php" method="POST">
															<input hidden class="form-control" value="<?= $id?>" type="text" name="id">
															<div class="form-group row">
																<label class="col-sm-12 col-md-2 col-form-label" for="des">Pot</label>
																<div class="col-sm-12 col-md-10">
																	<input class="form-control" value="<?= $row["pot_name"];?>" type="text" name="nama">
																</div>
															</div>									
															
															<div class="form-group row">
																<label class="col-sm-12 col-md-2 col-form-label" for="des">Description</label>
																<div class="col-sm-12 col-md-10">
																	<input class="form-control" value="<?= $row["description"];?>" type="text" name="des">
																</div>
															</div>	
															
															
														</div>
														<div class="modal-footer">
															<input type="submit" name="delete" class="btn btn-primary" value="Delete" onclick="return confirm('Sure, Want to Delete Data?')">	
															<button type="button" class="btn btn-secondary" data-dismiss="modal" alt="add-modal-kar">Close</button>
															<input type="submit" name="update" class="btn btn-primary" value="Update" onclick= "">
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
						}
						?>
					</div>
				</div>
			</div>
		</div>		
		<div class="add-modal-kar">
			<button href="#" class="welcome-modal-btn" data-toggle="modal" data-target="#tambahlahan">(+) Add</button>
		</div>
		
		<div class="modal fade bs-example-modal-lg"
			id="tambahlahan"
			tabindex="-1"
			role="dialog"
			aria-labelledby="myLargeModalLabel"
			aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myLargeModalLabel">Add Pot</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" alt="add-modal-kar">x</button>
					</div>
					<div class="modal-body">						
						<form action="lahan.php" method="POST" enctype="multipart/form-data">
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label" for="nama">Pot Name</label>
								<div class="col-sm-12 col-md-10">
									<input class="form-control" type="nama" placeholder="Pot 1, Pot 2, Pot 3" name="nama" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-12 col-md-2 col-form-label" for="des">Description</label>
								<div class="col-sm-12 col-md-10">
									<input class="form-control" placeholder="The Soil Moisture of pot 1 have good soil" type="des" name="des" required>
								</div>
							</div>
							<div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label" for="des">Images</label>
                <div class="col-sm-12 col-md-10">
                    <input class="" type="file" name="image" id="image" required>
                </div>
            </div>
						
							</div>
								<div class="modal-footer">
									<button
										type="button"
										class="btn btn-secondary"
										data-dismiss="modal"
										alt="add-modal-kar"
									>Cancel
									</button>
									<input type="submit" name="submit" class="btn btn-primary" value="Save" id="sa-success">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- js -->
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script>
		<script src="vendors/scripts/advanced-components.js"></script>
		<script>
			function inputAngka(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
				return true;
			}
		</script>
	</body>
</html>