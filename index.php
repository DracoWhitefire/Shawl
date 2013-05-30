<!DOCTYPE HTML>
<?php
	require_once("includes/functions.php");
	db_connect();
	$subject_set = get_all_subjects();
	get_selected_id();
	$current_subject = get_subject_by_id($current_id);
?>

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
		<title><?php echo SITE_TILE; ?></title>
		<link rel="stylesheet" type="text/css" href="css/common.css" />
	</head>
	<body>
		<div id="site_div">
			<div id="siteTitle_div">
				<h1><?php echo SITE_TILE; ?></h1>
			</div>
			<div id="body_div">
				<div id="nav_div">
					<?php echo navigation($subject_set); ?>
				</div>
				<div id="main_div">
					<div id="pageTitle_div">
						<h2><?php echo $current_subject["menu_name"]; ?></h2>
					</div>
					<div id="content_div">
						<?php
							$includefile = strtolower("content/{$current_subject["menu_name"]}/main.php");
							include($includefile);
							
						?>
						<div id="debug_div">
							<?php
								print_r($_POST);							
							?>
						</div>
					</div>
				</div>

			</div>
			<div id="copyright_div">
				Copyright <?php echo date("Y"); ?>
			</div>
		</div>
	</body>
</html>
<?php
	mysql_close($connection);
?>