<?php
include 'config.php'; // database connection
include 'navbar.php'; // include navbar

if(!isset($_SESSION['user_id'])){
    header('Location: login.php'); // redirect if not logged in
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch fitness data
$res = $conn->query("SELECT * FROM fitness_data WHERE user_id='$user_id' ORDER BY date ASC");

$dates = [];
$weights = [];
$bmies = [];
while($row = $res->fetch_assoc()){
    $dates[] = $row['date'];
    $weights[] = $row['weight'];
    $bmies[] = $row['bmi'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Analytics - Fitness Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      <h4 class="mb-0">Analytics</h4>
    </div>
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
