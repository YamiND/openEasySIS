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
						displayPanelHeading("Export All Data (SQL)");
    echo '
                        </div>
                        <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#addAssignment" data-toggle="tab">Export All Data (SQL)</a>
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
	echo "<label>Export All data to SQL File</label>";
	echo "<label>This will include everything in the database, so this feature is meant only for programmers trying to reverse engineer the DB/Data</label>";
	echo "<label>(Really this shouldn't be too hard to re-import into a database after exported. We are using MariaDB if that helps you)</label>";

	generateFormStart("../includes/adminFunctions/exportSQL", "post");
		generateFormButton(NULL, "Export ALL Data (SQL)");
	generateFormEnd();
}

?>
