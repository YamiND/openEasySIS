<?php

function getClassYearID($mysqli)
{
    if ($stmt = $mysqli->prepare("SELECT schoolYearID FROM schoolYear WHERE schoolYearStart <= CURDATE() AND schoolYearEnd >= CURDATE()"))
    {
        $stmt->execute();
        $stmt->bind_result($schoolYearID);
        $stmt->store_result();

        if ($stmt->num_rows > 0)
        {
            while ($stmt->fetch())
            {
                return $schoolYearID;
            }
        }
        else
        {
            $_SESSION['fail'] = 'Class could not be added, you need to set a school year for this current year';
            header('Location: ../../pages/addClass');
        }
    }
}

?>
