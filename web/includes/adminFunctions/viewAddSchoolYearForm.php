<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewAddSchoolYearForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewAddSchoolYearForm($mysqli)
{
	//This is required otherwise it defaults to UTC I think
	date_default_timezone_set('America/New_York');
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Add School Year");
    echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#addSchoolYear" data-toggle="tab">Add School Year</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="addSchoolYear">
        ';
                                getAddSchoolYearForm();
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

function getAddSchoolYearForm()
{
    generateFormStart("../includes/adminFunctions/addSchoolYear", "post");
        generateFormInputDiv("School Year Start Date", "date", "schoolYearStart");
        generateFormInputDiv("School Year End Date", "date", "schoolYearEnd");
        generateFormInputDiv("Fall Semester Start Date", "date", "fallSemesterStart");
        generateFormInputDiv("Fall Semester End Date", "date", "fallSemesterEnd");
        generateFormInputDiv("Spring Semester Start Date", "date", "springSemesterStart");
        generateFormInputDiv("Spring Semester End Date", "date", "springSemesterEnd");
        generateFormInputDiv("Quarter One Start Date", "date", "quarterOneStart");
        generateFormInputDiv("Quarter One End Date", "date", "quarterOneEnd");
        generateFormInputDiv("Quarter Two Start Date", "date", "quarterTwoStart");
        generateFormInputDiv("Quarter Two End Date", "date", "quarterTwoEnd");
        generateFormInputDiv("Quarter Three Start Date", "date", "quarterThreeStart");
        generateFormInputDiv("Quarter Three End Date", "date", "quarterThreeEnd");
        generateFormInputDiv("Quarter Four Start Date", "date", "quarterFourStart");
        generateFormInputDiv("Quarter Four End Date", "date", "quarterFourEnd");
        generateFormButton("addSchoolYearButton", "Add School Year");
    generateFormEnd();
}

?>
