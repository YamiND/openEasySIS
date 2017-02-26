<?php

function getStudentCount($parentID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT studentID FROM studentParentIDs WHERE parentID = ?"))
	{
		$stmt->bind_param('i', $parentID);
		$stmt->execute();
		$stmt->bind_result($studentID);

		if ($stmt->num_rows > 0)
		{
			return $stmt->num_rows;	
		}
		else
		{
			return 0;
		}
	}
	else
	{
		return 0;
	}
}

function getStudentID($parentID, $mysqli)
{
	if ($stmt = $mysqli->prepare("SELECT studentID FROM studentParentIDs WHERE parentID = ? LIMIT 1"))
	{
		$stmt->bind_param('i', $parentID);
		$stmt->execute();
		$stmt->bind_result($studentID);
		$stmt->store_result();

		if ($stmt->num_rows > 0)
		{
			while ($stmt->fetch())
			{
				return $studentID;
			}
		}
		else
		{
			return 0;
		}
	}
	else
	{
		return 0;
	}
}
?>
