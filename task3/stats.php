<?php
session_start();

$admin_username = "admin";
$admin_password = "password";

if(isset($_POST['username']) && isset($_POST['password'])){
  if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
    $_SESSION['loggedin'] = true;
  }  
}

if (!isset($_SESSION['loggedin'])) {
  echo '<form method="POST" action="">
          <input type="text" name="username" placeholder="Username" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="submit" value="Login">
        </form>';
  exit();
}

include 'config.php';

$hourlyVisits = [];
for ($i = 0; $i < 24; $i++) {
  $startHour = sprintf('%02d:00:00', $i);
  $endHour = sprintf('%02d:59:59', $i);

  $stmt = $conn->prepare("SELECT COUNT(*) as count FROM visits WHERE visit_time BETWEEN ? AND ?");

  $startDate = date("Y-m-d $startHour");
  $endDate = date("Y-m-d $endHour");
  
  $stmt->bind_param("ss", $startDate, $endDate);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $hourlyVisits[] = $count;
  $stmt->close();
}

$cityVisits = [];
$stmt = $conn->query("SELECT city, COUNT(*) as count FROM visits GROUP BY city");
while ($row = $stmt->fetch_assoc()) {
    $cityVisits[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Page Visit Statistics</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="script.js"></script>
</head>
<body>
  <h1>Page Visit Statistics</h1>

  <h2>Hourly Visits</h2>
  <canvas id="hourlyChart" width="400" height="200"></canvas>

  <h2>Visits by City</h2>
  <canvas id="cityChart" width="400" height="200"></canvas>

  <script>
      let ctxHourly = document.getElementById('hourlyChart').getContext('2d');
      let hourlyChart = new Chart(ctxHourly, {
        type: 'bar',
        data: {
          labels: <?php echo json_encode(range(0, 23)); ?>,
          datasets: [{
            label: 'Visits per Hour',
            data: <?php echo json_encode($hourlyVisits); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: { beginAtZero: true }
          }
        }
      });

      let ctxCity = document.getElementById('cityChart').getContext('2d');
      let cityChart = new Chart(ctxCity, {
        type: 'pie',
        data: {
          labels: <?php echo json_encode(array_column($cityVisits, 'city')); ?>,
          datasets: [{
            data: <?php echo json_encode(array_column($cityVisits, 'count')); ?>,
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
        }
      });
  </script>
</body>
</html>
