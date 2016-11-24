<?php

function viewCreateUserForm($mysqli)
{
echo '
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						if (isset($_SESSION['invalidCreate']))
                        {
                        	echo $_SESSION['invalidCreate'];
                            unset($_SESSION['invalidCreate']);
                        }
						else if (isset($_SESSION['createSuccess']))
						{
                        	echo $_SESSION['createSuccess'];
                            unset($_SESSION['createSuccess']);
						}
                        else
                        {
                        	echo 'Create User Account';
                        }
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
                                    <form action="../includes/adminFunctions/createUserAccount" method="post" role="form">
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
                                        <button type="submit" class="btn btn-default">Create Administrator</button>
                                    </form>

                                </div>
                                <div class="tab-pane fade" id="schoolAdmin">
                                    <h4>School Administrator</h4>
                                    <form action="../includes/adminFunctions/createUserAccount" role="form">
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
                                        <button type="submit" class="btn btn-default">Create School Admin</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="teacher">
                                    <h4>Teacher</h4>
                                    <form action="../includes/adminFunctions/createUserAccount" role="form">
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
                                        <button type="submit" class="btn btn-default">Create Teacher</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="guardian">
                                    <h4>Parent/Guardian</h4>
                                    <form action="../includes/adminFunctions/createUserAccount" role="form">
										<input type="hidden" name="roleID" value="4">
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
                                            <input class="form-control" name="guardianAltEmail" placeholder="Alternate Email">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianAddress" placeholder="Address">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianCity" placeholder="City">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianState" placeholder="State">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="guardianZip" placeholder="Zip">
                                        </div>
                                        <button type="submit" class="btn btn-default">Create Parent/Guardian</button>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="student">
                                    <h4>Student</h4>
                                    <form action="../includes/adminFunctions/createUserAccount" role="form">
										<input type="hidden" name="roleID" value="5">
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
                                            <input class="form-control" name="studentGender" placeholder="Gender">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" name="studentGradeLevel" placeholder="Grade Level">
                                        </div>
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
