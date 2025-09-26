<?php

include 'config.php'; // database connection
include 'navbar.php'; // include the Bootstrap navbar

if(!isset($_SESSION['user_id'])){
    header('Location: login.php'); // redirect if not logged in
    exit;
}

$user_id = $_SESSION['user_id'];

// Create goals table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS goals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  target_weight FLOAT,
  target_steps INT,
  target_calories FLOAT,
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
)");

// Fetch current goals
$res = $conn->query("SELECT * FROM goals WHERE user_id='$user_id'");
$goal = $res->fetch_assoc();

// Handle form submission
if($_SERVER['REQUEST_METHOD']=='POST'){
    $weight = $_POST['target_weight'] ?: null;
    $steps = $_POST['target_steps'] ?: null;
    $calories = $_POST['target_calories'] ?: null;

    if($goal){
        $stmt = $conn->prepare("UPDATE goals SET target_weight=?, target_steps=?, target_calories=? WHERE user_id=?");
        $stmt->bind_param("diii",$weight,$steps,$calories,$user_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO goals(user_id,target_weight,target_steps,target_calories) VALUES(?,?,?,?)");
        $stmt->bind_param("idii",$user_id,$weight,$steps,$calories);
    }
    $stmt->execute();
    $stmt->close();
    header('Location: goals.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Goals - Fitness Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">Fitness Goals</h4>
    </div>
    <div class="card-body">
      <form method="post" class="row g-3">
        <div class="col-md-4">
          <input type="number" step="0.1" name="target_weight" class="form-control" placeholder="Target Weight" value="<?php echo $goal['target_weight']??''; ?>">
        </div>
        <div class="col-md-4">
          <input type="number" name="target_steps" class="form-control" placeholder="Target Steps" value="<?php echo $goal['target_steps']??''; ?>">
        </div>
        <div class="col-md-4">
          <input type="number" step="0.1" name="target_calories" class="form-control" placeholder="Target Calories" value="<?php echo $goal['target_calories']??''; ?>">
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-success w-100">Save Goals</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
