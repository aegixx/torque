<?php

require_once './creds.php';

// Create array of column name/comments for chart data selector form
// 2015.08.21 - edit by surfrock66 - Rather than pull from the column comments,
//   pull from a new database created which manages variables. Include
//   a column flagging whether a variable is populated or not.
$col_result = $mysqli->query("SELECT id,description,type FROM {$db_keys_table} WHERE populated = 1 ORDER BY description") or die("ERROR: {$mysqli->error}");
while ($x = $col_result->fetch_array()) {
    if ((substr($x[0], 0, 1) == 'k') && ($x[2] == 'float')) {
        $coldata[] = array('colname' => $x[0], 'colcomment' => $x[1]);
    }
}
$col_result->close();

$numcols = strval(count($coldata) + 1);

//TODO: Do this once in a dedicated file
if (isset($_POST['id'])) {
    $session_id = preg_replace('/\D/', '', $_POST['id']);
} elseif (isset($_GET['id'])) {
    $session_id = preg_replace('/\D/', '', $_GET['id']);
}

$coldataempty = array();
