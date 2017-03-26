<?php

function getUserName($userID, $mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT userFirstName, userLastName FROM users WHERE userID = ?"))
    {   
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->bind_result($userFirstName, $userLastName);
        $stmt->store_result();

        while($stmt->fetch())
        {   
            return "$userLastName, $userFirstName";
        }   
    }   
    else
    {   
        return;
    }   
}

function getUserEmail($userID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT userEmail FROM users WHERE userID = ?"))
	{
		$stmt->bind_param('i', $userID);

		if ($stmt->execute())
		{
			$stmt->bind_result($userEmail);
			$stmt->store_result();

			$stmt->fetch();

			return "$userEmail";
		}
	}
}

?>
