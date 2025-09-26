<?php
session_start();
if(isset($_SESSION['user_id'])){
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Fitness & Monitoring System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container text-center mt-5">
    <h1>Welcome to Health Fitness Tracker</h1>
    <p>Track your weight, BMI, steps, and calories easily!</p>
    <a href="login.php" class="btn btn-success m-2">Login</a>
    <a href="register.php" class="btn btn-primary m-2">Register</a>
</div>
</body>
</html>
