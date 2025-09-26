<?php
session_start();
$username = $_SESSION['username'] ?? 'Guest';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Fitness Tracker</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="goals.php">Goals</a></li>
        <li class="nav-item"><a class="nav-link" href="analytics.php">Analytics</a></li>
        <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
        <li class="nav-item"><span class="navbar-text text-white me-3">Welcome, <?php echo htmlspecialchars($username); ?></span></li>
        <li class="nav-item"><a class="btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
