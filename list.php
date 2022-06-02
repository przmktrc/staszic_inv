<?php require "layout.php"; ?>
<?php require "util.php"; ?>


<?php echo $pre_title_boilerplate; ?>

<h1>Here will be a table view of items.</h1>

<?php echo $pre_content_boilerplate; ?>

<?php
/************************************
 * DISPLAY/HANDLE FILTERS & SORTING *
 ************************************/


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
                <td>" . sanitize($name) . "</td>
                <td>" . sanitize($value) . "</td>
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
        <input type='hidden' name='sort_what' value='" . $_REQUEST["sort_what"] . "'>
        <input type='hidden' name='sort_how' value='" . $_REQUEST["sort_how"] . "'>
        <select name='new_filter_name'>
            <option value='name'>Name</option>
            <option value='id'>Id</option>
            <option value='description'>Description</option>
            <option value='manufacturer'>Manufacturer</option>
            <option value='location'>Location</option>
            <option value='cpu_model'>CPU Model</option>
            <option value='cpu_max_freq_mhz'>CPU max frequency [MHz]</option>
            <option value='ram_model'>RAM Model</option>
            <option value='ram_amount_gb'>RAM amount [GB]</option>
            <option value='graphics_model'>Graphics Card Model</option>
            <option value='disk_model'>Disk Model</option>
            <option value='disk_size_gb'>Disk size [GB]</option>
            <option value='screen_diagonal_inch'>Screen diagonal [inch]</option>
            <option value='screen_resolution'>Screen resolution</option>
        </select>
        <input type='text' name='new_filter_value'>
        <input type='submit' value='Add filter'>
    </form><br>";

echo "
    Sorted by<br>
    <form method='get' action=list.php>
        <input type='hidden' name='existing_filters' value='" . encode_filters($filters) . "'>
        <select name='sort_what'>
            <option value='name'>Name</option>
            <option value='id' selected>Id</option>
            <option value='description'>Description</option>
            <option value='manufacturer'>Manufacturer</option>
            <option value='location'>Location</option>
            <option value='cpu_model'>CPU Model</option>
            <option value='cpu_max_freq_mhz'>CPU max frequency [MHz]</option>
            <option value='ram_model'>RAM Model</option>
            <option value='ram_amount_gb'>RAM amount [GB]</option>
            <option value='graphics_model'>Graphics Card Model</option>
            <option value='disk_model'>Disk Model</option>
            <option value='disk_size_gb'>Disk size [GB]</option>
            <option value='screen_diagonal_inch'>Screen diagonal [inch]</option>
            <option value='screen_resolution'>Screen resolution</option>
        </select>
        <select name='sort_how'>
            <option value='asc' selected>ascending</option>
            <option value='desc'>descending</option>
        </select>
        <input type='submit' value='sort'>
    </form><br>";

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


echo "<table>";
echo "
    <tr>
        <th></th>
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
if ($devices)
{
    foreach ($devices as $device)
    {
        echo "
            <tr>
                <td><a style='text-decoration: none;' href='edit_item.php?action=edit&id=" . sanitize($device['id']) . "'>edit</a></td>
                <td>" . sanitize($device['id']) . "</td>
                <td>" . sanitize($device['name']) . "</td>
                <td>" . sanitize($device['description']) . "</td>
                <td>" . sanitize($device['manufacturer']) . "</td>
                <td>" . sanitize($device['location']) . "</td>
                <td>" . sanitize($device['cpu_model']) . "</td>
                <td>" . sanitize($device['cpu_max_freq_mhz']) . "</td>
                <td>" . sanitize($device['ram_model']) . "</td>
                <td>" . sanitize($device['ram_amount_gb']) . "</td>
                <td>" . sanitize($device['graphics_model']) . "</td>
                <td>" . sanitize($device['disk_model']) . "</td>
                <td>" . sanitize($device['disk_size_gb']) . "</td>
                <td>" . sanitize($device['screen_diagonal_inch']) . "</td>
                <td>" . sanitize($device['screen_resolution']) . "</td>
            </tr>";
    }
}
echo "</table>";
?>

<?php echo $post_content_boilerplate ?>