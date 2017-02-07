<?php
//TODO: Test this after adding multiple students to a class
function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isStudent($mysqli)))
    {
        viewStudentDueAssignmentsTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewStudentDueAssignmentsTable($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Due Assignments");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#dueAssignments" data-toggle="tab">Due Assignments</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="dueAssignments">
        ';
                                viewDueAssignments($mysqli);
    echo '
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
        ';

}

function viewDueAssignments($mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT classID FROM studentClassIDs WHERE studentID = ?"))
    {
        $stmt->bind_param('i', $_SESSION['userID']);
        $stmt->execute();
        $stmt->bind_result($classID);
        $stmt->store_result();

        while($stmt->fetch())
        {   
            echo '
                    <h4> Class Name: ' . getClassName($classID, $mysqli) . '</h4>
                    <br>
                    <table width="100%" class="table table-striped table-bordered table-hover" id="' . $classID . '">
                        <thead>
                            <tr>
                                <th>Assignment Name</th>
                                <th>Assignment Type</th>
                                <th>Assignment Due Date</th>
                                <th>Assignment Points Possible</th>
                            </tr>
                        </thead>
                        <tbody>
                ';          
                            getDueAssignmentsForClass($classID, $mysqli);
            echo ' 
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->

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
        echo "No Clases";
        return;
    }
}

function getDueAssignmentsForClass($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName, materialPointsPossible, materialTypeID, materialDueDate FROM materials WHERE materialClassID = ? AND materialDueDate >= curdate()"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialName, $materialPointsPossible, $materialTypeID, $materialDueDate);
        $stmt->store_result();

        while($stmt->fetch())
        {
                
            echo '
                    <tr class="gradeA">
                        <td>' . $materialName . '</td>
                        <td>' . getMaterialType($materialTypeID, $mysqli) . '</td>
                        <td> '. $materialDueDate . '</td>
                        <td>' . $materialPointsPossible . '</td>
                    </tr>
                ';     
        }           
    }
    else
    {
        return "0";
    }
}

function getMaterialType($materialTypeID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialName FROM materialType WHERE materialTypeID = ?"))
    {
        $stmt->bind_param('i', $materialTypeID);
        $stmt->execute();
        $stmt->bind_result($materialName);
        $stmt->store_result();

        while($stmt->fetch())
        {
            return $materialName;     
        }           
    }
    else
    {
        return "N/A";
    }
}

function getClassName($classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT className FROM classes WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($className);
        $stmt->store_result();

        while($stmt->fetch())
        {
            return $className;     
        }           
    }
    else
    {
        return "0";
    }
}

?>