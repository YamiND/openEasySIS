<?php include("../includes/customizations.php");?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo aliasOpenEasySIS; ?> - Overview</title>
    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>
</head>

<body>
    <div id="wrapper">
	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>
		<?php include('../includes/adminFunctions/viewAllAnnouncementsForm.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">View all Announcements</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
	<?php 
		if (roleID_check($mysqli) == 1):
	?>

			<?php
				viewAllAnnouncements($mysqli);
			?>
            </div>
            <!-- /.row -->

	<?php 
		else:
			echo '<h1 class="text-danger">Invalid Page Access.</h1>';
		endif;
	?>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
</body>
</html>
