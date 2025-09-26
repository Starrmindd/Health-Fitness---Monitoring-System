<?php
session_start();
include 'config.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows>0){
        $row = $res->fetch_assoc();
        if(password_verify($password,$row['password'])){
            $_SESSION['user_id']=$row['id'];
            $_SESSION['username']=$row['username'];
            header('Location: dashboard.php');
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with this email.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post" class="mt-3">
        <input name="email" type="email" class="form-control mb-2" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
        <button type="submit" class="btn btn-success">Login</button>
    </form>
    <p class="mt-2">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
