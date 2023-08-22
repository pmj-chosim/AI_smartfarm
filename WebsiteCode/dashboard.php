<?php
require('koneksi.php');
$apiKey = "a4e873808077c72854f9549953b758af";
$cityId = "1643084"; // Jakarta city Code

function getWeatherData($apiKey, $cityId)
{
	$weatherServer = "http://api.openweathermap.org";
	$apiPath = "/data/2.5/weather";
	$url = "{$weatherServer}{$apiPath}?id={$cityId}&appid={$apiKey}";

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);
	curl_close($curl);

	$weatherData = json_decode($response, true);

	return $weatherData;
}

// Fetch weather data
$weatherData = getWeatherData($apiKey, $cityId);



?>
<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8" />
	<title>Smart Farm</title>
	<link rel="apple-touch-icon" sizes="180x180" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/SmartFarm-LOGO 1.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="vendors/images/SmartFarm-LOGO 1.png" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />

	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<style type="text/css">
		.progress {
			background-color: rgb(75, 192, 192);
			height: 20px;
			border-radius: 10px;
		}

		.progress-bar {
			background-color: silver;
			height: 20px;
			border-radius: 10px;
		}

		#map {
			height: 100vh;
			width: 100%
		}

		header {
			position: absolute;
			top: 10px;
			left: 60px;
			z-index: 1000;
			background: #fffd;
			padding: 10px 20px;
			width: calc(100% - 180px)
		}

		header h1 {
			padding: 0;
			margin: 0 0 5px;
			font-size: 22px
		}

		header p {
			padding: 0;
			margin: 0;
			font-size: 14px;
		}

		header .select {
			position: absolute;
			right: 20px;
			top: 1rem
		}

		header .select>select {
			font-size: 1rem;
			padding: .5rem;
			border: 1px solid #ddd !important;
		}
	</style>
</head>

<body>
<?php include 'header.php'; ?>
		
		<div class="right-sidebar">
		<?php include 'rightbar.php'; ?>
		</div>

		<?php include 'sidebar.php'; ?>
		<div class="mobile-menu-overlay"></div>
	<script>
		var firebaseConfig = {
			apiKey: "AIzaSyDp_uLCSrlxJEn9EWbFYm_kY8lLbMuT3Q4",
			authDomain: "knupolije1.firebaseapp.com",
			databaseURL: "https://knupolije1-default-rtdb.firebaseio.com",
			projectId: "knupolije1",
			storageBucket: "knupolije1.appspot.com",
			messagingSenderId: "668839492490",
			appId: "1:668839492490:web:f8da0717cf185992762673",
			measurementId: "G-2DXHZL4HQH"
		};
		if (!firebase.apps.length) {
			firebase.initializeApp(firebaseConfig);
		}


		
		function handleSuccess(snapshot) {
			const data = snapshot.val();
			if (data) {
				const latestEntryKey = Object.keys(data).pop();
				const latestEntry = data[latestEntryKey];

				if (latestEntry.soilHumidity1) {
					const soilHumidity1Data = latestEntry.soilHumidity1.slice();
					createGraph("soilHumidity1Chart", soilHumidity1Data);
				}
				if (latestEntry.soilHumidity2) {
					const soilHumidity2Data = latestEntry.soilHumidity2.slice();
					createGraph("soilHumidity2Chart", soilHumidity2Data);
				}

				const aidataRef = firebase.database().ref('aidata');
				aidataRef.once('value').then((aidataSnapshot) => {
					const aidata = aidataSnapshot.val();

					if (aidata && aidata.y1_pred_plus) {
						const y1_pred_plus = aidata.y1_pred_plus.slice();
						if (y1_pred_plus[0] < 50) {
							data_y1 = y1_pred_plus[0].toFixed(2) + " Motor On";
							document.getElementById('ai1Value').textContent = data_y1;
						} else {
							data_y1 = y1_pred_plus[0].toFixed(2) + " Motor Off";
							document.getElementById('ai1Value').textContent = data_y1;
						}
					}
					if (aidata && aidata.y2_pred_plus) {
						const y2_pred_plus = aidata.y2_pred_plus.slice();
						if (y2_pred_plus[0] < 50) {
							data_y2 = y2_pred_plus[0].toFixed(2) + " Motor On";
							document.getElementById('ai2Value').textContent = data_y2;
						} else {
							data_y2 = y2_pred_plus[0].toFixed(2) + " Motor Off";
							document.getElementById('ai2Value').textContent = data_y2;
						}
					}
				});
			}
		}

		function createGraph(chartId, data) {
			const ctx = document.getElementById(chartId).getContext("2d");

			const labels = Array.from({
				length: 24
			}, (_, i) => i); // X-axis labels: 0 to 23
			const chart = new Chart(ctx, {
				type: "line",
				data: {
					labels: labels,
					datasets: [{
						label: "Soil Moisture",
						data: data,
						borderColor: "rgba(75, 192, 192, 1)",
						backgroundColor: "rgba(75, 192, 192, 0.2)",
						fill: true,
					}, ],
				},
				options: {
					responsive: true,
					scales: {
						x: {
							title: {
								display: true,
								
							},
						},
						y: {
							title: {
								display: true,
								text: "Moisture",
							},
							suggestedMin: 0,
							suggestedMax: 100,
						},
					},
				},
			});
		}

		// Call handleSuccess(snapshot) with your Firebase snapshot data
		function readRhtDataFromFirebase() {
			const database = firebase.database();
			const rhtRef = database.ref('/rht');
			rhtRef.once('value')
				.then(snapshot => {
					const rhtData = snapshot.val();

					if (rhtData) {
						const temp = rhtData.temp.toFixed(2) + " °C";
						const humi = rhtData.humi.toFixed(2) + " %";

						document.getElementById('temperature').textContent = temp;
						document.getElementById('humidity').textContent = humi;
					} else {
						console.log('No RHT data found in Firebase.');
					}
				})
				.catch(error => {
					console.error('Error reading RHT data from Firebase:', error);
				});
		}

	function readMoisDataFromFirebase() {
            const database = firebase.database();
            const moisRef = database.ref('/mois');

            // Menggunakan orderByChild dan limitToLast untuk mengambil data terbaru
            moisRef.orderByChild('timestamp').limitToLast(1).once('value')
                .then(snapshot => {
                    const moisData = snapshot.val();

                    if (moisData) {
                        // Mengambil child node pertama dari objek data terbaru
                        const latestMoisKey = Object.keys(moisData)[0];
                        const latestMois = moisData[latestMoisKey];

                        const mois1 = parseInt(latestMois.mois1);
                        const mois2 = parseInt(latestMois.mois2);

                        // Update array values
                        const soilPercents = [mois1, mois2];
                        console.log('Updated soilPercents array:', soilPercents);

                        // Update progress bar widths
                        const mois1ProgressBar = document.querySelector('.mois1-progress');
                        mois1ProgressBar.style.width = mois1 + '%';
                        
                        const mois2ProgressBar = document.querySelector('.mois2-progress');
                        mois2ProgressBar.style.width = mois2 + '%';
						document.getElementById('mois1Value').textContent = mois1;
						document.getElementById('mois2Value').textContent = mois2;
                    } else {
                        console.log('No Mois data found in Firebase.');
                    }
                })
                .catch(error => {
                    console.error('Error reading Mois data from Firebase:', error);
                });
        }
		// Panggil fungsi untuk membaca dan menampilkan data dari Firebase
		readRhtDataFromFirebase();
		readMoisDataFromFirebase();

		function updateDataText(elementId, dataArray) {
			const element = document.getElementById(elementId);
			element.textContent = JSON.stringify(dataArray);
		}

		function handleError(error) {
			console.error(error);
		}
		// Mendaftarkan listener untuk pembaruan data
		const dataRef = database.ref('/data');
		dataRef.on('value', handleSuccess, handleError);
	</script>
	<div class="main-container">

		<div class="xs-pd-20-10 pd-ltr-20">
			<div class="row">
				<div class="col-md-6">
					<div class="title pb-20">
						
<h4 style="text-align: left"> Prediction For Pot 1 By AI: <span id="ai1Value">  </span></h4>
					<h4 style="text-align: left">Prediction For Pot 2 By AI: <span id="ai2Value"></span> </h4>
					</div>
				</div>
				<div class="col-md-6">
					<p style="text-align: right">Temperature Area : <span id="temperature"></span></p>
					<p style="text-align: right">Humidity : <span id="humidity"></span></p>
					
					<p style="text-align: right">
						<?php
						if ($weatherData) {
							$kelvinTemp = $weatherData['main']['temp'];
							$celsiusTemp = $kelvinTemp - 273.15;
							echo "Temperature: " . round($celsiusTemp, 2) . " &#8451;<br>";

							echo "Weather: " . $weatherData['weather'][0]['description'] . "<br>";
							echo "Wind Speed: " . $weatherData['wind']['speed'] . " m/s";
						} else {
							echo "Failed to fetch weather data.";
						}
						?>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title" style="text-align: center;">
								<i class="far fa-chart-bar"></i>
								Line Chart 1
							</h3>
							<h3 class="card-title" style="text-align: center;">
								<i class="far fa-chart-bar"></i>
								Date:
								<?php echo date("d F Y"); ?>
							</h3>
							<?php
							// Contoh data soilPercents
							$soilPercents = array(25, 100);

							echo '<div class="mb-1 text-lg font-bold text-center">Moisture</div>';
							echo '<div class="mx-auto w-9/12 h-6 bg-gray-200 rounded-full dark:bg-gray-700 text-center">';
							echo '<div class="h-6 bg-gradient-to-r from-cyan-500 to-indigo-500 rounded-full font-bold text-slate-200" style="width: ' . 100 . '%">';
							echo  '<span id="mois1Value"></span>' . '%</div></div>';
							?>

							<?php
							// Simulasikan nilai progres dari PHP (misalnya, dari basis data)
							$maxValue = 50;
							$currentValue = 40;
							$percentage = ($currentValue / $maxValue) * 100;
							?>

							<div class="progress-container">
								<div class="progress-bar">
									<div class="progress mois1-progress" style="width: <?php echo 94 ?>%;">
									</div>
								</div>
							</div>
							<div class="card-body">
								<canvas id="soilHumidity1Chart"></canvas>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title" style="text-align: center;">
								<i class="far fa-chart-bar"></i>
								Line Chart 2
							</h3>
							<h3 class="card-title" style="text-align: center;">
								<i class="far fa-chart-bar"></i>
								Date:
								<?php echo date("d F Y"); ?>
							</h3>

							<?php
							$soilPercents = array(0, 40, 80, 10, 100);

							echo '<div class="mb-1 text-lg font-bold text-center">Moisture</div>';
							echo '<div class="mx-auto w-9/12 h-6 bg-gray-200 rounded-full dark:bg-gray-700 text-center">';
							echo '<div class="h-6 bg-gradient-to-r from-cyan-500 to-indigo-500 rounded-full font-bold text-slate-200" style="width: ' . 100 . '%">';
							echo '<span id="mois2Value"></span>' . '%</div></div>';
							?>
							<!-- Add your card header buttons here -->
						<div class="progress-container">
								<div class="progress-bar">
									<div class="progress mois2-progress" style="width: <?php echo $soilPercents[0]; ?>%;"></div>
								</div>
							</div>
						<div class="card-body">
							<canvas id="soilHumidity2Chart"></canvas>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- welcome modal end -->
	<!-- js -->
	<!-- add sweet alert js & css in footer -->
	<script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    <script src="src/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js"></script>
    <script src="vendors/scripts/advanced-components.js"></script>
    <script>
    </script>
    <script src="src/plugins/sweetalert2/sweetalert2.all.js"></script>
	<script src="src/plugins/sweetalert2/sweet-alert.init.js"></script>
</body>

</html>