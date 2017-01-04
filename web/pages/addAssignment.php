<?php include("../includes/customizations.php");?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title><?php echo aliasOpenEasySIS; ?> - Add Assignment</title>
    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>

</head>

<body>

    <div id="wrapper">

	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>
		<?php include('../includes/teacherFunctions/viewAddAssignmentForm.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Assignment</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			
		<?php 
			viewAddAssignmentForm($mysqli); 
		?>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>

</html>
