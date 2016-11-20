<?php 

function viewAdminPasswordResetForm($mysqli)
{
    // Form for password reset via email. Used by admin
echo '  <!-- /.col-lg-4 -->
        <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
						';
						if (isset($_SESSION['invalidReset']))
                        {   
                            echo $_SESSION['invalidReset'];
                            unset($_SESSION['invalidReset']);
                        }   
                        else if (isset($_SESSION['resetSuccess']))
                        {   
                            echo $_SESSION['resetSuccess'];
                            unset($_SESSION['resetSuccess']);
                        }   
                        else
                        {   
                            echo 'Reset Password';
                        }  
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
