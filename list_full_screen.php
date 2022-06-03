<?php
require "util.php";
echo "
<!DOCTYPE html>
<html>
<head>
<meta charset=\"UTF-8\">
 <style>
.navbar {
  overflow: hidden;
  background-color: #333;
  position: fixed; /* Set the navbar to fixed position */
  top: 0; /* Position the navbar at the top of the page */
  width: 100%; /* Full width */
}
</style>

<link rel=\"stylesheet\" href=\"style.css\">
</head>
<body style=\"text-align:center;  margin: 0;  padding: 0; \">";


function encode_filters($filters)
{
    $result = "";

    foreach ($filters as $key => $value)
    {
        $result = $result . $key . ";" . $value . ";";
    }

    return $result;
}

function decode_filters($filters_string)
{
    $result = array();
    $filters_array = explode(';', $filters_string);


    $count = floor(count($filters_array) / 2);
    for ($i = 0; $i < $count; $i++)
    {
        $result[$filters_array[2 * $i]] = $filters_array[2 * $i + 1];
    }

    return $result;
}


$filters = decode_filters($_REQUEST["existing_filters"]);

if ($_REQUEST["new_filter_name"] && $_REQUEST["new_filter_value"])
    $filters[$_REQUEST["new_filter_name"]] = $_REQUEST["new_filter_value"];
else if ($_REQUEST["new_filter_name"])
    unset($filters[$_REQUEST["new_filter_name"]]);

?>


<?php
/*********************
 * DISPLAY THE ITEMS *
 *********************/


function get_select_query($filters)
{
    $query_string = "SELECT * FROM devices";
    $query_params = array();

    $where_part = get_where_part($filters);
    if ($where_part)
    {
        $query_string = $query_string . " " . $where_part["string"];
        $query_params = array_merge($query_params, $where_part["params"]);
    }

    $sort_part = get_sort_part();
    if ($sort_part)
    {
        $query_string = $query_string . " " . $sort_part["string"];
        $query_params = array_merge($query_params, $sort_part["params"]);
    }

    return array("string" => $query_string, "params" => $query_params);
}

function get_where_part($filters)
{
    if (!$filters) return false;

    $where_string = "WHERE ";
    $where_params = array();

    foreach ($filters as $key => $value)
    {
        if (is_valid_key($key))
        {
            if ($value == "null" || $value == "not null")
            {
                $where_string = $where_string . "$key IS $value AND ";
            }
            else
            {
                $where_string = $where_string . "$key LIKE ? AND ";
                array_push($where_params, $value);
            }
        }
    }

    return array("string" => substr($where_string, 0, strlen($where_string) - 5), "params" => $where_params);
}

function get_sort_part()
{
    if (is_valid_key($_REQUEST["sort_what"]) && is_valid_sortby($_REQUEST["sort_how"]))
        return array("string" => "ORDER BY " . $_REQUEST["sort_what"] . " " . $_REQUEST["sort_how"], "params" => array());
    else
        return array("string" => "ORDER BY id asc", "params" => array());
}



$query_result_pdo = query_database(get_select_query($filters));
if ($query_result_pdo)
    $devices = $query_result_pdo->fetchAll();
echo "<div class=\"navbar\"><table style=\"\"";
echo "
    <tr style=\"width:100%;\">
        <td style=\"width:3%;\">Id</th>
        <td style=\"width:10%;\">Name</th>
        <td style=\"width:15%;\">Description</th>
        <td style=\"width:8%;\">Manufacturer</th>
        <td style=\"width:10%;\">Location</th>
        <td style=\"width:5%;\">CPU model</th>
        <td style=\"width:7%;\">CPU max frequency [MHz]</th>
        <td style=\"width:5%;\">RAM model</th>
        <td style=\"width:5%;\">RAM amount [GB]</th>
        <td style=\"width:8%;\">Graphics card model</th>
        <td style=\"width:8%;\">Disk model</th>
        <td style=\"width:7%;\">Disk amount [GB]</th>
        <td style=\"width:4%;\">Screen diagonal [inch]</th>
        <td style=\"width:5%;\">Screen resolution</th>
    </tr></table></div><br><br><br><table style=\"table-layout:fixed; width:100%;\">";

echo "<div style=\"overflow:auto;\">";
foreach ($devices as $device)
{
    echo "
            <tr style=\"width:100%;\">
                <td style=\"width:3%;\">" . sanitize($device['id']) . "</td>
                <td style=\"width:10%;\">" . sanitize($device['name']) . "</td>
                <td style=\"width:15%;\">" . sanitize($device['description']) . "</td>
                <td style=\"width:8%;\">" . sanitize($device['manufacturer']) . "</td>
                <td style=\"width:10%;\">" . sanitize($device['location']) . "</td>
                <td style=\"width:5%;\">" . sanitize($device['cpu_model']) . "</td>
                <td style=\"width:7%;\">" . sanitize($device['cpu_max_freq_mhz']) . "</td>
                <td style=\"width:5%;\">" . sanitize($device['ram_model']) . "</td>
                <td style=\"width:5%;\">" . sanitize($device['ram_amount_gb']) . "</td>
                <td style=\"width:8%;\">" . sanitize($device['graphics_model']) . "</td>
                <td style=\"width:8%;\">" . sanitize($device['disk_model']) . "</td>
                <td style=\"width:7%;\">" . sanitize($device['disk_size_gb']) . "</td>
                <td style=\"width:4%;\">" . sanitize($device['screen_diagonal_inch']) . "</td>
                <td style=\"width:5%;\">" . sanitize($device['screen_resolution']) . "</td>
            </tr>";
}
echo "</table></div>";
?>

<?php echo $post_content_boilerplate ?>
