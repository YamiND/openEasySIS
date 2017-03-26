<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (isAdmin($mysqli)))
    {
        viewExportAllDataForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewExportAllDataForm($mysqli)
{
	echo '
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
	';
						displayPanelHeading("Export All Data");
    echo '
                        </div>
                        <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#addAssignment" data-toggle="tab">Export All Data</a>
                            </li>
                        </ul>
                        <!-- /.panel-heading -->
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="addAssignment">
                                
        ';

                        exportAllDataForm($mysqli);
    echo '              
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.panel -->
            </div>
        </div>
    ';

}

function exportAllDataForm($mysqli)
{
	echo "<br>";
	echo "<label>Export ALL data</label>";
	echo "<label>This will include everything in the database, so this feature is meant only for programmers trying to reverse engineer the DB/Data</label>";

	generateFormStart("../includes/adminFunctions/exportAllData", "post");
		generateFormButton(NULL, "Export ALL Data");
	generateFormEnd();
}

?>
