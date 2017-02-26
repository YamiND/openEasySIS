<?php

if (isset($_POST['singleChooseGrade']))
{
	$_SESSION['singleChooseGrade'] = $_POST['singleChooseGrade'];
}

if (isset($_POST['studentID']))
{
	$_SESSION['studentID'] = $_POST['studentID'];
}

if (isset($_POST['generateChoice']))
{
	$_SESSION['generateChoice'] = $_POST['generateChoice'];
}

if (isset($_POST['changeGenerate']))
{
	unset($_SESSION['generateChoice']);
	unset($_SESSION['singleChooseGrade']);
}

if (isset($_POST['changeGrade']))
{
	unset($_SESSION['singleChooseGrade']);
}

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isSchoolAdmin($mysqli) || isAdmin($mysqli)))
    {
        viewGenerateReportCardForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewGenerateReportCardForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Generate Report Card");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#generateReportCard" data-toggle="tab">Generate Report Card</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="generateReportCard">
                                    <br>
        ';

							if (!isset($_SESSION['generateChoice']))
							{
								generateChoiceForm($mysqli);
							}
							else
							{
								if ($_SESSION['generateChoice'] == "generateSingle")
								{
                                    generateSingleForm($mysqli);
								}
			
								if ($_SESSION['generateChoice'] == "generateForGrade")
								{
                                    generateForGradeForm($mysqli);
								}
								
								if ($_SESSION['generateChoice'] == "generateAll")
								{
                                    generateAllForm($mysqli);
								}

								echo "<br>";

    							generateFormStart("", "post"); 
        							generateFormButton("changeGenerate", "Choose A Different Option");
							    generateFormEnd();

							}
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

function generateSingleForm($mysqli)
{
	if (!isset($_SESSION['singleChooseGrade']))
	{
		singleChooseGradeForm($mysqli);
	}
	else if (!isset($_SESSION['studentID']))
	{
		singleChooseStudentForm($mysqli);
	}
	else
	{
		confirmStudentForm($mysqli);
	}
}

function singleChooseGradeForm($mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Select Student's Grade Level", "singleChooseGrade");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);	
            }
        generateFormEndSelectDiv();
        generateFormButton("selectGradeLevel", "Select Grade Level");
    generateFormEnd();
}

function singleChooseStudentForm($mysqli)
{
    generateFormStart("../includes/schoolAdminFunctions/generateReportCard", "post"); 
		generateFormHiddenInput("generateChoice", "generateSingle");
        generateFormStartSelectDiv("Select Student Report Card to Generate", "studentID");
			getStudentList($_SESSION['singleChooseGrade'], $mysqli);
        generateFormEndSelectDiv();
        generateFormButton("selectStudentButton", "Choose Student");
    generateFormEnd();
  
	echo "<br>";
 
	generateFormStart("", "post"); 
   		generateFormButton("changeGrade", "Choose A Different Grade");
	generateFormEnd();

}

function getStudentList($gradeLevel, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userID FROM users WHERE studentGradeLevel = ?"))
	{
		$stmt->bind_param('i', $gradeLevel);
		
		if ($stmt->execute())
		{
			$stmt->bind_result($studentID);
			$stmt->store_result();
			
			if ($stmt->num_rows > 0)
			{
				while ($stmt->fetch())
				{
	        		generateFormOption("$studentID", getUserName($studentID, $mysqli));	
				}
			}
			else
			{
	    		generateFormOption("NULL", "No students in grade", "disabled", "selected");	
			}
		}
		else
		{
	    	generateFormOption("NULL", "No students in grade", "disabled", "selected");	
		}
	}
	else
	{
    	generateFormOption("NULL", "No students in grade", "disabled", "selected");	
	}
}

function generateChoiceForm($mysqli)
{
    generateFormStart("", "post"); 
        generateFormStartSelectDiv("Choose Report Card Generation Method", "generateChoice");
        	generateFormOption("generateSingle", "Generate Single Report Card");	
        	generateFormOption("generateForGrade", "Generate Report Cards for Specific Grade");	
        	generateFormOption("generateAll", "Generate Report Cards for All Grades");	
        generateFormEndSelectDiv();
        generateFormButton("generateReportCardButton", "Choose Report Card Option");
    generateFormEnd();
}

function generateForGradeForm($mysqli)
{
    generateFormStart("../includes/schoolAdminFunctions/generateReportCard", "post"); 
	generateFormHiddenInput("generateChoice", "generateForGrade");
        generateFormStartSelectDiv("Generate Report Card for Grade", "gradeLevel");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);	
            }
        generateFormEndSelectDiv();
        generateFormButton("generateReportCardButton", "Generate Report Card");
    generateFormEnd();
}

function generateAllForm($mysqli)
{
	echo "<h3>Confirm Generation of Report Cards for All Grades </h3>";
    generateFormStart("../includes/schoolAdminFunctions/generateReportCard", "post"); 
	generateFormHiddenInput("generateChoice", "generateAll");
        generateFormButton("generateReportCardButton", "Generate Report Card for All Grades");
    generateFormEnd();
}

?>
