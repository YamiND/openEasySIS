<?php include("../includes/customizations.php");?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title><?php echo aliasOpenEasySIS; ?> - Modify School Year</title>
    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>

</head>

<body>

    <div id="wrapper">

	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>
		<?php include('../includes/adminFunctions/viewModifySchoolYearForm.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Modify School Year</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
			
		<?php 
			viewModifySchoolYearForm($mysqli); 
		?>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>

</html>
