<?php 

function generateUserTable($mysqli, $userGroup, $firstName, $lastName, $email, $profileName)
{
	echo '
		<!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" id="'. $userGroup . '"> 
                            ' . $userGroup . '
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="adminTable">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>';
				 getUserTableData($mysqli, $firstName, $lastName, $email, $profileName);
                               echo ' </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $(\'#' . $profileName . '\').DataTable({
            responsive: true
        });
    });
    </script>';
}

function getUserTableData($mysqli, $firstName, $lastName, $email, $profileName)
{
	if ($stmt = $mysqli->prepare("SELECT $firstName, $lastName, $email FROM $profileName"))
	{
		$stmt->execute();
		$stmt->bind_result($dbFirstName, $dbLastName, $dbEmail);
		$stmt->store_result();

		while($stmt->fetch())
		{
        		echo '<tr class="gradeA">
  			<td>' . $dbFirstName . '</td>
  			<td>' . $dbLastName . '</td>
  			<td>' . $dbEmail . '</td>
            		</tr>';
		}			
	}
	else
	{
		return;
	}
}

?>
