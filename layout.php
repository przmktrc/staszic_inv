<?php

$navigation_boilerplate = "
    <div id=\"navigation_div\">
        <a href=\"index.php\">Main page</a>
        <a href=\"list.php\">List</a>
        <a href=\"add_item.php\">Add item</a>
        <a href=\"edit_item.php\">Edit item</a>
    </div>";


$pre_title_boilerplate = "
    <!DOCTYPE html>

    <html>

    <head>
        <meta charset=\"UTF-8\">

        <link rel=\"stylesheet\" href=\"layout.css\">
        <link rel=\"stylesheet\" href=\"style.css\">
        <title>Staszic inventory system</title>
    </head>

    <body>
        <div id=\"root_div\">

            <div id=\"title_div\">";


$pre_content_boilerplate = "
            </div>

            $navigation_boilerplate

            <div id=\"content_div\">";


$post_content_boilerplate = "
            </div>
        </div>

    </body>

    </html>";
