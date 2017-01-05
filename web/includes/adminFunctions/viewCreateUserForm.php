<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewCreateUserForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
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
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#administrator" data-toggle="tab">Administrator</a>
                                </li>
                                <li><a href="#schoolAdmin" data-toggle="tab">School Administrator</a>
                                </li>
                                <li><a href="#teacher" data-toggle="tab">Teacher</a>
                                </li>
                                <li><a href="#guardian" data-toggle="tab">Parent/Guardian</a>
                                </li>
                                <li><a href="#student" data-toggle="tab">Student</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="administrator">
                                    <h4>Create Administrator Account</h4>
                                    <form action="../includes/adminFunctions/createUser" method="post" role="form">
										<input type="hidden" name="roleID" value="1">
                                        <div class="form-group">
                                            <input class="form-control" name="adminEmail" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="adminFirstName" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="adminLastName" placeholder="Last Name">
                                        </div>
										<div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" checked="checked" name="modProfile" value="modProfile">Modify Profiles
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" checked="checked" name="modClassList" value="modClassList">Modify Class Lists
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" checked="checked" name="viewAllGrades" value="viewAllGrades">View all Grades
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-default">Create Administrator</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="schoolAdmin">
                                    <h4>School Administrator</h4>
                                    <form action="../includes/adminFunctions/createUser" method="post" role="form">
										<input type="hidden" name="roleID" value="2">
                                        <div class="form-group">
                                            <input class="form-control" name="schoolAdminEmail" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="schoolAdminFirstName" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="schoolAdminLastName" placeholder="Last Name">
                                        </div>
										<div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="modProfile" value="modProfile">Modify Profiles
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="modClassList" value="modClassList">Modify Class Lists
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="viewAllGrades" value="viewAllGrades">View all Grades
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-default">Create School Admin</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="teacher">
                                    <h4>Teacher</h4>
                                    <form action="../includes/adminFunctions/createUser" method="post" role="form">
										<input type="hidden" name="roleID" value="3">
                                        <div class="form-group">
                                            <input class="form-control" name="teacherEmail" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="teacherFirstName" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="teacherLastName" placeholder="Last Name">
                                        </div>
										<div class="form-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="modProfile" value="modProfile">Modify Profiles
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="modClassList" value="modClassList">Modify Class Lists
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="viewAllGrades" value="viewAllGrades">View all Grades
                                                </label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-default">Create Teacher</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="guardian">
                                    <h4>Parent/Guardian</h4>
                                    <form action="../includes/adminFunctions/createUser" method="post" role="form">
										<input type="hidden" name="roleID" value="4">
										<input type="hidden" name="modProfile" value="0">
										<input type="hidden" name="modClassList" value="0">
										<input type="hidden" name="viewAllGrades" value="0">
                                        <div class="form-group">
                                            <input class="form-control" name="guardianEmail" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianFirstName" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianLastName" placeholder="Last Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianAddress" placeholder="Address">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianCity" placeholder="City">
                                        </div>
                                        <div class="form-group">
					    					<select name="guardianState" class="form-control">
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
											</select>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianZip" placeholder="Zip">
                                        </div>
                                        <button type="submit" class="btn btn-default">Create Parent/Guardian</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="student">
                                    <h4>Student</h4>
                                    <form action="../includes/adminFunctions/createUser" method="post" role="form">
										<input type="hidden" name="roleID" value="5">
										<input type="hidden" name="modProfile" value="0">
										<input type="hidden" name="modClassList" value="0">
										<input type="hidden" name="viewAllGrades" value="0">
                                        <div class="form-group">
                                            <input class="form-control" name="studentEmail" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="studentFirstName" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="studentLastName" placeholder="Last Name">
                                        </div>
                                        <div class="form-group">
					    					<select name="studentGender" class="form-control">
												<option value="M">Male</option>
												<option value="F">Female</option>
											</select>
                                        </div>
                        ';
                                        getGradeLevel();
                        echo '
                                        
                                        <button type="submit" class="btn btn-default">Create Student</button>
                                    </form>
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

function getGradeLevel()
{
    echo '
            <div class="form-group">
                <label>Class Grade Level</label>
                <select class="form-control" name="studentGradeLevel">
        ';
            for ($i = 1; $i <= 12; $i++)
            {
                echo "<option value='" . $i . "'>$i</option>";
            }
    echo '
                </select>
            </div>
        ';
}

function getUserRoles($mysqli)
{
    // This function gets the number of classes
        if ($stmt = $mysqli->prepare("SELECT roleName FROM roles"))
        {
            $stmt->execute();
			$stmt->bind_result($roleName);
            $stmt->store_result();
			
			while($stmt->fetch())
			{
				echo "<option>$roleName</option>";
			}
        }
        else
        {
            return 0;
        }
}
?>
