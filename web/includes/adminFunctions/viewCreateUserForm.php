<?php

function viewCreateUserForm($mysqli)
{
	echo '
<!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Create User Account
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="User\'s Email">
                                        </div>
                                        <div class="form-group">
                                            <label>Select Role</label>
                                            <select class="form-control">
											';
											getUserRoles($mysqli);
echo '
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-default">Create User</button>
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
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
