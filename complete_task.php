<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $task_id = intval($_GET['id']);
    $user_id = $_SESSION['id'];

    $sql = "UPDATE tasks SET is_completed = NOT is_completed WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $task_id, $user_id);
        mysqli_stmt_execute($stmt);
        header("Location: home.php");
        exit();
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
} else {
    header("Location: home.php");
    exit();
}
?>
