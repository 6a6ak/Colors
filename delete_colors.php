<?php
require_once 'relation.php';

if (isset($_GET['batch_id'])) {
    $batch_id = intval($_GET['batch_id']);

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare('DELETE FROM colors WHERE batch_id = ?');
    $stmt->bind_param('i', $batch_id);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();

    header('Location: index.php');
    exit;
} else {
    echo 'No batch ID provided for deletion.';
}
?>
