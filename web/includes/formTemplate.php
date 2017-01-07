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

function generateFormOption($value, $name, $disabled = NULL, $selected = NULL)
{
    echo "<option " . $disabled . " " . $selected . " value='" . $value . "'>$name</option>";
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

function generateFormCheckboxDiv($checked = NULL, $name = NULL, $value = NULL, $field = NULL)
{
    echo '
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" ' . $checked . ' name="' . $name .'" value="' . $value .'"> ' . $field . '
                </label>
            </div>
        </div>';
}

function generateFormHiddenInput($name = NULL, $value = NULL)
{
    echo '<input type="hidden" name="' . $name . '" value="'. $value .'">';
}

function generateFormInput($type = "text", $name = NULL, $value = NULL, $disabled = NULL, $min = NULL, $max = NULL, $placeholder = NULL, $size = NULL)
{
    switch($type)
    {
        case "date":
               echo  '<input class="form-control" type="' . $type . '" name="' . $name . '" value="' . $value . '" min="' . $min . '" max="' . $max . '" ' . $disabled . '>';
            break;

        default:
            echo  '<input class="form-control" type="' . $type . '" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '"' . $disabled . '>';
            break;

    }
}

function generateFormInputDiv($label = NULL, $type = "text", $name = NULL, $value = NULL, $disabled = NULL, $min = NULL, $max = NULL, $placeholder = NULL, $size = NULL)
{
    echo '
            <div class="form-group">
        ';

        if ($label != NULL)
        {
            echo '<label>' . $label .'</label>';
        }

        switch($type)
        {
        	case "date":
		           echo  '<input class="form-control" type="' . $type . '" name="' . $name . '" value="' . $value . '" min="' . $min . '" max="' . $max . '" ' . $disabled . '>';
        		break;

        	default:
        		echo  '<input class="form-control" type="' . $type . '" placeholder="' . $placeholder . '" name="' . $name . '" value="' . $value . '"' . $disabled . '>';
        		break;

        }

    echo '
            </div>
        ';
}

function generateFormTextAreaDiv($label = NULL, $name = NULL, $rows = "5", $value = NULL, $disabled = NULL)
{
	echo '
            <div class="form-group">
        ';

    if ($label != NULL)
    {
        echo '<label>' . $label .'</label>';
    }

    echo '<textarea class="form-control" name="' . $name . '" rows="' . $rows .'" ' . $disabled . '>'. $value .'</textarea>';

    echo '
            </div>
        ';
}
function generateFormButton($name = NULL, $value = "NULL", $type = "submit", $class = "btn btn-default")
{
	echo '
			<button name="' . $name . '" type="' . $type . '" class="' . $class . '">' . $value .'</button>
		';
}

?>
