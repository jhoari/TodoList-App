<?php
session_start();
include "db.php";

if (isset($_POST['uname']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['re_password'])) {

    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);
    $re_pass = validate($_POST['re_password']);
    $name = validate($_POST['name']);

    $user_data = 'uname=' . urlencode($uname) . '&name=' . urlencode($name);

    if (empty($uname)) {
        header("Location: signup.php?error=User Name is required&$user_data");
        exit();
    } elseif (empty($pass)) {
        header("Location: signup.php?error=Password is required&$user_data");
        exit();
    } elseif (empty($re_pass)) {
        header("Location: signup.php?error=Re-enter password is required&$user_data");
        exit();
    } elseif (empty($name)) {
        header("Location: signup.php?error=Name is required&$user_data");
        exit();
    } elseif ($pass !== $re_pass) {
        header("Location: signup.php?error=Passwords do not match&$user_data");
        exit();
    } else {
        // Hash the password
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // Insert user data into database
        $sql = "INSERT INTO users_list (user_name, password, name) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $uname, $hashed_pass, $name);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $user_id = mysqli_insert_id($conn);
                $_SESSION['id'] = $user_id;
                $_SESSION['user_name'] = $uname;
                $_SESSION['name'] = $name;

                header("Location: home.php");
                exit();
            } else {
                header("Location: signup.php?error=An unknown error occurred&$user_data");
                exit();
            }
        } else {
            header("Location: signup.php?error=Database error&$user_data");
            exit();
        }
    }
} else {
    header("Location: signup.php");
    exit();
}
?>
