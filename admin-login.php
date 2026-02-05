<?php
session_start();
include "db_connect.php";

$error = "";

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminId = $_POST['adminId'];
    $adminPassword = $_POST['adminPassword'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
    $stmt->bind_param("s", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        // Plain text password check for now
        if ($adminPassword === $admin['password']) {
            $_SESSION['admin'] = $adminId;
            header("Location: admin-dashboard.php");
            exit();
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Admin not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin-login.css">
</head>
<body>

<!-- Header same as landing page -->
<nav class="header-box">
    <div class="logo">
        <img src="images.jpg" alt="School Logo">
    </div>

    <div class="text-box">
        Sulivan National High School
    </div>

    <div class="login-container">
        <!-- optional: can leave empty for admin login page -->
    </div>
</nav>

<!-- Login Box -->
<div class="login-box">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="text" name="adminId" placeholder="Admin ID" required>
        <div class="password-box">
            <input type="password" name="adminPassword" placeholder="Password" required>
            <span class="eye" id="togglePassword">👁</span>
        </div>
        <button type="submit">Login</button>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
    </form>
</div>

<script>
const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.querySelector("input[name='adminPassword']");

togglePassword.addEventListener("click", () => {
  passwordInput.type = passwordInput.type === "password" ? "text" : "password";
  togglePassword.textContent = passwordInput.type === "password" ? "👁" : "🙈";
});
</script>

</body>
</html>
