<?php
session_start();
include "db.php";

if (isset($_POST['uname']) && isset($_POST['password'])) { 

    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']); 

    if (empty($uname)) {
        header("Location: index.php?error=User Name is required");
        exit();
    } elseif (empty($pass)) {
        header("Location: index.php?error=Password is required");
        exit();
    } else {
        // Prepare the SQL query to avoid SQL injection
        $sql = "SELECT * FROM users_list WHERE user_name=?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $uname);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                // Debugging: Check fetched user data
                echo "<pre>Fetched User Data: ";
                print_r($row);
                echo "</pre>";

                // Check if the password matches (assuming passwords are hashed)
                if (password_verify($pass, $row['password'])) {
                    // Set session variables
                    $_SESSION['user_name'] = $row['user_name'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['id'] = $row['id'];

                    // Debugging: Check session variables
                    echo "<pre>Session Variables: ";
                    print_r($_SESSION);
                    echo "</pre>";

                    // Redirect to home.php
                    header("Location: home.php");
                    exit();
                } else {
                    header("Location: index.php?error=Incorrect Username or Password");
                    exit();
                }
            } else {
                header("Location: index.php?error=Incorrect Username or Password");
                exit();
            }
        } else {
            die("Query preparation failed: " . mysqli_error($conn));
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>