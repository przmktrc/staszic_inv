<?php

/***************************************************************
 * MISCELLANEOUS FUNCTIONS AND VARIABLES USED IN VARIOUS FILES *
 ***************************************************************/


function ask_id_form($ID = "")
{
    return "
        <form method='post' action='edit_item.php'>
            <input   type='hidden'   name='action'                  value='view'>
            <input   type='text'     name='id'   placeholder='ID'   value='" . sanitize($ID) . "'>
            <input   type='submit'   value='Edit item'>
        </form>";
}



function show_editable_item_form($method, $action, $button_text, $item = array(), $extra = "")
{
    return "
        <form method='$method' action='$action'>
            $extra
            <input   type='text' name='name'                   placeholder='Name'                      value='" . sanitize($item["name"])                   . "'><br>
            <textarea name='description'                       placeholder ='Description' rows='5' cols='50'>"  . sanitize($item["description"])            . "</textarea><br>
            <input   type='text' name='manufacturer'           placeholder='Manufacturer'              value='" . sanitize($item["manufacturer"])           . "'><br>
            <input   type='text' name='location'               placeholder='Location'                  value='" . sanitize($item["location"])               . "'><br>
            <input   type='text' name='cpu_model'              placeholder='CPU Model'                 value='" . sanitize($item["cpu_model"])              . "'>
            <input   type='text' name='cpu_max_freq_mhz'       placeholder='CPU Max Frequency [MHz]'   value='" . sanitize($item["cpu_max_freq_mhz"])       . "'><br>
            <input   type='text' name='ram_model'              placeholder='RAM Model'                 value='" . sanitize($item["ram_model"])              . "'>
            <input   type='text' name='ram_amount_gb'          placeholder='RAM Amount [GB]'           value='" . sanitize($item["ram_amount_gb"])          . "'><br>
            <input   type='text' name='graphics_model'         placeholder='Graphics card model'       value='" . sanitize($item["graphics_model"])         . "'><br>
            <input   type='text' name='disk_model'             placeholder='Disk model'                value='" . sanitize($item["disk_model"])             . "'>
            <input   type='text' name='disk_size_gb'           placeholder='Disk size [GB]'            value='" . sanitize($item["disk_size_gb"])           . "'><br>
            <input   type='text' name='screen_diagonal_inch'   placeholder='Screen diagonal [inch]'    value='" . sanitize($item["screen_diagonal_inch"])   . "'>
            <input   type='text' name='screen_resolution'      placeholder='Screen resolution'         value='" . sanitize($item["screen_resolution"])      . "'><br>
            <input   type='submit' value='$button_text'>
        </form>";
}




function query_database($query)
{
    require "serverdata.php";

    try
    {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

        if ($pdo && $query["string"])
        {
            $pdo_statement = $pdo->prepare($query["string"]);

            if ($pdo_statement->execute($query["params"]))
                return $pdo_statement;
            else
                return false;
        }
        else
            return false;
    }
    catch (PDOException $e)
    {
        echo "<br>PDOException: " . $e->getMessage() . "<br>";

        return false;
    }
}



function is_valid_key($key)
{
    return $key == "id" || $key == "name" || $key == "description" || $key == "manufacturer"
        || $key == "location" || $key == "cpu_model" || $key == "cpu_max_freq_mhz"
        || $key == "ram_model" || $key == "ram_amount_gb" || $key == "graphics_model"
        || $key == "disk_model" || $key == "disk_size_gb" || $key == "screen_diagonal_inch"
        || $key == "screen_resolution";
}



function is_valid_sortby($word)
{
    return strtolower($word) == 'asc' || strtolower($word) == 'desc';
}



function sanitize($string)
{
    return htmlspecialchars($string);
}
