<?php require "layout.php" ?>
<?php require "util.php" ?>


<?php echo $pre_title_boilerplate ?>

<h1>Add an item</h1>

<?php echo $pre_content_boilerplate ?>

<?php

/*********************
 * ADD ITEM IF GIVEN *
 *********************/


function get_insert_query()
{
    $key_string = "name";
    $value_string = "?";

    $query_params = array($_REQUEST["name"]);

    foreach ($_REQUEST as $key => $value)
    {
        if (is_valid_key($key) && $value != "" && $key != "name")
        {
            $key_string = $key_string . ", " . $key;
            $value_string = $value_string . ", ?";

            array_push($query_params, $value);
        }
    }

    return array("string" => "INSERT INTO devices ( $key_string ) VALUES ( $value_string )", "params" => $query_params);
}


if ($_REQUEST["name"])
{
    $query_result_pdo = query_database(get_insert_query());

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