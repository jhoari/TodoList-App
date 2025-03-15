<?php
session_start();
include 'db.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Check if the task ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $task_id = intval($_GET['id']);
    $user_id = $_SESSION['id'];

    // Fetch the task from the database
    $sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $task_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if task exists
    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $task_name = htmlspecialchars($row['task_name']);
    } else {
        header("Location: home.php?error=Task not found");
        exit();
    }
} else {
    header("Location: home.php?error=Invalid task ID");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Task</title>

    <style>
        body {
            background-color: #FFB5C0; /* Same background */
            font-family: Arial, sans-serif;
        }

        /* Edit Task Form */
        .edit-container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px; /* Same corner-radius */
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.2); /* Glow effect */
        }

        /* Title Style (Same as Home.php) */
        .title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #B2004A;
            text-shadow: 3px 3px 5px rgba(178, 0, 74, 0.5);
            margin: 30px 0;
        }

        /* Input fields */
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #CC0256;
            border-radius: 10px;
            font-size: 16px;
            color: black; /* Ensure text is visible */
        }

        /* Update Task Button (Same as Add Task Button) */
        .btn-update {
            background-color: #B2004A !important; /* Same as Add Task button */
            border: 2px solid #A50545 !important;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 3px 3px 10px rgba(178, 0, 74, 0.3);
            transition: all 0.3s ease-in-out;
            cursor: pointer;
            display: block;
            width: 100%;
            text-align: center;
        }

        .btn-update:hover {
            background-color: #A50545 !important; /* Darker glow */
            box-shadow: 0 0 15px rgba(165, 5, 69, 0.6);
        }

        .btn-update:active {
            background-color: #87003C !important;
            box-shadow: 0 0 20px rgba(135, 0, 60, 0.7);
            transform: scale(0.95);
        }
        .app-title {
    text-align: center;
    font-size: 40px; /* Same as home.php title */
    font-weight: bold;
    color: #B2004A; /* Same color */
    text-shadow: 3px 3px 5px rgba(178, 0, 74, 0.5); /* Same shadow */
    margin: 30px 0;
}
.btn-add-task {
    background-color: #F94F8E !important; /* Same as Add Task */
    border: 2px solid #C71585 !important; /* Dark Pink Border */
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    box-shadow: 3px 3px 10px rgba(249, 79, 142, 0.3); /* Subtle glow */
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}

.btn-add-task:hover {
    background-color: #C71585 !important; /* Darker Pink */
    box-shadow: 0 0 15px rgba(199, 21, 133, 0.6); /* Brighter glow */
}

.btn-add-task:active {
    background-color: #A50545 !important; /* Even Darker Pink */
    box-shadow: 0 0 20px rgba(165, 5, 69, 0.7);
    transform: scale(0.95);
}

        </style>
</head>
<body>
    <h1 class="app-title">Update Your To-Do List</h1>

    <div class="w-50 m-auto">
        <form action="edit_action.php" method="post" autocomplete="off">
            <div class="form-group">
                <label for="title">Task Name</label>
                <input class="form-control" type="text" name="task_name" id="title"
                    value="<?php echo $task_name; ?>" required>
                <input type="hidden" name="id" value="<?php echo $task_id; ?>">
            </div>
            <br>
            <button class="btn-add-task" type="submit">Update Task</button>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

