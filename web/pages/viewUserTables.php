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

</head>

<body>

    <div id="wrapper">

	<!-- Navigation Menu -->
        <?php include('../includes/navPanel.php'); ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">User Tables</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Administrators
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="adminTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
									if ($stmt = $mysqli->prepare("SELECT adminFirstName, adminLastName, adminEmail FROM adminProfile"))
									{
										$stmt->execute();
										$stmt->bind_result($adminFirstName, $adminLastName, $adminEmail);
										$stmt->store_result();

										while($stmt->fetch())
										{
                                    		echo '<tr class="gradeA">';
  											echo "<td>" . $adminFirstName . "</td>";
  											echo "<td>" . $adminLastName . "</td>";
  											echo "<td>" . $adminEmail . "</td>";
                                    		echo "</tr>";
										}			
									}
									else
									{
										return;
									}
								?>
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
            <!-- /.row -->

			<!-- School Administrators -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           School Administrators 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="schoolAdminTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
									if ($stmt = $mysqli->prepare("SELECT schoolAdminFirstName, schoolAdminLastName, schoolAdminEmail FROM schoolAdminProfile"))
									{
										$stmt->execute();
										$stmt->bind_result($schoolAdminFirstName, $schoolAdminLastName, $schoolAdminEmail);
										$stmt->store_result();

										while($stmt->fetch())
										{
                                    		echo '<tr class="gradeA">';
  											echo "<td>" . $schoolAdminFirstName . "</td>";
  											echo "<td>" . $schoolAdminLastName . "</td>";
  											echo "<td>" . $schoolAdminEmail . "</td>";
                                    		echo "</tr>";
										}			
									}
									else
									{
										return;
									}
								?>
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
			<!-- End School Administrators -->

			<!-- Teachers -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Teachers 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="teacherTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
									if ($stmt = $mysqli->prepare("SELECT teacherFirstName, teacherLastName, teacherEmail FROM teacherProfile"))
									{
										$stmt->execute();
										$stmt->bind_result($teacherFirstName, $teacherLastName, $teacherEmail);
										$stmt->store_result();

										while($stmt->fetch())
										{
                                    		echo '<tr class="gradeA">';
  											echo "<td>" . $teacherFirstName . "</td>";
  											echo "<td>" . $teacherLastName . "</td>";
  											echo "<td>" . $teacherEmail . "</td>";
                                    		echo "</tr>";
										}			
									}
									else
									{
										return;
									}
								?>
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


			<!-- Guardians -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Parents/Guardians
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="guardianTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
										<th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
									if ($stmt = $mysqli->prepare("SELECT guardianFirstName, guardianLastName, guardianEmail, guardianPhoneNumber FROM guardianProfile"))
									{
										$stmt->execute();
										$stmt->bind_result($guardianFirstName, $guardianLastName, $guardianEmail, $guardianPhone);
										$stmt->store_result();

										while($stmt->fetch())
										{
                                    		echo '<tr class="gradeA">';
  											echo "<td>" . $guardianFirstName . "</td>";
  											echo "<td>" . $guardianLastName . "</td>";
  											echo "<td>" . $guardianEmail . "</td>";
  											echo "<td>" . $guardianPhone . "</td>";
                                    		echo "</tr>";
										}			
									}
									else
									{
										return;
									}
								?>
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


			<!-- Students -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Students
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="studentTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
										<th>Gender</th>
                                        <th>Grade Level</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
									if ($stmt = $mysqli->prepare("SELECT studentFirstName, studentLastName, studentGender, studentGradeLevel FROM studentProfile"))
									{
										$stmt->execute();
										$stmt->bind_result($studentFirstName, $studentLastName, $studentGender, $studentGradeLevel);
										$stmt->store_result();

										while($stmt->fetch())
										{
                                    		echo '<tr class="gradeA">';
  											echo "<td>" . $studentFirstName . "</td>";
  											echo "<td>" . $studentLastName . "</td>";
  											echo "<td>" . $studentGender . "</td>";
  											echo "<td>" . $studentGradeLevel . "</td>";
                                    		echo "</tr>";
										}			
									}
									else
									{
										return;
									}
								?>
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

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#adminTable').DataTable({
            responsive: true
        });
    });
    $(document).ready(function() {
        $('#schoolAdminTable').DataTable({
            responsive: true
        });
    });
    $(document).ready(function() {
        $('#teacherTable').DataTable({
            responsive: true
        });
    });
    $(document).ready(function() {
        $('#guardianTable').DataTable({
            responsive: true
        });
    });
    $(document).ready(function() {
        $('#studentTable').DataTable({
            responsive: true
        });
    });
    </script>

</body>

</html>
