<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.html");
  exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
</head>
<body>
  <h1>Benvenuto, <?= htmlspecialchars($_SESSION['user_email']) ?>!</h1>
  <p><a href="logout.php">Logout</a></p>
</body>
</html>
