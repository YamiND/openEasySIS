<?php

function getStudentGradeByID($studentID, $mysqli)
{
	 if ($stmt = $mysqli->prepare("SELECT studentGradeLevel FROM users WHERE userID = ?"))
    {
        $stmt->bind_param('i', $studentID);
        $stmt->execute();
        $stmt->bind_result($studentGradeLevel);
        $stmt->store_result();

        while($stmt->fetch())
        {   
			return "$studentGradeLevel";
        }       
    }       
    else
    {   
        return;
    } 	
}

?>
