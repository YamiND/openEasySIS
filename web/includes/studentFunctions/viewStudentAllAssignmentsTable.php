<?php
//TODO: Test this after adding multiple students to a class
function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 5))
    {
        viewStudentAllAssignmentsTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewStudentAllAssignmentsTable($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	     ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("All Assignments");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            
        ';
                                viewAllAssignments($mysqli);
    echo '
                                
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
        ';

}

function viewAllAssignments($mysqli)
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
                                <th>Assignment Points Scored</th>
                                <th>Assignment Points Possible</th>
                            </tr>
                        </thead>
                        <tbody>
                ';          
                            getAllAssignmentsForClass($classID, $_SESSION['userID'], $mysqli);
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

function getAllAssignmentsForClass($classID, $studentID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT materialID, materialName, materialPointsPossible, materialTypeID, materialDueDate FROM materials WHERE materialClassID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialID, $materialName, $materialPointsPossible, $materialTypeID, $materialDueDate);
        $stmt->store_result();

        while($stmt->fetch())
        {
                
            echo '
                    <tr class="gradeA">
                        <td>' . $materialName . '</td>
                        <td>' . getMaterialType($materialTypeID, $mysqli) . '</td>
                        <td> '. $materialDueDate . '</td>
                        <td>' . getMaterialPointsScored($materialID, $studentID, $classID, $mysqli) . '</td>
                        <td> /' . $materialPointsPossible . '</td>
                    </tr>
                ';     
        }           
    }
    else
    {
        return "0";
    }
}

function getMaterialPointsScored($materialID, $studentID, $classID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT gradeMaterialPointsScored FROM grades WHERE gradeMaterialID = ? AND gradeClassID = ? AND gradeStudentID = ?"))
    {
        $stmt->bind_param('iii', $materialID, $classID, $studentID);
        $stmt->execute();
        $stmt->bind_result($materialPointsScored);
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
            while($stmt->fetch())
            {
                return $materialPointsScored;     
            }                       
        }
        else
        {
            return "Assignment not yet Graded";
        }
    }
    else
    {
        return "Assignment not yet Graded";
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