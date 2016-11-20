<!DOCTYPE html>
<html lang="en">

<head>
    <title>openEasySIS - Overview</title>
    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>
    <script type="text/JavaScript" src="../js/sha512.js"></script> 
    <script type="text/JavaScript" src="../js/passwordReset.js"></script> 
</head>

<body>
    <div id="wrapper">
	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>

        <div id="page-wrapper">
       	    <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Forms</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>


	<?php
		userPasswordResetForm();
	?>

        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
</body>
</html>
