<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php");
    exit();
}

/* Voting status */
$statusQuery = $conn->query("SELECT status FROM voting_status WHERE id = 1");
$votingStatus = $statusQuery->fetch_assoc()['status'];

/* Actions */
if (isset($_GET['toggle'])) {
    $newStatus = $votingStatus ? 0 : 1;
    $conn->query("UPDATE voting_status SET status = $newStatus WHERE id = 1");
    header("Location: admin-dashboard.php");
    exit();
}

if (isset($_GET['deleteCandidate'])) {
    $id = intval($_GET['deleteCandidate']);
    $conn->query("DELETE FROM candidates WHERE id = $id");
    header("Location: admin-dashboard.php");
    exit();
}

if (isset($_GET['deleteVoter'])) {
    $id = intval($_GET['deleteVoter']);
    $conn->query("DELETE FROM voters WHERE id = $id");
    header("Location: admin-dashboard.php");
    exit();
}

if (isset($_GET['verifyVoter'])) {
    $id = intval($_GET['verifyVoter']);
    $conn->query("UPDATE voters SET verified = 1 WHERE id = $id");
    header("Location: admin-dashboard.php");
    exit();
}

/* Data */
$candidates = $conn->query("SELECT * FROM candidates ORDER BY id DESC");
$voters = $conn->query("SELECT * FROM voters ORDER BY id DESC");

$totalCandidates = $candidates->num_rows;
$totalVoters = $voters->num_rows;
$verifiedVoters = $conn->query("SELECT * FROM voters WHERE verified = 1")->num_rows;
$unverifiedVoters = $conn->query("SELECT * FROM voters WHERE verified = 0")->num_rows;

/* Load HTML */
include "admin-dashboard.html";
