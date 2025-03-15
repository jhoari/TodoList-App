<!DOCTYPE html>
<html>
<head>
    <title>SIGN UP</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="signup-check.php" method="post" onsubmit="return validateForm()">
        <h2>SIGN UP</h2>

        <!-- Display error messages -->
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>

        <!-- Display success messages -->
        <?php if (isset($_GET['success'])) { ?>
            <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php } ?>

        <!-- Name Field -->
        <label>Name</label>
        <input type="text" 
               name="name" 
               placeholder="Name"
               value="<?php if (isset($_GET['name'])) echo htmlspecialchars($_GET['name']); ?>"><br>

        <!-- Username Field -->
        <label>User Name</label>
        <input type="text" 
               name="uname" 
               placeholder="User Name"
               value="<?php if (isset($_GET['uname'])) echo htmlspecialchars($_GET['uname']); ?>"><br>

        <!-- Password Field -->
        <label>Password</label>
        <input type="password" 
               name="password" 
               placeholder="Password"><br>

        <!-- Re-enter Password Field -->
        <label>Re Password</label>
        <input type="password" 
               name="re_password" 
               placeholder="Re-enter Password"><br>

        <!-- Submit Button -->
        <button type="submit">Sign Up</button>

        <!-- Link to Login Page -->
        <a href="index.php" class="ca">Already have an account?</a>
    </form>

    <!-- JavaScript for Client-Side Validation -->
    <script>
        function validateForm() {
            const password = document.querySelector('input[name="password"]').value;
            const rePassword = document.querySelector('input[name="re_password"]').value;

            if (password !== rePassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>