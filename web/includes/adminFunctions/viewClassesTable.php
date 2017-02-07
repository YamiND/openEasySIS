<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        generateClassesTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function generateClassesTable($mysqli)
{
	echo '
        <!-- DataTables CSS -->
        <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

        <!-- DataTables Responsive CSS -->
        <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

        <!-- DataTables JavaScript -->
        <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
        <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
		<!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" id="Classes"> 
                           Classes 
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="classTable">
                                <thead>
                                    <tr>
                                        <th>Class Name</th>
                                        <th>Grade Level</th>
                                        <th>Teacher Email</th>
                                    </tr>
                                </thead>
                                <tbody>';
					 getClassesTableData($mysqli);
                               echo ' </tbody>
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


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $(\'#classTable\').DataTable({
            responsive: true
        });
    });
    </script>';
}

function getClassesTableData($mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT className, classGrade, classTeacherID FROM classes"))
	{
		$stmt->execute();
		$stmt->bind_result($dbClassName, $dbClassGrade, $dbClassTeacherID);
		$stmt->store_result();

		while($stmt->fetch())
		{
			if($stmt2 = $mysqli->prepare("SELECT teacherEmail FROM teacherProfile WHERE teacherID = ?"))
			{	
				$stmt2->bind_param('s', $dbClassTeacherID);
				$stmt2->bind_result($dbTeacherEmail);
				$stmt2->execute();
				$stmt2->store_result();
				
	        	while($stmt2->fetch()) 	
				{
					echo '<tr class="gradeA">
	  				<td>' . $dbClassName . '</td>
	  				<td>' . $dbClassGrade . '</td>
	  				<td>' . $dbTeacherEmail . '</td>
	            			</tr>';
				}
			}
		}			
	}
	else
	{
		return;
	}
}

?>
