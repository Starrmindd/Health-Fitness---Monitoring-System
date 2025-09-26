<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if ID is provided
if(!isset($_GET['id'])){
    header('Location: dashboard.php');
    exit;
}

$record_id = $_GET['id'];

// Fetch record for this user
$stmt = $conn->prepare("SELECT * FROM fitness_data WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $record_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows == 0){
    $stmt->close();
    header('Location: dashboard.php');
    exit;
}

$record = $res->fetch_assoc();
$stmt->close();

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $date = $_POST['date'];
    $weight = $_POST['weight'] ?: null;
    $bmi = $_POST['bmi'] ?: null;
    $steps = $_POST['steps'] ?: null;
    $calories = $_POST['calories'] ?: null;

    $stmt = $conn->prepare("UPDATE fitness_data SET date=?, weight=?, bmi=?, steps=?, calories=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sddiiii", $date, $weight, $bmi, $steps, $calories, $record_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Fitness Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Fitness Record</h2>
    <form method="post" class="row g-3 mt-3">
        <div class="col-md-2">
            <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($record['date']); ?>" required>
        </div>
        <div class="col-md-2">
            <input type="number" step="0.1" name="weight" class="form-control" value="<?php echo htmlspecialchars($record['weight']); ?>" placeholder="Weight">
        </div>
        <div class="col-md-2">
            <input type="number" step="0.1" name="bmi" class="form-control" value="<?php echo htmlspecialchars($record['bmi']); ?>" placeholder="BMI">
        </div>
        <div class="col-md-2">
            <input type="number" name="steps" class="form-control" value="<?php echo htmlspecialchars($record['steps']); ?>" placeholder="Steps">
        </div>
        <div class="col-md-2">
            <input type="number" step="0.1" name="calories" class="form-control" value="<?php echo htmlspecialchars($record['calories']); ?>" placeholder="Calories">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Update</button>
        </div>
    </form>
    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
