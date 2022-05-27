<?php
echo "
<!DOCTYPE html>
<html>
<head>
<meta charset=\"UTF-8\">

<link rel=\"stylesheet\" href=\"style.css\">
</head>
<body style=\"text-align:center;  margin: 0;  padding: 0;\">";

function decode_filters($filters_string)
{
    // TODO: Sanitize user input
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
?>


<?php
/*********************
 * DISPLAY THE ITEMS *
 *********************/

require "serverdata.php";


function get_query_string($filters)
{
    $query_string = "SELECT * FROM devices";

    $where_part = get_where_part($filters);
    if ($where_part)
        $query_string = $query_string . " " . $where_part;

    $sort_part = get_sort_part();
    if ($sort_part)
        $query_string = $query_string . " " . $sort_part;

    return $query_string;
}

function get_where_part($filters)
{
    if (!$filters) return false;

    $result = "WHERE ";

    foreach ($filters as $key => $value)
    {
        if ($value == "null" || $value == "not null")
            $result = $result . "$key IS $value AND ";
        else
            $result = $result . "$key LIKE '$value' AND ";
    }

    return substr($result, 0, strlen($result) - 5);
}

function get_sort_part()
{
    if (!$_REQUEST["sort_what"])
    {
        return "ORDER BY id asc";
    }

    return "ORDER BY " . $_REQUEST["sort_what"] . " " . $_REQUEST["sort_how"];
}

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);
$query_string = get_query_string($filters);
$query_result_pdo = $pdo->query($query_string);
if ($query_result_pdo)
    $query_result = $query_result_pdo->fetchAll();

echo "<table";
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
    </tr></table><table style=\"table-layout:fixed\">";
if ($query_result)
{
echo "<div style=\"overflow:auto;\">";
    foreach ($query_result as $device)
    {
        echo "
            <tr style=\"width:100%;\">
                <td style=\"width:3%;\">" . $device['id'] . "</td>
                <td style=\"width:10%;\">" . $device['name'] . "</td>
                <td style=\"width:15%;\">" . $device['description'] . "</td>
                <td style=\"width:8%;\">" . $device['manufacturer'] . "</td>
                <td style=\"width:10%;\">" . $device['location'] . "</td>
                <td style=\"width:5%;\">" . $device['cpu_model'] . "</td>
                <td style=\"width:7%;\">" . $device['cpu_max_freq_mhz'] . "</td>
                <td style=\"width:5%;\">" . $device['ram_model'] . "</td>
                <td style=\"width:5%;\">" . $device['ram_amount_gb'] . "</td>
                <td style=\"width:8%;\">" . $device['graphics_model'] . "</td>
                <td style=\"width:8%;\">" . $device['disk_model'] . "</td>
                <td style=\"width:7%;\">" . $device['disk_size_gb'] . "</td>
                <td style=\"width:4%;\">" . $device['screen_diagonal_inch'] . "</td>
                <td style=\"width:5%;\">" . $device['screen_resolution'] . "</td>
            </tr>";
    }
}
echo "</div>";
echo "</table>";
?>

<?php echo $post_content_boilerplate ?>
