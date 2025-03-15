<?php
session_start();
include 'db.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Check if the form is submitted and the task ID and name are provided
if (isset($_POST['id']) && isset($_POST['task_name'])) {
    // Sanitize the input
    $task_id = intval($_POST['id']); // Ensure the ID is an integer
    $task_name = htmlspecialchars(trim($_POST['task_name']));
    $user_id = $_SESSION['id']; // Get the logged-in user's ID

    // Check if the task name is not empty
    if (!empty($task_name)) {
        // Prepare the SQL query to update the task
        $sql = "UPDATE tasks SET task_name = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if the statement was prepared successfully
        if ($stmt) {
            // Bind parameters and execute the query
            mysqli_stmt_bind_param($stmt, "sii", $task_name, $task_id, $user_id);
            mysqli_stmt_execute($stmt);

            // Check if the task was updated successfully
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Task updated successfully, redirect to home.php
                header("Location: home.php");
                exit();
            } else {
                // Handle update failure (e.g., task not found or doesn't belong to the user)
                header("Location: home.php?error=Failed to update task or task does not exist");
                exit();
            }
        } else {
            // Handle SQL preparation failure
            die("Query preparation failed: " . mysqli_error($conn));
        }
    } else {
        // Handle empty task name
        header("Location: home.php?error=Task name cannot be empty");
        exit();
    }
} else {
    // Handle invalid request (task ID or name not provided)
    header("Location: home.php");
    exit();
}
?>