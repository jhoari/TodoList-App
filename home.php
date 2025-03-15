<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch tasks for the logged-in user
require_once 'db.php'; // Include your database connection file

// Check if 'id' is set in session
if (!isset($_SESSION['id'])) {
    die("Error: User ID is not set in session. Please log in again.");
}

$user_id = $_SESSION['id']; // Use 'id' since it's what is stored in the login session
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$tasks = $result->fetch_all(MYSQLI_ASSOC); // Fetch tasks as an associative array
?>

<!doctype html>
<html lang="en">
<head>
    
<style>
    .custom-table {
        background-color:rgb(255, 255, 255) !important;
    }
    .custom-table tbody tr {
        background-color:rgb(226, 226, 226) !important;
    }
    .custom-table thead {
        background-color:rgb(255, 255, 255) !important;
    }
    .custom-table td, .custom-table th {
        color: black !important;
    }
    .btn-group .btn {
        margin-right: 5px;
    }
    .logout-container {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    }
    .todo-title {
    font-weight: bold;
    font-size: 2.5rem; 
    color: #700124;
    font-size: 40px;
    text-shadow: 3px 3px 5px rgba(158, 16, 73, 0.5);
    }
    .text-center{
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
    }
    input.form-control {
    border-radius: 15px; 
    border: 2px solid #FF69B4; 
    padding: 10px; 
    outline: none; 
    font-size: 16px; 
    }

  
    input.form-control:focus {
    border-color: #FF1493; 
    box-shadow: 0 0 10px rgba(255, 105, 180, 0.5);
    }
    .btn-success {
    background-color: #FF69B4 !important; 
    border: 2px solid #C71585 !important; 
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    box-shadow: 3px 3px 10px rgba(255, 105, 180, 0.3); 
    transition: all 0.3s ease-in-out;
    font-family: 'Poppins', sans-serif;
}

.btn-success:hover {
    background-color: #FF1493 !important; 
    border-color: #8B0047 !important; 
    box-shadow: 0 0 15px rgba(255, 20, 147, 0.6); 
}

.btn-success:active {
    background-color: #C71585 !important; 
    border-color: #8B0047 !important; 
    box-shadow: 0 0 20px rgba(199, 21, 133, 0.7);
    transform: scale(0.95); 
}

.btn-success {
    background-color: #F94F8E !important; 
    border: 2px solid #D13E77 !important;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif ;
    font-weight: bold;
    color: white;
    box-shadow: 3px 3px 10px rgba(249, 79, 142, 0.3);
    transition: all 0.3s ease-in-out;
}

.btn-success:hover {
    background-color: #E0437F !important; 
    box-shadow: 0 0 15px rgba(224, 67, 127, 0.6);
}

.btn-success:active {
    background-color: #C7356F !important; 
    box-shadow: 0 0 20px rgba(199, 53, 111, 0.7);
    transform: scale(0.95);
}

.btn-primary {
    background-color: #EA0F6B !important; 
    border: 2px solid #C90D5A !important; 
    color: white;
    font-family: 'Poppins', sans-serif ;
    border-radius: 10px;
    box-shadow: 3px 3px 10px rgba(234, 15, 107, 0.3);
    transition: all 0.3s ease-in-out;
}

.btn-primary:hover {
    background-color: #D00E5F !important; 
    box-shadow: 0 0 15px rgba(208, 14, 95, 0.6);
}

.btn-primary:active {
    background-color: #B90C54 !important; 
    box-shadow: 0 0 20px rgba(185, 12, 84, 0.7);
    transform: scale(0.95);
}

.btn-danger {
    background-color: #CC0256 !important;
    border: 2px solid #A70246 !important;
    color: white;
    font-family: 'Poppins', sans-serif ;
    border-radius: 10px;
    box-shadow: 3px 3px 10px rgba(204, 2, 86, 0.3);
    transition: all 0.3s ease-in-out;
}

.btn-danger:hover {
    background-color: #B0024D !important; 
    box-shadow: 0 0 15px rgba(176, 2, 77, 0.6);
}

.btn-danger:active {
    background-color: #94023F !important; 
    box-shadow: 0 0 20px rgba(148, 2, 63, 0.7);
    transform: scale(0.95);
}


.btn-logout {
    background-color: #A50545 !important; 
    border: 2px solid #800437 !important; 
    color: white;
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: bold;
    font-family: 'Poppins', sans-serif ;
    box-shadow: 3px 3px 10px rgba(165, 5, 69, 0.3);
    transition: all 0.3s ease-in-out;

    position: sticky;  
    bottom: 0;
    width: 100%; 
    text-align: center;
}

.btn-logout:hover {
    background-color: #8F043D !important;
    box-shadow: 0 0 10px rgba(143, 4, 61, 0.4);
}

.btn-logout:active {
    background-color: #750335 !important; 
    box-shadow: 0 0 15px rgba(117, 3, 53, 0.6);
    transform: scale(0.95);
}


.btn {
    border-radius: 10px !important; 
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    font-family: 'Poppins', sans-serif ;
    transition: all 0.3s ease-in-out;
}
h1 {
    color: #B2004A !important; 
    text-shadow: 2px 2px 5px rgba(178, 0, 74, 0.5); 
}
.welcome-text {
    color:  #CE2B6F !important; 
    font-weight: bold;
    text-shadow: 2px 2px 5px rgba(204, 2, 86, 0.5);
}
thead th {
    color: #A50545 !important; 
    border-bottom: 3px solid #CC0256 !important; 
    padding-bottom: 10px; 
}
.custom-table {
    border: 3px solid #CC0256 !important;
    border-radius: 5px; 
    overflow: hidden; 
}

.custom-table thead th {
    color: #A50545 !important; 
    border-bottom: 3px solid #CC0256 !important;
    background-color: #FFB5C0 !important; 
    padding-bottom: 10px;
}

.custom-table tbody td {
    color: black !important; 
    border: 2px solid #CC0256 !important;
    background-color: white !important; 
}
label[for="title"] {
    color: #B2004A !important; 
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(178, 0, 74, 0.3);
}
h3 {
    color: #B2004A !important;
    font-weight: bold;
    text-shadow: 2px 2px 4px rgba(178, 0, 74, 0.5); 
}
th {
    background-color: #CC0256 !important; 
    color: white !important;
    border: 2px solid #A50545 !important; 
    padding: 10px;
    text-align: center;
}

td {
    border: 2px solid #A50545 !important; 
    color: black !important;
    padding: 8px;
    text-align: center;
}

    </style>


    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>ToDo List App!</title>
</head>
<body style="background-color: #FFB5C0;">
>
    <h1 class="text-center py-4 my-4 todo-title">To-Do List App</h1>

   
    <p class="text-center welcome-text">Welcome, <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Guest'; ?>!</p>

    
    <div class="logout-container">
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>


    <!-- Form to add a new task -->
    <div class="w-50 m-auto">
        <form action="add_task.php" method="post" autocomplete="off">
            <div class="form-group">
                <label for="title">Task Name</label>
                <input class="form-control" type="text" name="task_name" id="title" placeholder="Enter task here..." required>
            </div><br>
            <button class="btn btn-success" type="submit">Add Task</button>
        </form>
    </div><br>

    <hr class="bg-dark w-50 m-auto">

    <!-- Display the user's tasks -->
    <div class="lists w-50 m-auto my-4">
        <h3>Your Tasks</h3>
        <div id="lists">
        <table class="table table-hover custom-table">
                <thead>
                    <tr>
                        <th scope="col">S. No.</th>
                        <th scope="col">Task Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($tasks) {
                        $counter = 1;
                        foreach ($tasks as $task) {
                            $id = $task['id'];
                            $task_name = $task['task_name'];
                            $is_completed = $task['is_completed'];
                            ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($task_name); ?></td>
                                <td>
                                    <?php echo $is_completed ? 'Completed' : 'Pending'; ?>
                                </td>
                                <td>
                             <div class="btn-group" role="group">
                                 <!-- Mark Complete Button -->
                                    <a class="btn btn-success btn-sm me-2" href="complete_task.php?id=<?php echo $id; ?>" role="button">
                                     <?php echo $is_completed ? 'Mark Incomplete' : 'Mark Complete'; ?>
                                    </a>

                                <!-- Edit Button -->
                                    <a class="btn btn-primary btn-sm me-2" href="edit_task.php?id=<?php echo $id; ?>" role="button">Edit</a>

                                <!-- Delete Button -->
                                 <form action="delete_task.php" method="post" onsubmit="return confirm('Are you sure you want to delete this task?');" style="display:inline;">
                                 <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
    </div>
</td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='4'>No tasks found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>
</html>