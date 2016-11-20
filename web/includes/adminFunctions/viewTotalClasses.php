<?php

function viewTotalClasses($mysqli)
{
	// This function generates the HTML to show the number of users.
	// $userGroup is the name of the the type of user
	// $profileName is the table in the database

	echo ' 
               <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-university fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">' . getTotalCLasses($mysqli) . ' </div>
                                    <div>Classes</div>
                                </div>
                            </div>
                        </div>
                        <a href="viewClassesTable">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
	';
}

function getTotalClasses($mysqli)
{
    // This function gets the number of classes
        if ($stmt = $mysqli->prepare("SELECT * FROM classes"))
        {
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0)
            {
                return $stmt->num_rows;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
}
?>
