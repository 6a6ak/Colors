<?php
require_once 'relation.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $palette_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare('DELETE FROM user_palettes WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $palette_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header('Location: index.php');
} else {
    echo "Invalid request.";
}
?>
