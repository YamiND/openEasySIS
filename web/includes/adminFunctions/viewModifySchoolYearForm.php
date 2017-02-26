<?php

// TODO: I need to rewrite this and provide a year select

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewModifySchoolYearForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
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
                                <li class="active"><a href="#modifySchoolYear" data-toggle="tab">Modify School Year</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="modifyClass">
                                    <br>
        ';
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
    if ($stmt = $mysqli->prepare("SELECT schoolYearID, fallSemesterStart, fallSemesterEnd, springSemesterStart, springSemesterEnd, quarterOneStart, quarterOneEnd, quarterTwoStart, quarterTwoEnd, quarterThreeStart, quarterThreeEnd, schoolYearStart, schoolYearEnd, quarterFourStart, quarterFourEnd FROM schoolYear WHERE schoolYearStart <= CURDATE() AND schoolYearEnd >= CURDATE()"))
    {
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($dbSchoolYearID, $dbFallSemesterStart, $dbFallSemesterEnd, $dbSpringSemesterStart, $dbSpringSemesterEnd, $dbQuarterOneStart, $dbQuarterOneEnd, $dbQuarterTwoStart, $dbQuarterTwoEnd, $dbQuarterThreeStart, $dbQuarterThreeEnd, $dbSchoolYearStart, $dbSchoolYearEnd, $quarterFourStart, $quarterFourEnd);
         
        $stmt->fetch();

        generateFormStart("../includes/adminFunctions/modifySchoolYear", "post");
            generateFormHiddenInput("schoolYearID", $dbSchoolYearID);
            generateFormInputDiv("School Year Start Date", "date", "schoolYearStart", $dbSchoolYearStart);
            generateFormInputDiv("School Year End Date", "date", "schoolYearEnd", $dbSchoolYearEnd);
            generateFormInputDiv("Fall Semester Start Date", "date", "fallSemesterStart", $dbFallSemesterStart);
            generateFormInputDiv("Fall Semester End Date", "date", "fallSemesterEnd", $dbFallSemesterEnd);
            generateFormInputDiv("Spring Semester Start Date", "date", "springSemesterStart", $dbSpringSemesterStart);
            generateFormInputDiv("Spring Semester End Date", "date", "springSemesterEnd", $dbSpringSemesterEnd);
            generateFormInputDiv("Quarter One Start Date", "date", "quarterOneStart", $dbQuarterOneStart);
            generateFormInputDiv("Quarter One End Date", "date", "quarterOneEnd", $dbQuarterOneEnd);
            generateFormInputDiv("Quarter Two Start Date", "date", "quarterTwoStart", $dbQuarterTwoStart);
            generateFormInputDiv("Quarter Two End Date", "date", "quarterTwoEnd", $dbQuarterTwoEnd);
            generateFormInputDiv("Quarter Three Start Date", "date", "quarterThreeStart", $dbQuarterThreeStart);
            generateFormInputDiv("Quarter Three End Date", "date", "quarterThreeEnd", $dbQuarterThreeEnd);
            generateFormInputDiv("Quarter Four Start Date", "date", "quarterFourStart", $quarterFourStart);
            generateFormInputDiv("Quarter Four End Date", "date", "quarterFourEnd", $quarterFourEnd);
            generateFormButton("modifySchoolYearButton", "Modify School Year");
        generateFormEnd();
    }
    else
    {
        echo "School year does not exist for " . date("Y") . ", Please add School Year";
        return;
    }
}
?>
