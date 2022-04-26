<?php require "layout.php"; ?>


<?php echo $pre_title_boilerplate; ?>

<h1>Here will be a table view of items.</h1>

<?php echo $pre_content_boilerplate; ?>

<?php
require "serverdata.php";

try
{
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_user_password);

    if ($pdo)
        echo "Connected to database successfully.<br>";
    else
        echo "Failed to connect to database but no exception was thrown.<br>";


    $query_result = $pdo->query("SELECT * FROM devices;")->fetchAll();
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
                <td>" . $device['disk_amount_gb'] . "</td>
                <td>" . $device['screen_diagonal_inch'] . "</td>
                <td>" . $device['screen_resolution'] . "</td>
            </tr>";
    }
}
echo "</table>";
?>

<?php echo $post_content_boilerplate ?>
