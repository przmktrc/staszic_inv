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
