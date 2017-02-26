<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewCreateUserForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}


function viewCreateUserForm($mysqli)
{

    echo '
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Create User");
echo '
                        <div id="Create" class="tabcontent">
 <div id="Checkboxes1">
  <input type="checkbox" id="schoolAdmin">
  <label for="Checkbox1">School Admin</label>
  <input type="checkbox" id="administrator">
  <label for="Checkbox2">Admin</label>
  <input type="checkbox" id="teacher">
  <label for="Checkbox3">Teacher</label>
  <input type="checkbox" id="parent">
  <label for="Checkbox3">Parent</label>
  <input type="checkbox" id="student">
  <label for="Checkbox3">Student</label>
<script type="text/javascript">
$(function() {
	$( "#Checkboxes1" ).buttonset(); 
});
</script>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="administrator">
                                    <br>
            ';
                                    createAdminForm();
                                    
    echo '
                                </div>
                                <div class="tab-pane fade" id="schoolAdmin">
                                <br>
        ';
                                    createSchoolAdminForm();
        echo '
                                </div>
                                <div class="tab-pane fade" id="teacher">
                                <br>
            ';
                                    createTeacherForm();
        echo '
                                </div>
                                <div class="tab-pane fade" id="parent">
                                <br>
            ';
                                    createParentForm();
        echo '
                                    
                                </div>
                                <div class="tab-pane fade" id="student">
                                <br>
            ';
                                    createStudentForm();
        echo '
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
			</div>
			</div>
			</div>


';

}

function createAdminForm()
{
    generateFormStart("../includes/adminFunctions/createUser", "post"); 
        generateFormHiddenInput("roleID", "1");
        generateFormInputDiv(NULL, "email", "adminEmail", NULL, NULL, NULL, NULL, "Email");
        generateFormInputDiv(NULL, "text", "adminFirstName", NULL, NULL, NULL, NULL, "First Name");
        generateFormInputDiv(NULL, "text", "adminLastName", NULL, NULL, NULL, NULL, "Last Name");
        generateFormCheckboxDiv("checked", "modProfile", "modProfile", "Modify Profiles");
        generateFormCheckboxDiv("checked", "modClassList", "modClassList", "Modify Class Lists");
        generateFormCheckboxDiv("checked", "viewAllGrades", "viewAllGrades", "View All Grades");
        generateFormButton(NULL, "Create Administrator");
    generateFormEnd();
}

function createSchoolAdminForm()
{
    generateFormStart("../includes/adminFunctions/createUser", "post"); 
        generateFormHiddenInput("roleID", "2");
        generateFormInputDiv(NULL, "email", "schoolAdminEmail", NULL, NULL, NULL, NULL, "Email");
        generateFormInputDiv(NULL, "text", "schoolAdminFirstName", NULL, NULL, NULL, NULL, "First Name");
        generateFormInputDiv(NULL, "text", "schoolAdminLastName", NULL, NULL, NULL, NULL, "Last Name");
        generateFormCheckboxDiv(NULL, "modProfile", "modProfile", "Modify Profiles");
        generateFormCheckboxDiv(NULL, "modClassList", "modClassList", "Modify Class Lists");
        generateFormCheckboxDiv(NULL, "viewAllGrades", "viewAllGrades", "View All Grades");
        generateFormButton(NULL, "Create School Administrator");
    generateFormEnd();
}

function createTeacherForm()
{
    generateFormStart("../includes/adminFunctions/createUser", "post"); 
        generateFormHiddenInput("roleID", "3");
        generateFormInputDiv(NULL, "email", "teacherEmail", NULL, NULL, NULL, NULL, "Email");
        generateFormInputDiv(NULL, "text", "teacherFirstName", NULL, NULL, NULL, NULL, "First Name");
        generateFormInputDiv(NULL, "text", "teacherLastName", NULL, NULL, NULL, NULL, "Last Name");
        generateFormCheckboxDiv(NULL, "modProfile", "modProfile", "Modify Profiles");
        generateFormCheckboxDiv(NULL, "modClassList", "modClassList", "Modify Class Lists");
        generateFormCheckboxDiv(NULL, "viewAllGrades", "viewAllGrades", "View All Grades");
        generateFormButton(NULL, "Create Teacher");
    generateFormEnd();
}

function createParentForm()
{
    generateFormStart("../includes/adminFunctions/createUser", "post"); 
        generateFormHiddenInput("roleID", "4");
        generateFormHiddenInput("modeProfile", "0");
        generateFormHiddenInput("modClassList", "0");
        generateFormHiddenInput("viewAllGrades", "0");
        generateFormInputDiv(NULL, "email", "parentEmail", NULL, NULL, NULL, NULL, "Email");
        generateFormInputDiv(NULL, "text", "parentFirstName", NULL, NULL, NULL, NULL, "First Name");
        generateFormInputDiv(NULL, "text", "parentLastName", NULL, NULL, NULL, NULL, "Last Name");
        generateFormInputDiv(NULL, "text", "parentAddress", NULL, NULL, NULL, NULL, "Address");
        generateFormInputDiv(NULL, "text", "parentyCity", NULL, NULL, NULL, NULL, "City");
        generateFormStartSelectDiv(NULL, "parentState");
            // There is no good way to do this that I know...
            echo '
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
                ';
        generateFormEndSelectDiv();
        generateFormButton(NULL, "Create Parent");
    generateFormEnd();
}

function createStudentForm()
{
    generateFormStart("../includes/adminFunctions/createUser", "post"); 
        generateFormHiddenInput("roleID", "5");
        generateFormHiddenInput("modProfile", "0");
        generateFormHiddenInput("modClassList", "0");
        generateFormHiddenInput("viewAllGrades", "0");
        generateFormInputDiv(NULL, "email", "studentEmail", NULL, NULL, NULL, NULL, "Email");
        generateFormInputDiv(NULL, "text", "studentFirstName", NULL, NULL, NULL, NULL, "First Name");
        generateFormInputDiv(NULL, "text", "studentLastName", NULL, NULL, NULL, NULL, "Last Name");
        generateFormStartSelectDiv("Gender", "studentGender");
            generateFormOption("M", "Male");
            generateFormOption("F", "Female");
        generateFormEndSelectDiv();
        generateFormStartSelectDiv("Grade Level", "studentGradeLevel");
            for ($i = 1; $i <= 12; $i++)
            {
                generateFormOption($i, $i);
            }
        generateFormEndSelectDiv();
        generateFormButton(NULL, "Create Student");
    generateFormEnd();
}

?>
