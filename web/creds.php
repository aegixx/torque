<?php

// Connect to Database
$mysqli = new mysqli($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], $_SERVER['RDS_PORT']);
if ($mysqli->connect_error) {
    die('Connect Error ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
}

$db_table = 'raw_logs';
$db_keys_table = 'key_defs';

// User credentials for Browser login
$auth_user = $_SERVER['AUTH_USER'];    //Sample: 'torque'
$auth_pass = $_SERVER['AUTH_PASS'];    //Sample: 'open'

//If you want to restrict access to upload_data.php, 
// either enter your torque ID as shown in the torque app, 
// or enter the hashed ID as it can found in the uploaded data.
//The hash is simply MD5(ID).
//Leave empty to allow any torque app to upload data to this server.
$torque_id = $_SERVER['TORQUE_ID'];        //Sample: 123456789012345
$torque_id_hash = $_SERVER['TORQUE_ID_HASH'];   //Sample: 58b9b9268acaef64ac6a80b0543357e6

//Just 'settings', could be moved to a config file later.
$source_is_fahrenheit = empty($_SERVER['SOURCE_IS_FAHRENHEIT']) ? false : !!filter_var($_SERVER['SOURCE_IS_FAHRENHEIT'], FILTER_VALIDATE_BOOLEAN);
$use_fahrenheit = empty($_SERVER['USE_FAHRENHEIT']) ? false : !!filter_var($_SERVER['USE_FAHRENHEIT'], FILTER_VALIDATE_BOOLEAN);

$source_is_miles = empty($_SERVER['SOURCE_IS_MILES']) ? false : !!filter_var($_SERVER['SOURCE_IS_MILES'], FILTER_VALIDATE_BOOLEAN);
$use_miles = empty($_SERVER['USE_MILES']) ? false : !!filter_var($_SERVER['USE_MILES'], FILTER_VALIDATE_BOOLEAN);

$hide_empty_variables = empty($_SERVER['HIDE_EMPTY_VARIABLES']) ? true : !!filter_var($_SERVER['HIDE_EMPTY_VARIABLES'], FILTER_VALIDATE_BOOLEAN);
$show_session_length = empty($_SERVER['SHOW_SESSION_LENGTH']) ? true : !!filter_var($_SERVER['SHOW_SESSION_LENGTH'], FILTER_VALIDATE_BOOLEAN);
