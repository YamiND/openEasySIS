<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
    {
        viewMaterialTypesTable($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewMaterialTypesTable($mysqli)
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
                                                    <th>Assignment Type</th>
                                                    <th>Assignment Weight</th>
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
    if ($stmt = $mysqli->prepare("SELECT materialName, materialWeight FROM materialType WHERE classID = ?"))
    {
        $stmt->bind_param('i', $classID);
        $stmt->execute();
        $stmt->bind_result($materialName, $materialWeight);
        $stmt->store_result();

        while($stmt->fetch())
        {       
            echo '
                    <tr class="gradeA">
                        <td>' . $materialName . '</td>
                        <td>' . $materialWeight . '</td>
                    </tr>
                ';
        }           
    }
    else
    {
        return;
    }
}

?>
