<?php

function displayPanelHeading($message = NULL)
{
	if (isset($_SESSION['fail']))
    {
    	echo $_SESSION['fail'];
        unset($_SESSION['fail']);
    }
	else if (isset($_SESSION['success']))
	{
    	echo $_SESSION['success'];
        unset($_SESSION['success']);
	}
    else if ($message != NULL)
    {
    	echo "$message";
    }   
}

?>
