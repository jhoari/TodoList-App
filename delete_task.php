<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die("Error: No task ID received.");
    }

    $task_id = intval($_POST['id']);
    $user_id = $_SESSION['id'];

    // Delete task query
    $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $task_id, $user_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: home.php"); // Redirect back after deletion
            exit();
        } else {
            echo "Task not found or unauthorized.";
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
} else {
    die("Invalid request method.");
}
?>
