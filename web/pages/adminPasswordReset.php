<!DOCTYPE html>
<html lang="en">

<!--TODO: Add user lookup? -->

<head>
    <title>openEasySIS - Reset User's Password</title>
    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>
</head>

<body>
    <div id="wrapper">
	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>
	<?php include('../includes/adminFunctions/viewAdminPasswordResetForm.php'); ?>
        <div id="page-wrapper">
       	    <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Reset User's Password</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

	<?php
		viewAdminPasswordResetForm($mysqli);
	?>

        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
</body>
</html>
