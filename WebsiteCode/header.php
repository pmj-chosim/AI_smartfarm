<?php
require("koneksi.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		.header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 10px;
			background-color: #f0f0f0;
		}

		.nav-button {
			margin-right: 10px;
		}

		.toggle-button {
			background-color: #007bff;
			color: white;
			border: none;
			padding: 5px 10px;
			border-radius: 5px;
			cursor: pointer;

		}

		.toggle-group {
			display: flex;
			align-items: center;
			margin-left: auto;
			/* Menggeser ke sebelah kanan */
		}

		.toggle-label {
			margin-right: auto;
		}

		.header-left {
			justify-content: left;
		}
	</style>
</head>

<body>
	<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
	<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
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
		firebase.initializeApp(firebaseConfig);
		const database = firebase.database();
		const blogref1 = database.ref('/led').on('value', handleSuccess, handleError);
	
		function toggleLedStateInFirebase() {
			const ledRef = database.ref('/led');
			ledRef.once('value')
				.then(snapshot => {
					const currentState = snapshot.val();
					const newState = currentState === 'on' ? 'off' : 'on';
					ledRef.set(newState)
						.then(() => {
							console.log('LED state updated in Firebase.');
						})
						.catch(error => {
							console.error('Error updating LED state:', error);
						});
				})
				.catch(error => {
					console.error('Error reading LED state from Firebase:', error);
				});
		}

		function toggleMotorStateInFirebase() {
			const motorRef = database.ref('/motor');
			motorRef.once('value')
				.then(snapshot => {
					const currentState = snapshot.val();
					const newState = currentState === 'on' ? 'off' : 'on';
					motorRef.set(newState)
						.then(() => {
							console.log('Motor state updated in Firebase.');
						})
						.catch(error => {
							console.error('Error updating Motor state:', error);
						});
				})
				.catch(error => {
					console.error('Error reading Motor state from Firebase:', error);
				});
		}




	</script>
	<div class="header">
		<div class="header-left">
			<div class="menu-icon bi bi-list"></div>
		</div>
		<div class="header-right">
			<div class="d-flex align-items-center">
				<div class="toggle-group mr-3">
					<span class="toggle-label">LED:</span>
					<button class="toggle-button" id="led-toggle">ON</button>
				</div>
				<div class="toggle-group">
					<span class="toggle-label">Motor:</span>
					<button class="toggle-button" id="motor-toggle">ON</button>
				</div>
			</div>
		</div>
	</div>


	<script>
		const ledToggle = document.getElementById('led-toggle');
		const motorToggle = document.getElementById('motor-toggle');

		let ledOn = false;
		let motorOn = false;
		ledToggle.addEventListener('click', () => {
			ledOn = !ledOn;
			ledToggle.textContent = ledOn ? 'OFF' : 'ON';
			toggleLedStateInFirebase();
		
		});

		motorToggle.addEventListener('click', () => {
			motorOn = !motorOn;
			motorToggle.textContent = motorOn ? 'OFF' : 'ON';
			toggleMotorStateInFirebase();
		});
	</script>
</body>

</html>