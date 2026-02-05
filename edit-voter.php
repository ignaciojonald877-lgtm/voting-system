<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$id = intval($_GET['id']);
$voter = $conn->query("SELECT * FROM voters WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $grade = $_POST['grade'];
    $stmt = $conn->prepare("UPDATE voters SET name=?, grade=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $grade, $id);
    $stmt->execute();
    header("Location: admin-dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Voter</title>
<link rel="stylesheet" href="admin-login.css">
</head>
<body>
<div class="login-box">
  <h2>Edit Student Voter</h2>
  <form method="POST">
    <input type="text" name="name" value="<?php echo $voter['name']; ?>" required>
    <input type="text" name="grade" value="<?php echo $voter['grade']; ?>" required>
    <button type="submit">Update</button>
  </form>
  <a href="admin-dashboard.php">Back</a>
</div>
</body>
</html>
