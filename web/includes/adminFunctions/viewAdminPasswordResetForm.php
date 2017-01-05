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
    echo '  
            <!-- /.col-lg-4 -->
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
		  ';
						// Call Session Message code and Panel Heading here
                        displayPanelHeading("Reset Password");
    echo '
                        </div>
                        <div class="panel-body">
        ';
                        getAdminPasswordResetForm();
    echo '
                        </div>
                    </div>  
                </div>  
            <!-- /.col-lg-4 -->
        ';
}

function getAdminPasswordResetForm()
{
    generateFormStart("../includes/adminFunctions/adminPasswordReset", "post"); 
        generateFormInputDiv(NULL, "email", "userEmail", NULL, NULL, NULL, NULL, "Enter User Email");
        generateFormButton("Reset Password", "submit", "btn btn-lg btn-success btn-block");
    generateFormEnd();
}

?>
