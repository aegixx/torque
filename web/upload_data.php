<?php

require 'vendor/autoload.php';

use Aws\Kinesis\KinesisClient;

$client = KinesisClient::factory(array(
    // 'profile' => '<profile in your aws credentials file>',
    'region' => 'us-east-1',
));

$result = $client->putRecord(array(
    'StreamName' => 'torqueData',
    'Data' => json_encode($_GET),
    'PartitionKey' => $_GET['session']
));

require_once 'creds.php';
require_once 'auth_app.php';

// Create an array of all the existing fields in the database
$result = $mysqli->query("SHOW COLUMNS FROM {$db_table}") or die("ERROR: {$mysqli->error}");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dbfields[] = ($row['Field']);
    }
}
$result->close();

// Iterate over all the k* _GET arguments to check that a field exists
if (sizeof($_GET) > 0) {
    $keys = array();
    $values = array();
    foreach ($_GET as $key => $value) {
        // Keep columns starting with k
        if (preg_match('/^k/', $key)) {
            $keys[] = $key;
            $values[] = $value;
            $submitval = 1;
        } elseif (in_array($key, array('v', 'eml', 'time', 'id', 'session', 'profile'))) {
            //else if (in_array($key, array("v", "eml", "time", "id", "session"))) {
            $keys[] = $key;
            $values[] = "'".$value."'";
            $submitval = 1;
        }
        // Skip columns matching userUnit*, defaultUnit*, and profile*
        elseif (preg_match('/^userUnit/', $key) or preg_match('/^defaultUnit/', $key)) {
            //else if (preg_match("/^userUnit/", $key) or preg_match("/^defaultUnit/", $key) or (preg_match("/^profile/", $key) and (!preg_match("/^profileName/", $key)))) {
            $submitval = 0;
        } else {
            $keys[] = $key;
            $values[] = "'".$value."'";
            $submitval = 1;
        }
        // If the field doesn't already exist, add it to the database
        if (!in_array($key, $dbfields) and $submitval == 1) {
            if (is_float($value)) {
                $sqlalter = "ALTER TABLE $db_table ADD $key float NOT NULL default '0'";
            } else {
                $sqlalter = "ALTER TABLE $db_table ADD $key VARCHAR(255) NOT NULL default '0'";
            }
            $mysqli->query($sqlalter, $con) or die("ERROR: {$mysqli->error}");
        }
    }
    if ((sizeof($keys) === sizeof($values)) && sizeof($keys) > 0) {
        // Now insert the data for all the fields
        $sql = "INSERT INTO {$db_table} (".implode(',', $keys).') VALUES ('.implode(',', $values).')';
        $mysqli->query($sql) or die("ERROR: {$mysqli->error}");
    }
}

$mysqli->close();

// Return the response required by Torque
echo 'OK!';
