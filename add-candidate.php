<?php
session_start();
include "db_connect.php";

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $position = $_POST['position'];
    $name = $_POST['name'];
    $party = $_POST['party'];
    
    // Handle image upload
    $imgPath = '';
    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
        $targetDir = "uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir);
        $fileName = time().'_'.basename($_FILES['img']['name']);
        $targetFile = $targetDir.$fileName;
        move_uploaded_file($_FILES['img']['tmp_name'], $targetFile);
        $imgPath = $targetFile;
    }

    $stmt = $conn->prepare("INSERT INTO candidates (position,name,party,img) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $position, $name, $party, $imgPath);
    $stmt->execute();
    header("Location: admin-dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Candidate</title>
<link rel="stylesheet" href="admin-login.css">
</head>
<body>
<div class="login-box">
  <h2>Add Candidate</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="position" placeholder="Position" required>
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="party" placeholder="Party" required>
    <input type="file" name="img" accept="image/*">
    <button type="submit">Save</button>
  </form>
  <a href="admin-dashboard.php">Back</a>
</div>
</body>
</html>
