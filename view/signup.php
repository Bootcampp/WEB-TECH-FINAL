<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BridalConnect</title>
    <link rel="stylesheet" href="../public/css/register.css">
</head>
<body>
    <div class="login-container">
        <!-- Left Half: Image -->
        <div class="login-image">
            <img src="../assets/Bridal.jpeg" alt="Bridal Connect" />
        </div>

        <!-- Right Half: Signup Form -->
        <div class="login-form">
            <h1>Join Bridal connect</h1>
            <p>Create an account and explore bridal collections.</p>
            <form  id="signupForm" action="../actions/register_user.php" method="POST">
            <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="full_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" id="role_id" required>
                        <option value="1">User</option>
                        <option value="2">Designer</option>
                    </select>
                </div>
                <button type="submit" class="btn">Sign Up</button>
                <p class="register-link">Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>
    </div>
</body>
<script src="../public/js/register.js"></script>
</html>
