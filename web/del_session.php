<?php

require_once './creds.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['deletesession'])) {
    $deletesession = preg_replace('/\D/', '', $_POST['deletesession']);
} elseif (isset($_GET['deletesession'])) {
    $deletesession = preg_replace('/\D/', '', $_GET['deletesession']);
}

if (isset($deletesession) && !empty($deletesession)) {
    $mysqli->query("DELETE FROM $db_table WHERE session=$deletesession;") or die("ERROR: {$mysqli->error}");
}
