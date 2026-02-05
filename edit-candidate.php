<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$id = intval($_GET['id']);
$candidate = $conn->query("SELECT * FROM candidates WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $position = $_POST['position'];
    $name = $_POST['name'];
    $party = $_POST['party'];
    $imgPath = $candidate['img'];

    if(isset($_FILES['img']) && $_FILES['img']['error'] == 0){
        $targetDir = "uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir);
        $fileName = time().'_'.basename($_FILES['img']['name']);
        $targetFile = $targetDir.$fileName;
        move_uploaded_file($_FILES['img']['tmp_name'], $targetFile);
        $imgPath = $targetFile;
    }

    $stmt = $conn->prepare("UPDATE candidates SET position=?, name=?, party=?, img=? WHERE id=?");
    $stmt->bind_param("ssssi", $position, $name, $party, $imgPath, $id);
    $stmt->execute();
    header("Location: admin-dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Candidate</title>
<link rel="stylesheet" href="admin-login.css">
</head>
<body>
<div class="login-box">
  <h2>Edit Candidate</h2>
  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="position" value="<?php echo $candidate['position']; ?>" required>
    <input type="text" name="name" value="<?php echo $candidate['name']; ?>" required>
    <input type="text" name="party" value="<?php echo $candidate['party']; ?>" required>
    <input type="file" name="img" accept="image/*">
    <button type="submit">Update</button>
  </form>
  <a href="admin-dashboard.php">Back</a>
</div>
</body>
</html>
