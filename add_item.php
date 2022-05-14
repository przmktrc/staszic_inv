<?php require "layout.php" ?>


<?php echo $pre_title_boilerplate ?>

<h1>Add an item</h1>

<?php echo $pre_content_boilerplate ?>

<?php

/*********************
 * ADD ITEM IF GIVEN *
 *********************/

require("serverdata.php");


function get_query_string()
{
    $key_string = "name";
    $value_string = "\"" . $_REQUEST["name"] . "\"";

    if ($_REQUEST["description"] != "")
    {
        $key_string = $key_string . ", description";
        $value_string = $value_string . ", \"" . $_REQUEST["description"] . "\"";
    }
    if ($_REQUEST["manufacturer"] != "")
    {
        $key_string = $key_string . ", manufacturer";
        $value_string = $value_string . ", \"" . $_REQUEST["manufacturer"] . "\"";
    }
    if ($_REQUEST["location"] != "")
    {
        $key_string = $key_string . ", location";
        $value_string = $value_string . ", \"" . $_REQUEST["location"] . "\"";
    }
    if ($_REQUEST["cpu_model"] != "")
    {
        $key_string = $key_string . ", cpu_model";
        $value_string = $value_string . ", \"" . $_REQUEST["cpu_model"] . "\"";
    }
    if ($_REQUEST["cpu_max_freq_mhz"] != "")
    {
        $key_string = $key_string . ", cpu_max_freq_mhz";
        $value_string = $value_string . ", " . $_REQUEST["cpu_max_freq_mhz"];
    }
    if ($_REQUEST["ram_model"] != "")
    {
        $key_string = $key_string . ", ram_model";
        $value_string = $value_string . ", \"" . $_REQUEST["ram_model"] . "\"";
    }
    if ($_REQUEST["ram_amount_gb"] != "")
    {
        $key_string = $key_string . ", ram_amount_gb";
        $value_string = $value_string . ", " . $_REQUEST["ram_amount_gb"];
    }
    if ($_REQUEST["graphics_model"] != "")
    {
        $key_string = $key_string . ", graphics_model";
        $value_string = $value_string . ", \"" . $_REQUEST["graphics_model"] . "\"";
    }
    if ($_REQUEST["disk_model"] != "")
    {
        $key_string = $key_string . ", disk_model";
        $value_string = $value_string . ", \"" . $_REQUEST["disk_model"] . "\"";
    }
    if ($_REQUEST["disk_size_gb"] != "")
    {
        $key_string = $key_string . ", disk_size_gb";
        $value_string = $value_string . ", " . $_REQUEST["disk_size_gb"];
    }
    if ($_REQUEST["screen_diagonal_inch"] != "")
    {
        $key_string = $key_string . ", screen_diagonal_inch";
        $value_string = $value_string . ", " . $_REQUEST["screen_diagonal_inch"];
    }
    if ($_REQUEST["screen_resolution"] != "")
    {
        $key_string = $key_string . ", screen_resolution";
        $value_string = $value_string . ", \"" . $_REQUEST["screen_resolution"] . "\"";
    }

    return "INSERT INTO devices (" . $key_string . ") VALUES (" . $value_string . ")";
}


if ($_REQUEST["name"])
{
    try
    {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

        if ($pdo)
            echo "Connected to database successfully.<br>";
        else
            echo "Failed to connect to database but no exception was thrown.<br>";

        $query_string = get_query_string();
        echo "query_string: " . $query_string . "<br>";

        if ($query_string)
            $query_result_pdo = $pdo->query($query_string);
    }
    catch (PDOException $e)
    {
        echo "PDOException: " . $e->getMessage();
    }
}

?>


<?php
/****************************
 * DISPLAY FORM TO ADD ITEM *
 ****************************/

echo "
    <form method='post' action='add_item.php'>
        <input type='text' name='name' placeholder='Name'><br>
        <textarea name='description' placeholder='Description' rows='5' cols='50'></textarea><br>
        <input type='text' name='manufacturer' placeholder='Manufacturer'><br>
        <input type='text' name='location' placeholder='Location'><br>
        <input type='text' name='cpu_model' placeholder='CPU Model'>
        <input type='text' name='cpu_max_freq_mhz' placeholder='CPU Max Frequency [MHz]'><br>
        <input type='text' name='ram_model' placeholder='RAM Model'>
        <input type='text' name='ram_amount_gb' placeholder='RAM Amount [GB]'><br>
        <input type='text' name='graphics_model' placeholder='Graphics card model'><br>
        <input type='text' name='disk_model' placeholder='Disk model'>
        <input type='text' name='disk_size_gb' placeholder='Disk size [GB]'><br>
        <input type='text' name='screen_diagonal_inch' placeholder='Screen diagonal [inch]'>
        <input type='text' name='screen_resolution' placeholder='Screen resolution'><br>
        <input type='submit' value='Add item'>
    </form><br>";
?>


<?php echo $post_content_boilerplate ?>