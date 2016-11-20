<!DOCTYPE html>
<html lang="en">

<head>

    <title>User Tables</title>
    <!-- Header Information, CSS, and JS -->
    <?php include("../includes/header.php"); ?>

    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

	<!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

</head>

<body>

    <div id="wrapper">

	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>
		<?php include('../includes/adminFunctions/viewUserTables.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">User Tables</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

			<?php
				generateUserTable($mysqli, "Administrators", "adminFirstName", "adminLastName", "adminEmail", "adminProfile");
				generateUserTable($mysqli, "School Administrators", "schoolAdminFirstName", "schoolAdminLastName", "schoolAdminEmail", "schoolAdminProfile");
				generateUserTable($mysqli, "Teachers", "teacherFirstName", "teacherLastName", "teacherEmail", "teacherProfile");
				generateUserTable($mysqli, "Guardians", "guardianFirstName", "guardianLastName", "guardianEmail", "guardianProfile");
				generateUserTable($mysqli, "Students", "studentFirstName", "studentLastName", "studentEmail", "studentProfile");
			?>
            <!-- /.row -->
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
			<!-- End Teachers -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>

</html>
