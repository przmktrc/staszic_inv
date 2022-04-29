<?php require "layout.php"; ?>


<?php echo $pre_title_boilerplate; ?>

<h1>Here will be a table view of items.</h1>

<?php echo $pre_content_boilerplate; ?>

<?php
/******************************
 * DISPLAY/HANDLE THE FILTERS *
 ******************************/


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

if ($_REQUEST["new_filter_name"] && $_REQUEST["new_filter_value"])
    $filters[$_REQUEST["new_filter_name"]] = $_REQUEST["new_filter_value"];
else
    unset($filters[$_REQUEST["new_filter_name"]]);


if ($filters)
{
    echo "
    Active filters<br>
    <table>
        <tr>
            <th>Column</th>
            <th>Filter</th>
        </tr>";
    foreach ($filters as $name => $value)
    {
        echo "
            <tr>
                <td>$name</td>
                <td>$value</td>
            </tr>";
    }
    echo "</table><br>";
}
else
    echo "No active filters<br><br>";


echo "
    Add/change a filter (case-insensitive, empty to delete, accepts mysql wildcards, null/not null to filter null/not null)<br>
    <form method='get' action='list.php'>
        <input type='hidden' name='existing_filters' value='" . encode_filters($filters) . "'>
        <select name='new_filter_name'>
            <option value='name'>Name</option>
            <option value='id'>Id</option>
            <option value='description'>Description</option>
            <option value='manufacturer'>Manufacturer</option>
            <option value='location'>Location</option>
            <option value='cpu_model'>CPU Model</option>
            <option value='cpu_max_freq_mhz'>CPU max frequency [MHz]</option>
            <option value='graphics_model'>Graphics Card Model</option>
            <option value='disk_model'>Disk Model</option>
            <option value='disk_size_gb'>Disk size [GB]</option>
            <option value='screen_diagonal_inch'>Screen diagonal [inch]</option>
            <option value='screen_resolution'>Screen resolution</option>
        </select>
        <input type='text' name='new_filter_value'>
        <input type='submit' value='Add filter'>
    </form><br>";
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

    return $query_string;
}

function get_where_part($filters)
{
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


try
{
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

    if ($pdo)
        echo "Connected to database successfully.<br>";
    else
        echo "Failed to connect to database but no exception was thrown.<br>";


    $query_string = get_query_string($filters);
    $query_result_pdo = $pdo->query($query_string);
    if ($query_result_pdo)
        $query_result = $query_result_pdo->fetchAll();
}
catch (PDOException $e)
{
    echo "PDOException: " . $e->getMessage();
}


echo "<table>";
echo "
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Description</th>
        <th>Manufacturer</th>
        <th>Location</th>
        <th>CPU model</th>
        <th>CPU max frequency [MHz]</th>
        <th>RAM model</th>
        <th>RAM amount [GB]</th>
        <th>Graphics card model</th>
        <th>Disk model</th>
        <th>Disk amount [GB]</th>
        <th>Screen diagonal [inch]</th>
        <th>Screen resolution</th>
    </tr>";
if ($query_result)
{
    foreach ($query_result as $device)
    {
        echo "
            <tr>
                <td>" . $device['id'] . "</td>
                <td>" . $device['name'] . "</td>
                <td>" . $device['description'] . "</td>
                <td>" . $device['manufacturer'] . "</td>
                <td>" . $device['location'] . "</td>
                <td>" . $device['cpu_model'] . "</td>
                <td>" . $device['cpu_max_freq_mhz'] . "</td>
                <td>" . $device['ram_model'] . "</td>
                <td>" . $device['ram_amount_gb'] . "</td>
                <td>" . $device['graphics_model'] . "</td>
                <td>" . $device['disk_model'] . "</td>
                <td>" . $device['disk_size_gb'] . "</td>
                <td>" . $device['screen_diagonal_inch'] . "</td>
                <td>" . $device['screen_resolution'] . "</td>
            </tr>";
    }
}
echo "</table>";
?>

<?php echo $post_content_boilerplate ?>
