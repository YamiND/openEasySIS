<?php

// TODO: I need to rewrite this and provide a year select

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewModifySchoolYearForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

date_default_timezone_set('America/New_York');

function viewModifySchoolYearForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Modify School Year");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#modifyClass" data-toggle="tab">Modify a Class</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <h4>Modify School Year</h4>
                                <div class="tab-pane fade in active" id="modifyClass">';
                                        getSchoolYearInfo($mysqli);
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

function getSchoolYearInfo($mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT schoolYearID, fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd, quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, schoolYearStart, schoolYearEnd FROM schoolYear WHERE year(schoolYearStart) = year(curdate())"))
    {
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($dbSchoolYearID, $dbFallSemesterStart, $dbFallSemesterEnd, $dbSpringSemesterStart, $dbSpringSemesterEnd, $dbQuarterOneStart, $dbQuarterOneEnd, $dbQuarterTwoStart, $dbQuarterTwoEnd, $dbQuarterThreeStart, $dbQuarterThreeEnd, $dbSchoolYearStart, $dbSchoolYearEnd);
         
        $stmt->fetch();

        echo '
            <form action="../includes/adminFunctions/modifySchoolYear" method="post" role="form">
                <input type="hidden" name="schoolYearID" value="'. $dbSchoolYearID .'">
                <div class="form-group">
                    <label>School Year Start Date</label>
                    <input class="form-control" type="date" name="schoolYearStart" value="' . $dbSchoolYearStart . '">
                </div>
                <div class="form-group">
                    <label>School Year End Date</label>
                    <input class="form-control" type="date" name="schoolYearEnd" value="' . $dbSchoolYearEnd . '">
                </div>
                <div class="form-group">
                    <label>Fall Semester Start Date</label>
                    <input class="form-control" type="date" name="fallSemesterStart" value="' . $dbFallSemesterStart . '">
                </div>
                <div class="form-group">
                    <label>Fall Semester End Date</label>
                    <input class="form-control" type="date" name="fallSemesterEnd" value="' . $dbFallSemesterEnd . '">
                </div>
                <div class="form-group">
                    <label>Spring Semester Start Date</label>
                    <input class="form-control" type="date" name="springSemesterStart" value="' . $dbSpringSemesterStart . '">
                </div>
                <div class="form-group">
                    <label>Spring Semester End Date</label>
                    <input class="form-control" type="date" name="springSemesterEnd" value="' . $dbSpringSemesterEnd . '">
                </div>
                <div class="form-group">
                    <label>Quarter One Start Date</label>
                    <input class="form-control" type="date" name="quarterOneStart" value="' . $dbQuarterOneStart . '">
                </div>
                <div class="form-group">
                    <label>Quarter One End Date</label>
                    <input class="form-control" type="date" name="quarterOneEnd" value="' . $dbQuarterOneEnd . '">
                </div>
                <div class="form-group">
                    <label>Quarter Two Start Date</label>
                    <input class="form-control" type="date" name="quarterTwoStart" value="' . $dbQuarterTwoStart . '">
                </div>
                <div class="form-group">
                    <label>Quarter Two End Date</label>
                    <input class="form-control" type="date" name="quarterTwoEnd" value="' . $dbQuarterTwoEnd . '">
                </div>
                <div class="form-group">
                    <label>Quarter Three Start Date</label>
                    <input class="form-control" type="date" name="quarterThreeStart" value="' . $dbQuarterThreeStart . '">
                </div>
                <div class="form-group">
                    <label>Quarter Three End Date</label>
                    <input class="form-control" type="date" name="quarterThreeEnd" value="' . $dbQuarterThreeEnd . '">
                </div>
                <button type="submit" class="btn btn-default">Modify School Year</button>
            </form>
        ';
    }
    else
    {
        echo "School year does not exist for " . date("Y") . ", Please add School Year";
        return;
    }
}
?>
