  <?php
require('koneksi.php');
session_start();
if(isset($_GET['error'])){
	$error=$_GET['error'];
}else{
	$error=0;
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
		<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
	</head>
	<body 
    
    class="login-page">
		<div class="login-header box-shadow">
			<div
				class="container-fluid d-flex justify-content-between align-items-center"
			>
				<div class="brand-logo">
					<a href="login.php">
						<img src="vendors/images/logo_edifarmbaru.png" alt="" />
					</a>
				</div>
				<div class="login-menu">
					<ul>
						<li><a href=""></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div
			class="login-wrap d-flex align-items-center flex-wrap justify-content-center"
		>
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6 col-lg-7">
						<img src="vendors/images/login.svg" alt="" />
					</div>
					<div class="col-md-6 col-lg-5">
						<div class="login-box bg-white box-shadow border-radius-10">
							<div class="login-title">
								<h2 class="text-center text-primary">Masuk Di EdiFARM</h2>
							</div>
							<form action="auth.php" method="POST">
								<div class="input-group custom">
									<input
										type="username"
										class="form-control form-control-lg"
										placeholder="Username"
                                        name="username"
									/>
									<div class="input-group-append custom">
										<span class="input-group-text"
											><i class="icon-copy dw dw-user1"></i
										></span>
									</div>
								</div>
								<div class="input-group custom">
									<input
										type="password"
										class="form-control form-control-lg"
										placeholder="**********"
                                        name="password"
									/>
									<div class="input-group-append custom">
										<span class="input-group-text"
											><i class="dw dw-padlock1"></i
										></span>
									</div>
								</div>
                                <?php
								if($error!=null){?>
									<div class="alert alert-danger" role="alert">
									<?php echo $error?>
									</div>
								<?php
								}
								?>
								<div class="row">
									<div class="col-sm-12">
										<div class="input-group mb-0">
											
											<button class="btn btn-primary btn-lg btn-block" type="login" name="login" value="Masuk">Masuk</button>
											<div class="col-md-4 col-sm-12 mb-30">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="vendors/scripts/core.js"></script>
		<script src="vendors/scripts/script.min.js"></script>
		<script src="vendors/scripts/process.js"></script>
		<script src="vendors/scripts/layout-settings.js"></script>
	</body>
</html>
