<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD']=='POST'){
    $date = $_POST['date'];
    $weight = $_POST['weight'] ?: null;
    $bmi = $_POST['bmi'] ?: null;
    $steps = $_POST['steps'] ?: null;
    $calories = $_POST['calories'] ?: null;

    $stmt = $conn->prepare("INSERT INTO fitness_data(user_id,date,weight,bmi,steps,calories) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("isddii", $user_id, $date, $weight, $bmi, $steps, $calories);
    $stmt->execute();
    $stmt->close();

    header('Location: dashboard.php');
}
?>
