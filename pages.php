<?php
include "include/config.php";
session_start();
if ($_SESSION['status'] != "login") {
    header("location:index.php");
}
 ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>CS PILAR</title>
		<?php include "head.php";
	 ?>


		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script type="text/javascript" src="assets/js/libs/utils/html5shiv.js?1403934957"></script>
		<script type="text/javascript" src="assets/js/libs/utils/respond.min.js?1403934956"></script>
		<![endif]-->
	</head>
	<body class="menubar-hoverable header-fixed ">

		<header id="header" >
			<div class="headerbar">
				<div class="headerbar-left">
					<ul class="header-nav header-nav-options">
						<li class="header-nav-brand" >
							<div class="brand-holder">
								<a href="html/dashboards/dashboard.html">
									<span class="text-lg text-bold text-primary">YOUR TITLE</span>
								</a>
							</div>
						</li>
						<li>
							<a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>
				<div class="headerbar-right" id='actionArea'>
					<ul class="header-nav header-nav-options">
						<li class="dropdown">
							<div class="row">

								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<a href="baru.php">
										<button type="submit" class="btn ink-reaction btn-flat btn-primary">
											<i class="fa fa-plus"></i>
											baru
										</button>
									</a>
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<button type="submit" class="btn ink-reaction btn-flat btn-primary">
										<i class="fa fa-magic"></i>
										edit
									</button>
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
									<button type="submit" class="btn ink-reaction btn-flat btn-primary">
										<i class="fa fa-close"></i>
										hapus
									</button>
								</div>
							</div>
						</li>
						<li class="dropdown" id='findArea'>
							<form class="navbar-search" role="search">
								<div class="form-group">
									<input type="text" class="form-control" name="headerSearch" placeholder="Enter your keyword">
								</div>
								<button type="submit" class="btn btn-icon-toggle ink-reaction">
									<i class="fa fa-search"></i>
								</button>
							</form>
						</li>
					</ul>
				</div>
			</div>
		</header>

		<div id="base">
<?php
		$page = @$_GET['page'];
		if ($page == "informasi") {
		  include 'pages/informasi.php';
		}elseif ($page == "produk") {
		  include 'pages/produk.php';
		}elseif ($page == "acara") {
		  include 'pages/acara.php';
		}elseif ($page == "slider") {
		  include 'pages/slider.php';
		}elseif ($page == "setting") {
		  include 'pages/setting.php';
		}elseif ($page == "chating") {
		  include 'pages/chating.php';
		}elseif ($page == "userManagement") {
		  include 'pages/userManagement.php';
		}elseif ($page == "lowonganKerja") {
		  include 'pages/lowonganKerja.php';
		}elseif ($page == "team") {
		  include 'pages/team.php';
		}elseif ($page == "profile") {
		  include 'pages/profile.php';
		}else{
			echo " 404 ! halaman tidak di temukan ";
		}
?>
			<?php include "include/sidebar.php"; ?>
		</div>
		<?php include "footer.php"; ?>
	</body>
</html>
