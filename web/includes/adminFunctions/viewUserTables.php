<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        generateUserTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function generateUserTable($mysqli)
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
        ';
                getUserTable($mysqli);

        echo '      
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    ';
}

function getUserTable($mysqli)
{
    echo '
        <div class="panel panel-default">
                        <div class="panel-heading" id="users"> 
                        	Users
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="userTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Admin</th>
                                        <th>School Admin</th>
                                        <th>Teacher</th>
                                        <th>Parent</th>
                                        <th>Student</th>
			
                                    </tr>
                                </thead>
                                <tbody>
        ';
        
	if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName, userEmail, isAdmin, isSchoolAdmin, isTeacher, isParent, isStudent FROM users"))
	{
		if($stmt->execute())
		{
			$stmt->bind_result($dbFirstName, $dbLastName, $dbEmail, $isAdmin, $isSchoolAdmin, $isTeacher, $isParent, $isStudent);
			$stmt->store_result();

			while($stmt->fetch())
			{
        		echo '<tr class="gradeA">
  						<td>' . $dbFirstName . '</td>
			  			<td>' . $dbLastName . '</td>
  						<td>' . $dbEmail . '</td>
  						<td> <input type="checkbox" disabled ' . ($isAdmin ? 'checked' : '') . '></td>
  						<td> <input type="checkbox" disabled ' . ($isSchoolAdmin ? 'checked' : '') . '></td>
  						<td> <input type="checkbox" disabled ' . ($isTeacher ? 'checked' : '') . '></td>
  						<td> <input type="checkbox" disabled ' . ($isParent ? 'checked' : '') . '></td>
  						<td> <input type="checkbox" disabled ' . ($isStudent ? 'checked' : '') . '></td>
            	   	</tr>';
			}			
		}
	}
	else
	{
		return;
	}

    echo ' 
                   </tbody>
                </table>
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->

    <script>
    $(document).ready(function() {
        $(\'#userTable\').DataTable({
            responsive: true
        });
    });
    </script>
    ';
}

?>
