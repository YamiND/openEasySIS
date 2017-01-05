<?php

function generateFormStart($action = NULL, $method = NULL)
{
	echo '
		<form action="'. $action .'" method="' . $method . '" role="form">
	';
}

function generateFormEnd()
{
	echo '</form>';
}


function generateFormOption($value, $name)
{
    echo "<option value='" . $value . "'>$name</option>";
}

function generateFormStartSelectDiv($label = NULL, $name = NULL)
{
    echo '
            <div class="form-group">
        ';

        if ($label != NULL)
        {
            echo '<label>' . $label .'</label>';
        }

    echo '
        <select class="form-control" name="' . $name . '">
    ';
}

function generateFormEndSelectDiv()
{
    echo '
                </select>
            </div>
        ';
}

function generateFormInputDiv($label = NULL, $type = "text", $name = NULL, $value = NULL, $disabled = NULL)
{
    echo '
            <div class="form-group">
        ';

        if ($label != NULL)
        {
            echo '<label>' . $label .'</label>';
        }

        if ($value != NULL)
        {
           echo  '<input class="form-control" type="' . $type . '" name="' . $name . '" value="' . $value . '"' . $disabled . '>';
        }
        else
        {
            echo  '<input class="form-control" name="' . $name . '"' . $disabled . '>';
        }

    echo '
            </div>
        ';
}

?>