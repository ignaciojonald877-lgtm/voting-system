<?php
session_start();
include "db_connect.php";

$login_error = "";
$signup_error = "";
$signup_success = "";

/* ===== LOGIN ===== */
if (isset($_POST['login'])) {
    $student_id = trim($_POST['student_id']);

    if (strlen($student_id) != 6) {
        $login_error = "Student ID must be 6 digits.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM voters WHERE student_id = ? LIMIT 1");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 0) {
            $login_error = "Student not found.";
        } else {
            $s = $res->fetch_assoc();
            if ($s['verified'] == 0) {
                $login_error = "Account not verified yet.";
            } else {
                $_SESSION['student'] = $s['id'];
                $_SESSION['student_name'] = $s['name'];
                header("Location: student-dashboard.php");
                exit();
            }
        }
    }
}

/* ===== SIGN UP ===== */
if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $grade = trim($_POST['grade']);
    $section = trim($_POST['section']);
    $student_id = trim($_POST['student_id']);

    if (!$name || !$grade || !$section || strlen($student_id) != 6) {
        $signup_error = "Complete all fields. Student ID must be 6 digits.";
    } else {
        $check = $conn->prepare("SELECT id FROM voters WHERE student_id=?");
        $check->bind_param("s", $student_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $signup_error = "Student ID already exists.";
        } else {
            $ins = $conn->prepare(
                "INSERT INTO voters (name, grade, section, student_id, verified, hasVoted)
                 VALUES (?, ?, ?, ?, 0, 0)"
            );
            $ins->bind_param("ssss", $name, $grade, $section, $student_id);
            $ins->execute();
            $signup_success = "Signup successful! Wait for admin verification.";
        }
    }
}

/* LOAD HTML */
include "student-login.html";
