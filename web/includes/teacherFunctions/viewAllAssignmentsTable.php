<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
    {
        viewAssignmentsTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAssignmentsTable($mysqli)
{

    $teacherID = $_SESSION['userID'];

    if ($stmt = $mysqli->prepare("SELECT classID, className FROM classes WHERE classTeacherID = ?"))
    {
        $stmt->bind_param('i', $teacherID);

        $stmt->execute();
        $stmt->bind_result($classID, $className);

        $stmt->store_result();

        while($stmt->fetch())
        {
            echo '
                    <!-- /.row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="'. $className . '"> 
                                        Class Name: ' . $className . '
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="' . $classID . '">
                                            <thead>
                                                <tr>
                                                    <th>Assignment Name</th>
                                                    <th>Assignment Points Possible</th>
						    <th>Assignment Due Date</th>
						    <th>Assignemnt Type</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                ';
                                                getMaterialInfo($classID, $mysqli);
            echo ' 
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


                        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
                        <script>
                        $(document).ready(function() {
                            $(\'#' . $classID . '\').DataTable({
                                responsive: true
                            });
                        });
                        </script>
                ';
        }
    }
    else
    {
        echo "You are not a teacher!";
        return;
    }   
}

function getMaterialInfo($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName, materialPointsPossible, materialDueDate, materialTypeID FROM materials WHERE materialClassID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialName, $materialPointsPossible, $materialDueDate, $materialTypeID);
        $stmt->store_result();

        while($stmt->fetch())
        {       
		
	   $materialTypeNameResult = getMaterialType($materialTypeID, $mysqli);

            echo '
                    <tr class="gradeA">
                        <td>' . $materialName . '</td>
                        <td>' . $materialPointsPossible . '</td>
                        <td>' . $materialDueDate . '</td>
			<td>' . $materialTypeNameResult . '</td>
		    </tr>
                ';
        }           
    }
    else
    {
        return;
    }
}

function getMaterialType($materialTypeID, $mysqli)
{

	if ($stmt = $mysqli->prepare("SELECT materialName FROM materialType WHERE materialTypeID = ?"))
    {
        $stmt->bind_param('i', $materialTypeID);
        $stmt->execute();
        $stmt->bind_result($materialTypeNameResult);
        $stmt->store_result();

	if ($stmt->num_rows > 0)
	{
		while ($stmt->fetch())
		{
			return $materialTypeNameResult;
		}
	}
	else
	{
		return "No Assignment Types";
	}

    }
}

?>
