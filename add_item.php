<?php require "layout.php" ?>
<?php require "util.php" ?>


<?php echo $pre_title_boilerplate ?>

<h1>Add an item</h1>

<?php echo $pre_content_boilerplate ?>

<?php

/*********************
 * ADD ITEM IF GIVEN *
 *********************/


function get_query_insert_string()
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
    $query_result_pdo = query_database(get_query_insert_string());

    if ($query_result_pdo)
        echo "Item (hopefully) added.<br>";
}
else
{
    echo "Specifying name is required.<br>";
}

?>


<?php
/****************************
 * DISPLAY FORM TO ADD ITEM *
 ****************************/


echo show_editable_item_form("post", "add_item.php", "Add item", $_REQUEST) . "<br>";
?>


<?php echo $post_content_boilerplate ?>