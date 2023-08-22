<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Tentang</title>
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
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Tentang</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="index.html">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Tentang
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="faq-wrap">
						<h4 class="mb-20 h4 text-blue">Fitur-Fitur</h4>
						<div id="accordion">
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block"
										data-toggle="collapse"
										data-target="#faq1"
									>
										Apa itu Edifarm?
									</button>
								</div>
								<div id="faq1" class="collapse show" data-parent="#accordion">
									<div class="card-body">
									Edifarm adalah suatu perangkat yang dikembangkan untuk membantu admin 
                                    dalam memonitoring beberapa sawah, walaupun dengan jarak jauh. Edifarm 
                                    juga menyediakan pada perangkat mobile android untuk karyawan yang 
                                    mengelola sawah

                                    Website edifarm hanya dapat diakses oleh admin yakni pemilik sawah. Admin 
                                    dapat menambah karyawan, mengubah jadwal, menerima konsultasi dari karyawan,
                                    melihat kegiatan yang dilakukan karyawan disawah.
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block collapsed"
										data-toggle="collapse"
										data-target="#faq2"
									>
										Menambahkan Lahan
									</button>
								</div>
								<div id="faq2" class="collapse" data-parent="#accordion">
									<div class="card-body">
									Anda dapat menambahkan lahan sawah yang anda miliki pada fitur lahan. 
										Dalam fitur lahan terdapat detail mengenai lahan yang anda bisa lihat.
										Anda dapat menambahkan lahan dengan mengklik button tambah pada pojok bawah.
										Fitur lahan memudahkan anda dalam melihat detail lahan yang anda miliki, seperti 
										nama lahan anda, luas lahan, deskripsi, dan tempat lahan yang anda miliki.
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block collapsed"
										data-toggle="collapse"
										data-target="#faq3"
									>
										Jenis Padi Yang Anda Gunakan
									</button>
								</div>
								<div id="faq3" class="collapse" data-parent="#accordion">
									<div class="card-body">
										<p>
										Pada fitur padi anda dapat melihat data detail varietas jenis padi yang anda
											gunakan pada lahan anda. Anda dapat menambahkan detail padi dengan mengklik button tambah pada pojok 
											bawah. Anda dapat menambahkan nama jenis padi, lama tanam, dan deskripsi pada detail padi.
											Anda dapat menambahkan hari ke berapa padi yang anda gunakan untuk dilakukan irigasi, pemupukan, dan 
											pestisida, sesuai dengan jenis padi anda.
										</p>
										<p class="mb-0">

										</p>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block collapsed"
										data-toggle="collapse"
										data-target="#faq4"
									>
										Jadwal Kegiatan Pada Lahan
									</button>
								</div>
								<div id="faq4" class="collapse" data-parent="#accordion">
									<div class="card-body">
									Fitur Jadwal yakni memudahkan anda dalam melihat jadwal kegiatan yang sedang dan akan 
										karyawan anda kerjakan pada lahan yang anda miliki. Anda dapat menambahkan jadwal kegiatan 
										sesuai dengan lahan berapa yang anda pilih. Anda juga dapat melihat kalender jadwal kegiatan 
										karyawan dalam tiap bulan, tiap minggunya, maupun jadwal kegiatan hari itu juga. Anda dapat 
										menambahkan jadwal dengan mengklik button tambah pada pojok bawah, serta mengisinya dengan nama kegiatan, 
										tanggal memulai kegiatan hingga mengakhiri kegiatan, serta deskripsi kegiatan. Anda dapat mengeditnya 
										apabila ingin memperpanjang kegiatan dengan menyeret nama kegiatan hingga ke tanggal yang ingin anda pilih.
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block collapsed"
										data-toggle="collapse"
										data-target="#faq5"
									>
										Data Karyawan
									</button>
								</div>
								<div id="faq5" class="collapse" data-parent="#accordion">
									<div class="card-body">
									Data Karyawan dapat membantu anda dalam melihat data beberapa karyawan yang mengerjakan lahan anda.
										Pada data karyawan anda dapat membuatkan akun untuk karyawan anda agar dapat mengakses aplikasi pada mobile Edifarm,
										Apabila anda ingin menambahkan karyawan untuk menggarap lahan, anda harus membuatkan akun juga untuk karyawan baru anda. 
										Ketika anda ingin menambahkan data karyawan anda hanya perlu mengklik button tambah pada pojok bawah, dan mengisi semua 
									    sesuai dengan field yang ada.Anda dapat mengubah data karyawan anda apabila terdapat kesalahan, dengan mengklik icon edit pada kolom aksi pada tabel. 
										Anda juga dapat menghapus karyawan apabila karyawan tersebut sudah tidak bekerja menggarap lahan anda dengan mengklik icon hapus 
										pada kolom aksi ditabel.
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block collapsed"
										data-toggle="collapse"
										data-target="#faq6"
									>
										Konsultasi Permasalahan Padi
									</button>
								</div>
								<div id="faq6" class="collapse" data-parent="#accordion">
									<div class="card-body">
										<p>
										Fitur ini membantu anda dalam mengetahui permalasahan yang ada pada padi di lahan yang anda miliki.
										Anda dapat melihat permasalahan yang di konsultasikan oleh karyawan anda, sehingga anda dapat membantu
										karyawan dengan menyelesaikan permasalahan tersebut. Anda dapat mengubah atau mengedit kegiatan untuk 
										mengatasi masalah tersebut sesuai dengan jenis padi dan masalah yang ada pada padi. Sehingga karyawan 
										dapat melakukan kegiatan yang dikonsultasikan tersebut.
										</p>
										<p class="mb-0">
											
										</p>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block collapsed"
										data-toggle="collapse"
										data-target="#faq7"
									>
										Laporan Riwayat
									</button>
								</div>
								<div id="faq7" class="collapse" data-parent="#accordion">
									<div class="card-body">
									Laporan ini memudahkan anda dalam melakukan pendataan dokumentasi pada setiap lahan yang anda miliki. 
										Pada fitur laporan ini terdapat laporan karyawan, laporan lahan, dan laporan konsultasi. Anda dengan mudah 
										melihat setiap riwayat kegiatan yang dilakukan. Untuk kebutuhan dokumentasi anda dapat mencetak setiap laporan 
										anda pilih untuk dilakukan pendataan.
									</div>
								</div>
							</div>
						</div>
						<h4 class="mb-30 h4 text-blue padding-top-30">Tampilan Website</h4>
						<div class="padding-bottom-30">
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block"
										data-toggle="collapse"
										data-target="#faq1-1"
									>
										Lihat Profile Anda
									</button>
								</div>
								<div id="faq1-1" class="collapse show">
									<div class="card-body">
									Anda dapat mengedit atau mengubah profil anda dengan mengklik foto profil anda
										pada header pojok atas hingga muncul dropdown menu, lalu klik edit profil. Anda 
										dapat mengubah foto profil, serta data diri anda pada fitur ini. Apabila anda sudah 
										mengedit profile anda, akan otomatis berubah sesuai dengan yang anda edit.
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<button
										class="btn btn-block collapsed"
										data-toggle="collapse"
										data-target="#faq2-2"
									>
										Ubah Mode Layar
									</button>
								</div>
								<div id="faq2-2" class="collapse">
									<div class="card-body">
									Anda dapat merubah tampilan website anda dengan mengklik profil anda pada header pojok
										atas hingga muncul dropdown menu, lalu pilih pengaturan. Maka anda dapat memilih ingin 
										mengubah website anda pada mode gelap atau mode terang sesuai dengan tampilan mana yang
										ingin anda rubah.
									</div>
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
