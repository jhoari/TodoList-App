<?php
session_start();
include "db.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Check if the form is submitted and the task name is provided
if (isset($_POST['task_name'])) {
    // Sanitize and validate the task name
    $task_name = htmlspecialchars(trim($_POST['task_name']));
    $user_id = $_SESSION['id'];

    // Check if the task name is not empty
    if (!empty($task_name)) {
        // Prepare the SQL query to insert the task
        $sql = "INSERT INTO tasks (user_id, task_name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if the statement was prepared successfully
        if ($stmt) {
            // Bind parameters and execute the query
            mysqli_stmt_bind_param($stmt, "is", $user_id, $task_name);
            mysqli_stmt_execute($stmt);

            // Check if the task was inserted successfully
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Task added successfully, redirect to home.php
                header("Location: home.php");
                exit();
            } else {
                // Handle insertion failure
                header("Location: home.php?error=Failed to add task");
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
    // Handle invalid request (task_name not provided)
    header("Location: home.php");
    exit();
}
?>