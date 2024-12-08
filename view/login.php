<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BridalConnect</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
    <div class="login-container">
        <!-- Left Half: Image -->
        <div class="login-image">
            <img src="../assets/Bridal.jpeg" alt="Bridal Connect" />
        </div>

        <!-- Right Half: Login Form -->
        <div class="login-form">
            <h1>Welcome Back!</h1>
            <p>Log in to access your account and explore bridal collections.</p>
            <form action="../actions/login_user.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Log In</button>
                <p class="register-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>
