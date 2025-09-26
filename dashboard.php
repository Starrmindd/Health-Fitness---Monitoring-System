<?php

include 'config.php';
include 'navbar.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Guest';

// Fetch fitness data
$res = $conn->query("SELECT * FROM fitness_data WHERE user_id='$user_id' ORDER BY date ASC");

// Prepare data for charts
$dates = [];
$weights = [];
$bmies = [];
while($row = $res->fetch_assoc()){
    $dates[] = $row['date'];
    $weights[] = $row['weight'];
    $bmies[] = $row['bmi'];
}

// Fetch for table
$res_table = $conn->query("SELECT * FROM fitness_data WHERE user_id='$user_id' ORDER BY date DESC");

// Fetch user goals
$goal_res = $conn->query("SELECT * FROM goals WHERE user_id='$user_id'");
$goal = $goal_res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - Fitness Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  .card-summary { height: 100%; }
  .table-actions form { display:inline; }
</style>
</head>
<body class="bg-light">

<div class="container mt-4">

  <!-- Goal Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3">
      <div class="card bg-success text-white card-summary shadow">
        <div class="card-body">
          <h5 class="card-title">Target Weight</h5>
          <p class="card-text"><?php echo $goal['target_weight'] ?? 'Not set'; ?> kg</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card bg-warning text-dark card-summary shadow">
        <div class="card-body">
          <h5 class="card-title">Target Steps</h5>
          <p class="card-text"><?php echo $goal['target_steps'] ?? 'Not set'; ?> steps</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card bg-info text-white card-summary shadow">
        <div class="card-body">
          <h5 class="card-title">Target Calories</h5>
          <p class="card-text"><?php echo $goal['target_calories'] ?? 'Not set'; ?> kcal</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Fitness Data Form -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-success text-white">Add Fitness Data</div>
    <div class="card-body">
      <form method="post" action="add_data.php" class="row g-3">
        <div class="col-md-2"><input type="date" name="date" class="form-control" required></div>
        <div class="col-md-2"><input type="number" step="0.1" name="weight" class="form-control" placeholder="Weight"></div>
        <div class="col-md-2"><input type="number" step="0.1" name="bmi" class="form-control" placeholder="BMI"></div>
        <div class="col-md-2"><input type="number" name="steps" class="form-control" placeholder="Steps"></div>
        <div class="col-md-2"><input type="number" step="0.1" name="calories" class="form-control" placeholder="Calories"></div>
        <div class="col-md-2"><button type="submit" class="btn btn-success w-100">Add</button></div>
      </form>
    </div>
  </div>

  <!-- Fitness Records Table -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-success text-white">Your Fitness Records</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-success">
          <tr><th>Date</th><th>Weight</th><th>BMI</th><th>Steps</th><th>Calories</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if($res_table->num_rows>0): while($row=$res_table->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['date']); ?></td>
            <td><?php echo htmlspecialchars($row['weight']); ?></td>
            <td><?php echo htmlspecialchars($row['bmi']); ?></td>
            <td><?php echo htmlspecialchars($row['steps']); ?></td>
            <td><?php echo htmlspecialchars($row['calories']); ?></td>
            <td class="table-actions">
              <a href="edit_data.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
              <form method="post" action="delete_data.php" onsubmit="return confirm('Delete this record?');">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="6" class="text-center">No records found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Charts -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-success text-white">Weight & BMI Trends</div>
    <div class="card-body">
      <canvas id="fitnessChart" height="100"></canvas>
    </div>
  </div>

</div>

<script>
const ctx = document.getElementById('fitnessChart').getContext('2d');
new Chart(ctx, {
    type:'line',
    data:{
        labels: <?php echo json_encode($dates); ?>,
        datasets:[
            {label:'Weight', data: <?php echo json_encode($weights); ?>, borderColor:'rgba(40,167,69,1)', backgroundColor:'rgba(40,167,69,0.2)', tension:0.3},
            {label:'BMI', data: <?php echo json_encode($bmies); ?>, borderColor:'rgba(255,193,7,1)', backgroundColor:'rgba(255,193,7,0.2)', tension:0.3}
        ]
    },
    options:{
        responsive:true,
        plugins:{ legend:{ position:'top' } },
        scales:{ y:{ beginAtZero:true } }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
