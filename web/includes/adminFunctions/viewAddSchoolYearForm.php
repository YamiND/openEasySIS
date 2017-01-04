<?php

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
						if (isset($_SESSION['addSchoolYearFailed']))
                        {
                        	echo $_SESSION['addSchoolYearFailed'];
                            unset($_SESSION['addSchoolYearFailed']);
                        }
						else if (isset($_SESSION['addSchoolYearSuccess']))
						{
                        	echo $_SESSION['addSchoolYearSuccess'];
                            unset($_SESSION['addSchoolYearSuccess']);
						}
                        else
                        {
                        	echo 'Add School Year';
                        }
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
                                    <h4>Add School Year</h4>
                                    <form action="../includes/adminFunctions/addSchoolYear" method="post" role="form">
                                        <div class="form-group">
											<label>School Year Start Date</label>
											<input class="form-control" type="date" name="schoolYearStart" value="' . date('Y-m-d') . '">
                                        </div>
                                        <div class="form-group">
											<label>School Year End Date</label>
											<input class="form-control" type="date" name="schoolYearEnd">
                                        </div>
                                        <div class="form-group">
											<label>Fall Semester Start Date</label>
											<input class="form-control" type="date" name="fallSemesterStart">
                                        </div>
                                        <div class="form-group">
											<label>Fall Semester End Date</label>
											<input class="form-control" type="date" name="fallSemesterEnd">
                                        </div>
                                        <div class="form-group">
											<label>Spring Semester Start Date</label>
											<input class="form-control" type="date" name="springSemesterStart">
                                        </div>
                                        <div class="form-group">
											<label>Spring Semester End Date</label>
											<input class="form-control" type="date" name="springSemesterEnd">
                                        </div>
                                        <div class="form-group">
											<label>Quarter One Start Date</label>
											<input class="form-control" type="date" name="quarterOneStart">
                                        </div>
                                        <div class="form-group">
											<label>Quarter One End Date</label>
											<input class="form-control" type="date" name="quarterOneEnd">
                                        </div>
                                        <div class="form-group">
											<label>Quarter Two Start Date</label>
											<input class="form-control" type="date" name="quarterTwoStart">
                                        </div>
                                        <div class="form-group">
											<label>Quarter Two End Date</label>
											<input class="form-control" type="date" name="quarterTwoEnd">
                                        </div>
                                        <div class="form-group">
											<label>Quarter Three Start Date</label>
											<input class="form-control" type="date" name="quarterThreeStart">
                                        </div>
                                        <div class="form-group">
											<label>Quarter Three End Date</label>
											<input class="form-control" type="date" name="quarterThreeEnd">
                                        </div>
                                        <button type="submit" class="btn btn-default">Add School Year</button>
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

?>
