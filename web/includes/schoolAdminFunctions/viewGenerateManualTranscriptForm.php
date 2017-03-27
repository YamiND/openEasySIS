<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isSchoolAdmin($mysqli) || isAdmin($mysqli)))
    {
        viewGenerateManualTranscriptForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewGenerateManualTranscriptForm($mysqli)
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

                                generateManualForm($mysqli);
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

function generateManualForm($mysqli)
{
    generateFormStart("../includes/schoolAdminFunctions/generateManualTranscript", "post", "multipart/form-data"); 

echo <<<EOF
	<div class="form-group">
	   	<label>Student's First Name</label>
   		<input class="form-control" type="text" placeholder="Student's First Name" name="studentFirstName">
	  </div>
  
	<div class="form-group">
    	<label>Student's Last Name</label>
	    <input class="form-control" type="text" placeholder="Student's Last Name" name="studentLastName">
	</div>

	<div class="form-group">
    	<label>Academic Year</label>
	    <input class="form-control" type="text" placeholder="Academic Year" name="academicYear">
	</div>

	<div class="form-group">
    	<label>Student's GPA</label>
	    <input class="form-control" type="text" placeholder="Student's GPA" name="studentGPA">
	</div>

EOF;
        generateFormStartSelectDiv("Select Student's Grade Level", "studentGradeLevel");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);	
            }
        generateFormEndSelectDiv();

		echo '<label>Class CSV</label>';
		echo '<input type="file" name="csvFile" id="file" />';
		echo '<br>';

        generateFormButton(NULL, "Generate Manual Transcript");

		echo "<br>";
		echo "<br>";
	    echo "<a href=\"https://support.office.com/en-us/article/Import-or-export-text-txt-or-csv-files-5250ac4c-663c-47ce-937b-339e391393ba\">To learn how to export a file from Excel as a CSV, please click here</a>";
	   	echo "
		<br>
		<h5>The format for the Manual Transcript CSV should be this: </h5>

	    <p>&nbsp;&nbsp;&nbsp;&nbsp;Class Name,Teacher Name,Q1,Q2,Q3,Q4</p>
	   	<p>A sample CSV is listed below: </p>
	    <h5>&nbsp;&nbsp;&nbsp;&nbsp;Math 105,Torvalds, Linus,80,85,98,91</h5> 
		";
    generateFormEnd();
}

?>
