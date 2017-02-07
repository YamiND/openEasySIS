<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewAllSchoolYears($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAllSchoolYears($mysqli)
{
    echo '
            <!-- col-lg-12 -->
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        All School Years
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        ';
                        getAllSchoolYears($mysqli);
        echo '
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
            ';
}


function getAllSchoolYears($mysqli)
{
    echo '
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>School Year Start</th>
                            <th>School Year End</th>
                            <th>Fall Semester Start</th>
                            <th>Fall Semester End</th>
                            <th>Spring Semester Start</th>
                            <th>Spring Semester End</th>
                            <th>Quarter One Start</th>
                            <th>Quarter One End</th>
                            <th>Quarter Two Start</th>
                            <th>Quarter Two End</th>
                            <th>Quarter Three Start</th>
                            <th>Quarter Three End</th>
                        </tr>
                    </thead>
                    <tbody>
        ';

    if ($stmt = $mysqli->prepare("SELECT fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd, quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, schoolYearStart, schoolYearEnd FROM schoolYear"))
        {
            $stmt->execute();
            $stmt->bind_result($dbFallSemesterStart, $dbFallSemesterEnd, $dbSpringSemesterStart, $dbSpringSemesterEnd, $dbQuarterOneStart, $dbQuarterOneEnd, $dbQuarterTwoStart, $dbQuarterTwoEnd, $dbQuarterThreeStart, $dbQuarterThreeEnd, $dbSchoolYearStart, $dbSchoolYearEnd);
            $stmt->store_result();

            
            while($stmt->fetch())
            {  
                    echo "<tr>";
                    echo "<td>" . $dbSchoolYearStart . "</td>";
                    echo "<td>" . $dbSchoolYearEnd . "</td>";
                    echo "<td>" . $dbFallSemesterStart . "</td>";
                    echo "<td>" . $dbFallSemesterEnd . "</td>";
                    echo "<td>" . $dbSpringSemesterStart . "</td>";
                    echo "<td>" . $dbSpringSemesterEnd . "</td>";
                    echo "<td>" . $dbQuarterOneStart . "</td>";
                    echo "<td>" . $dbQuarterOneEnd . "</td>";
                    echo "<td>" . $dbQuarterTwoStart . "</td>";
                    echo "<td>" . $dbQuarterTwoEnd . "</td>";
                    echo "<td>" . $dbQuarterThreeStart . "</td>";
                    echo "<td>" . $dbQuarterThreeEnd . "</td>";
                    echo "</tr>";
            }   
        }
        else
        {
            echo "School year does not exist for " . date("Y") . ", Please add School Year";
            return;
        }

        echo '
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            ';
}
?>
