<?php
include 'config.php'; // include database connection
include 'navbar.php'; // include navbar

if(!isset($_SESSION['user_id'])){
    header('Location: login.php'); // redirect if not logged in
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user fitness data
$res = $conn->query("SELECT * FROM fitness_data WHERE user_id='$user_id' ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reports - Fitness Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">Reports</h4>
    </div>
    <div class="card-body">
      <a href="export_csv.php" class="btn btn-primary mb-3">Download CSV</a>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-success">
            <tr>
              <th>Date</th>
              <th>Weight</th>
              <th>BMI</th>
              <th>Steps</th>
              <th>Calories</th>
            </tr>
          </thead>
          <tbody>
            <?php if($res->num_rows>0): ?>
              <?php while($row = $res->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['weight']); ?></td>
                <td><?php echo htmlspecialchars($row['bmi']); ?></td>
                <td><?php echo htmlspecialchars($row['steps']); ?></td>
                <td><?php echo htmlspecialchars($row['calories']); ?></td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5" class="text-center">No records found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
