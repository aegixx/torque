<?php

require './creds.php';

if (isset($_GET['sid'])) {
    $session_id = $mysqli->real_escape_string($_GET['sid']);
    // Get data for session
    $output = '';
    $result = $mysqli->query("SELECT * FROM $db_table WHERE session=$session_id ORDER BY time DESC;") or die("ERROR: {$mysqli->error}");

    if ($_GET['filetype'] == 'csv') {
        $columns_total = $mysqli->field_count;

        // Get The Field Name
        for ($i = 0; $i < $columns_total; ++$i) {
            $heading = $mysqli->fetch_field_direct($i);
            $output .= '"'.$heading.'",';
        }
        $output .= "\n";

        // Get Records from the table
        while ($row = $result->fetch_array()) {
            for ($i = 0; $i < $columns_total; ++$i) {
                $output .= '"'.$row["$i"].'",';
            }
            $output .= "\n";
        }

        // Download the file
        $csvfilename = 'torque_session_'.$session_id.'.csv';
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$csvfilename);

        echo $output;
        exit;
    } elseif ($_GET['filetype'] == 'json') {
        $rows = array();
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        $jsonrows = json_encode($rows);

        // Download the file
        $jsonfilename = 'torque_session_'.$session_id.'.json';
        header('Content-type: application/json');
        header('Content-Disposition: attachment; filename='.$jsonfilename);

        echo $jsonrows;
    }

    $result->close();
}

$mysqli->close();
