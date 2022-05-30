<?php

/***************************************************************
 * MISCELLANEOUS FUNCTIONS AND VARIABLES USED IN VARIOUS FILES *
 ***************************************************************/


function ask_id_form($ID = "")
{
    return "
        <form method='post' action='edit_item.php'>
            <input type='hidden' name='action' value='view'>
            <input type='text' name='id' placeholder='ID' value='$ID'>
            <input type='submit' value='Edit item'>
        </form>";
}



function show_editable_item_form($method, $action, $button_text, $item = array(), $extra = "")
{
    return "
        <form method='$method' action='$action'>
            $extra
            <input type='text' name='name' placeholder='Name' value='" . $item["name"] . "'><br>
            <textarea name='description' placeholder='Description' rows='5' cols='50'>" . $item["description"] . "</textarea><br>
            <input type='text' name='manufacturer' placeholder='Manufacturer' value='" . $item["manufacturer"] . "'><br>
            <input type='text' name='location' placeholder='Location' value='" . $item["location"] . "'><br>
            <input type='text' name='cpu_model' placeholder='CPU Model' value='" . $item["cpu_model"] . "'>
            <input type='text' name='cpu_max_freq_mhz' placeholder='CPU Max Frequency [MHz]' value='" . $item["cpu_max_freq_mhz"] . "'><br>
            <input type='text' name='ram_model' placeholder='RAM Model' value='" . $item["ram_model"] . "'>
            <input type='text' name='ram_amount_gb' placeholder='RAM Amount [GB]' value='" . $item["ram_amount_gb"] . "'><br>
            <input type='text' name='graphics_model' placeholder='Graphics card model' value='" . $item["graphics_model"] . "'><br>
            <input type='text' name='disk_model' placeholder='Disk model' value='" . $item["disk_model"] . "'>
            <input type='text' name='disk_size_gb' placeholder='Disk size [GB]' value='" . $item["disk_size_gb"] . "'><br>
            <input type='text' name='screen_diagonal_inch' placeholder='Screen diagonal [inch]' value='" . $item["screen_diagonal_inch"] . "'>
            <input type='text' name='screen_resolution' placeholder='Screen resolution' value='" . $item["screen_resolution"] . "'><br>
            <input type='submit' value='$button_text'>
        </form>";
}




function query_database($query_string)
{
    require "serverdata.php";

    try
    {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

        if ($pdo && $query_string)
            return $pdo->query($query_string);
        else
            return false;
    }
    catch (PDOException $e)
    {
        echo "<br>PDOException: " . $e->getMessage() . "<br>";

        return false;
    }
}
