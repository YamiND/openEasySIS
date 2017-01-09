<?php

function checkPermissions($mysqli)
{
    if ((login_check($mysqli) == true) && (roleID_check($mysqli) == 3))
    {
        viewUserPasswordResetForm($mysqli);
    }
    else
    {
        $_SESSION['fail'] = 'Invalid Access, you do not have permission';
        // Call Session Message code and Panel Heading here
        displayPanelHeading();
    }
}

function viewUserPasswordResetForm($mysqli)
{
    echo ' 
            <!-- /.col-lg-4 -->
                <div class="col-lg-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
		';  
                        displayPanelHeading("Reset a Password");  
	echo '  
                        </div>
                        <div class="panel-body">

        ';
            generateFormStart("../includes/userFunctions/userPasswordReset", "post"); 
                generateFormHiddenInput("userEmail", $_SESSION["userEmail"]);
                generateFormInputDiv("Old Password", "password", "oldPassword");
                generateFormInputDiv("New Password", "password", "newPassword");
                generateFormInputDiv("Repeat New Password", "password", "repeatPassword");
                generateFormButton("resetPasswordButton", "Reset Password");
            generateFormEnd();
    echo '
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-4 -->
        ';
}

?>
