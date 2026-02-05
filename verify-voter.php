<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

$id = intval($_GET['id']);
$conn->query("UPDATE voters SET verified = 1 WHERE id = $id");
header("Location: admin-dashboard.php");
exit();
?>
