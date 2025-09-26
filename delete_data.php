<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['id'])){
    $record_id = $_POST['id'];

    // Delete only if the record belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM fitness_data WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $record_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: dashboard.php');
exit;
?>
