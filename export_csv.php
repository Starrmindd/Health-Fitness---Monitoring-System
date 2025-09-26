<?php
include 'config.php';
session_start();
$user_id=$_SESSION['user_id'];

$res=$conn->query("SELECT * FROM fitness_data WHERE user_id='$user_id' ORDER BY date DESC");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="fitness_report.csv"');
$output = fopen('php://output','w');
fputcsv($output,['Date','Weight','BMI','Steps','Calories']);
while($row=$res->fetch_assoc()){
    fputcsv($output,[$row['date'],$row['weight'],$row['bmi'],$row['steps'],$row['calories']]);
}
fclose($output);
exit;
?>
