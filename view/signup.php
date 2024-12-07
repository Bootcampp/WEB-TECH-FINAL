<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - BridalConnect</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
    <div class="signup-container">
        <!-- Left Half: Image -->
        <div class="signup-image">
            <img src="../assets/Bridal.jpeg" alt="Bridal Connect" />
        </div>

        <!-- Right Half: Sign Up Form -->
        <div class="signup-form">
            <h1>Join BridalConnect</h1>
            <p>Create an account to explore bridal collections and more.</p>
            <form action="process-signup.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
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
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit" class="btn">Sign Up</button>
                <p class="login-link">Already have an account? <a href="login.php">Log In</a></p>
            </form>
        </div>
    </div>
</body>
</html>
