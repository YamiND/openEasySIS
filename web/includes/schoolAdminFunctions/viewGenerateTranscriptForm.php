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
        viewGenerateTranscriptForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewGenerateTranscriptForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Generate Transcript");
echo '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#generateTranscript" data-toggle="tab">Generate Transcript</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="generateTranscript">
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
    generateFormStart("../includes/schoolAdminFunctions/generateTranscript", "post"); 
		generateFormHiddenInput("generateChoice", "generateSingle");
        generateFormStartSelectDiv("Select Student Transcript to Generate", "studentID");
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
        generateFormStartSelectDiv("Choose Transcript Generation Method", "generateChoice");
        	generateFormOption("generateSingle", "Generate Single Transcript");	
        	generateFormOption("generateForGrade", "Generate Transcripts for Specific Grade");	
        	generateFormOption("generateAll", "Generate Transcripts for All Grades");	
        generateFormEndSelectDiv();
        generateFormButton("generateTranscriptButton", "Choose Transcript Option");
    generateFormEnd();
}

function generateForGradeForm($mysqli)
{
    generateFormStart("../includes/schoolAdminFunctions/generateTranscript", "post"); 
	generateFormHiddenInput("generateChoice", "generateForGrade");
        generateFormStartSelectDiv("Generate Transcript for Grade", "gradeLevel");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);	
            }
        generateFormEndSelectDiv();
        generateFormButton("generateTranscriptButton", "Generate Transcript");
    generateFormEnd();
}

function generateAllForm($mysqli)
{
	echo "<h3>Confirm Generation of Transcripts for All Grades </h3>";
    generateFormStart("../includes/schoolAdminFunctions/generateTranscript", "post"); 
	generateFormHiddenInput("generateChoice", "generateAll");
        generateFormButton("generateTranscript", "Generate Transcript for All Grades");
    generateFormEnd();
}

?>
