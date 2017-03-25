<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        generateParentChildTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function generateParentChildTable($mysqli)
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
                getParentChildTable($mysqli);

        echo '      
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    ';
}

function getParentChildTable($mysqli)
{
    echo '
        <div class="panel panel-default">
                        <div class="panel-heading" id="parentChildren"> 
                        	Parent Children Table
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="children">
                                <thead>
                                    <tr>
                                        <th>Parent\'s Name</th>
                                        <th>Children</th>
                                    </tr>
                                </thead>
                                <tbody>
        ';
        
	if ($stmt = $mysqli->prepare("SELECT userID, userFirstName, userLastName FROM users WHERE isParent"))
	{
		if($stmt->execute())
		{
			$stmt->bind_result($dbUserID, $dbUserFirstName, $dbUserLastName);
			$stmt->store_result();

			while($stmt->fetch())
			{
				if ($stmt2 = $mysqli->prepare("SELECT studentParentIDs.studentID, users.userFirstName, users.userLastName FROM studentParentIDs INNER JOIN (users) ON (users.userID = studentParentIDs.studentID AND studentParentIDs.parentID = ?)"))
				{
					$stmt2->bind_param('i', $dbUserID);

					if ($stmt2->execute())
					{
						$stmt2->bind_result($dbStudentID, $studentFirstName, $studentLastName);

						// What we will store student names in						
						$studentList = "";
					
						echo '
								<tr class="gradeA">
									<td>' . $dbUserFirstName . " " . $dbUserLastName . '</td>';

						while ($stmt2->fetch())
						{
										$studentList .= "$studentFirstName, $studentLastName <br>";
						}
						echo '
									<td>' . $studentList . '</td>
								  </tr>';
					}
				}
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
        $(\'#children\').DataTable({
            responsive: true
        });
    });
    </script>
    ';
}

?>
