<?php require "layout.php" ?>


<?php echo $pre_title_boilerplate ?>

<h1>Change an item</h1>

<?php echo $pre_content_boilerplate ?>

<?php require("serverdata.php"); ?>


<?php
/**********************************
 * ASK FOR DEVICE ID IF NOT GIVEN *
 **********************************/


if (!isset($_REQUEST["id"]))
{
    echo "
        <form method='post' action='edit_item.php'>
            <input type='hidden' name='action' value='view'>
            <input type='text' name='id' placeholder='ID'>
            <input type='submit' value='Edit item'>
        </form>";
}
?>


<?php
/****************************
 * CHANGE ITEM IF REQUESTED *
 ****************************/


function get_change_query_string()
{
    $query_string = "UPDATE devices SET ";

    if ($_REQUEST["name"] != "")
        $query_string = $query_string . "name = \"" . $_REQUEST["name"] . "\", ";
    if ($_REQUEST["description"] != "")
        $query_string = $query_string . "description = \"" . $_REQUEST["description"] . "\", ";
    if ($_REQUEST["manufacturer"] != "")
        $query_string = $query_string . "manufacturer = \"" . $_REQUEST["manufacturer"] . "\", ";
    if ($_REQUEST["location"] != "")
        $query_string = $query_string . "location = \"" . $_REQUEST["location"] . "\", ";
    if ($_REQUEST["cpu_model"] != "")
        $query_string = $query_string . "cpu_model = \"" . $_REQUEST["cpu_model"] . "\", ";
    if ($_REQUEST["cpu_max_freq_mhz"] != "")
        $query_string = $query_string . "cpu_max_freq_mhz = " . $_REQUEST["cpu_max_freq_mhz"] . ", ";
    if ($_REQUEST["ram_model"] != "")
        $query_string = $query_string . "ram_model = \"" . $_REQUEST["ram_model"] . "\", ";
    if ($_REQUEST["ram_amount_gb"] != "")
        $query_string = $query_string . "ram_amount_gb = " . $_REQUEST["ram_amount_gb"] . ", ";
    if ($_REQUEST["graphics_model"] != "")
        $query_string = $query_string . "graphics_model = \"" . $_REQUEST["graphics_model"] . "\", ";
    if ($_REQUEST["disk_model"] != "")
        $query_string = $query_string . "disk_model = \"" . $_REQUEST["disk_model"] . "\", ";
    if ($_REQUEST["disk_size_gb"] != "")
        $query_string = $query_string . "disk_size_gb = " . $_REQUEST["disk_size_gb"] . ", ";
    if ($_REQUEST["screen_diagonal_inch"] != "")
        $query_string = $query_string . "screen_diagonal_inch = " . $_REQUEST["screen_diagonal_inch"] . ", ";
    if ($_REQUEST["screen_resolution"] != "")
        $query_string = $query_string . "screen_resolution = \"" . $_REQUEST["screen_resolution"] . "\", ";

    $query_string = substr($query_string, 0, strlen($query_string) - 2);
    $query_string = $query_string . " WHERE id = " . $_REQUEST["id"];

    return $query_string;
}


if ($_REQUEST["action"] == "edit" && isset($_REQUEST["id"]))
{
    try
    {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

        $query_string = get_change_query_string();

        if ($query_string)
            $pdo->query($query_string);
    }
    catch (PDOException $e)
    {
        echo "PDOException: " . $e->getMessage();
    }
}
?>


<?php
/*******************************
 * DISPLAY AN ITEM IF ID GIVEN * 
 *******************************/


function get_select_query_string($device_id)
{
    $query_string = "SELECT * FROM devices WHERE id = " . $device_id;

    return $query_string;
}


if (($_REQUEST["action"] == "view" || $_REQUEST["action"] == "edit") && isset($_REQUEST["id"]))
{
    try
    {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

        $query_string = get_select_query_string($_REQUEST["id"]);

        $query_result_pdo = $pdo->query($query_string);

        if ($query_result_pdo)
            $item = $query_result_pdo->fetch();
    }
    catch (PDOException $e)
    {
        echo "PDOException: " . $e->getMessage();
    }


    if ($item)
    {
        echo "
            Device ID: " . $item["id"] . "<br>
            <form method='post' action='edit_item.php'>
                <input type='hidden' name='action' value='edit'>
                <input type='hidden' name='id' value='" . $item["id"] . "'>
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
                <input type='submit' value='Submit changes'>
            </form>
            <form method='post' action='edit_item.php'>
                <input type='hidden' name='action' value='delete'>
                <input type='hidden' name='id' value='" . $item["id"] . "'>
                <input type='submit' value='Delete item'>
            </form><br>";
    }
    else
    {
        echo "
            Device with that id not found.
            <form method='post' action='edit_item.php'>
                <input type='hidden' name='action' value='view'>
                <input type='text' name='id' placeholder='ID' value='" . $_REQUEST["id"] . "'>
                <input type='submit' value='Edit item'>
            </form>";
    }
}
?>


<?php
/*****************************************
 * ASK FOR CONFIRMATION OF ITEM DELETION *
 *****************************************/

if (isset($_REQUEST["id"]) && $_REQUEST["action"] == "delete")
{
    echo "
        Are you sure you want to delete item with ID " . $_REQUEST["id"] . "?<br>
        <form method='post' action='edit_item.php'>
            <input type='hidden' name='action' value='delete_confirmed'>
            <input type='hidden' name='id' value='" . $_REQUEST["id"] . "'>
            <input type='submit' value='Yes, I am'>
        </form>
        <form method='post' action='edit_item.php'>
            <input type='hidden' name='action' value='view'>
            <input type='hidden' name='id' value='" . $_REQUEST["id"] . "'>
            <input type='submit' value='No, I am not'>
        </form>
        ";
}
?>


<?php
/****************************
 * DELETE ITEM IF CONFIRMED *
 ****************************/


function get_delete_query_string()
{
    $query_string = "DELETE FROM devices WHERE id = " . $_REQUEST["id"];

    return $query_string;
}


if (isset($_REQUEST["id"]) && $_REQUEST["action"] == "delete_confirmed")
{
    try
    {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

        $query_string = get_delete_query_string();

        if ($query_string)
            $pdo->query($query_string);
    }
    catch (PDOException $e)
    {
        echo "PDOException: " . $e->getMessage();
    }


    echo "
        Item (hopefully) deleted.
        <form method='post' action='edit_item.php'>
            <input type='hidden' name='action' value='view'>
            <input type='text' name='id' placeholder='ID'>
            <input type='submit' value='Edit item'>
        </form>";
}
?>


<?php echo $post_content_boilerplate ?>