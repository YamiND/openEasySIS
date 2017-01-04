<?php include("../includes/customizations.php");?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title><?php echo aliasOpenEasySIS; ?> - Add Material Type</title>
    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>

</head>

<body>

    <div id="wrapper">

	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>
		<?php include('../includes/teacherFunctions/viewAddMaterialTypeForm.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Material Type</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			
		<?php 
			viewAddMaterialTypeForm($mysqli); 
		?>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>

</html>
