<?php require "layout.php" ?>
<?php require "util.php" ?>


<?php echo $pre_title_boilerplate ?>

<h1>Change an item</h1>

<?php echo $pre_content_boilerplate ?>


<?php /* var_dump($_REQUEST); */ ?>


<?php
/**********************************
 * ASK FOR DEVICE ID IF NOT GIVEN *
 **********************************/


if (!isset($_REQUEST["id"]))
{
    echo ask_id_form();
}
?>


<?php
/****************************
 * CHANGE ITEM IF REQUESTED *
 ****************************/


function get_change_query()
{
    $query_string = "UPDATE devices SET ";
    $query_params = array();

    foreach ($_REQUEST as $key => $value)
    {
        if (is_valid_key($key) && $key != "id" && ($key != "name" || $value != ""))
        {
            $query_string = $query_string . " " . $key . " = ?, ";
            array_push($query_params, $value);
        }
    }

    $query_string = substr($query_string, 0, strlen($query_string) - 2);
    $query_string = $query_string . " WHERE id = " . $_REQUEST["id"];

    return array("string" => $query_string, "params" => $query_params);
}


if ($_REQUEST["action"] == "edit" && isset($_REQUEST["id"]))
{
    query_database(get_change_query());
}
?>


<?php
/*******************************
 * DISPLAY AN ITEM IF ID GIVEN * 
 *******************************/


function get_select_query()
{
    return array("string" => "SELECT * FROM devices WHERE id = ?", "params" => array($_REQUEST["id"]));
}


if (($_REQUEST["action"] == "view" || $_REQUEST["action"] == "edit") && isset($_REQUEST["id"]))
{
    $query_result_pdo = query_database(get_select_query());

    if ($query_result_pdo)
        $item = $query_result_pdo->fetch();


    if ($item)
    {
        echo "
            Device ID: " . sanitize($item["id"]) . "<br>" .
            show_editable_item_form(
                "post",
                "edit_item.php",
                "Change item",
                $item,
                "
                    <input type='hidden' name='action' value='edit'>
                    <input type='hidden' name='id' value='" . sanitize($item["id"]) . "'>
                "
            ) .
            "<form method='post' action='edit_item.php'>
                <input type='hidden' name='action' value='delete'>
                <input type='hidden' name='id' value='" . sanitize($item["id"]) . "'>
                <input type='submit' value='Delete item'>
            </form><br>";
    }
    else
    {
        echo "Device with that ID not found." . ask_id_form($_REQUEST["id"]);
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
        Are you sure you want to delete item with ID " . sanitize($_REQUEST["id"]) . "?<br>
        <form method='post' action='edit_item.php'>
            <input type='hidden' name='action' value='delete_confirmed'>
            <input type='hidden' name='id' value='" . sanitize($_REQUEST["id"]) . "'>
            <input type='submit' value='Yes, I am'>
        </form>
        <form method='post' action='edit_item.php'>
            <input type='hidden' name='action' value='view'>
            <input type='hidden' name='id' value='" . sanitize($_REQUEST["id"]) . "'>
            <input type='submit' value='No, I am not'>
        </form>
        ";
}
?>


<?php
/****************************
 * DELETE ITEM IF CONFIRMED *
 ****************************/


function get_delete_query()
{
    return array("string" => "DELETE FROM devices WHERE id = ?", "params" => array($_REQUEST["id"]));
}


if (isset($_REQUEST["id"]) && $_REQUEST["action"] == "delete_confirmed")
{
    $query_result_pdo = query_database(get_delete_query());

    if ($query_result_pdo)
        echo "Item (hopefully) deleted." . ask_id_form();
}
?>


<?php echo $post_content_boilerplate ?>