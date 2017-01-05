<?php 

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 1))
    {
        viewAdminPasswordResetForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
    }
}

function viewAdminPasswordResetForm($mysqli)
{
    // Form for password reset via email. Used by admin
echo '  <!-- /.col-lg-4 -->
        <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
						';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Reset Password");
echo '
                        </div>
                        <div class="panel-body">
            <form action="../includes/adminFunctions/adminPasswordReset" method="post" name="login_form" role="form">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Enter User Email" name="userEmail" type="text" autofocus>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->                 
                                <input type="submit" class="btn btn-lg btn-success btn-block"                       
                                                   value="Reset Password"/>
                            </fieldset>
                        </form> 
                        </div>
                    </div>  
                </div>  
                <!-- /.col-lg-4 -->';
}

?>
