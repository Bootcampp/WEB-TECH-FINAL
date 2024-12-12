
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Profile</title>
    <link rel="stylesheet" href="../public/css/profile.css">
</head>
<body>
    <div class="profile-container">
        <header class="profile-header">
            <h1>Your Profile</h1>
        </header>

        <section class="profile-card">
            <div class="profile-image">
                <img src="../assets/sarah.jpeg" alt="Profile Picture">
            </div>
            <div class="profile-details">
                <h2>Sarah Lawson</h2>
                <p><strong>Email:</strong> sarahlawson@gmail.com</p>
                <p><strong>Contact Number:</strong> +233 24 123 4567</p>
                <p><strong>Bio:</strong> Passionate bridal designer specializing in elegant and timeless designs for the modern bride.</p>
            </div>
        </section>

        <section class="profile-actions">
            <h3>Edit Profile</h3>
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" value="Sarah Lawson" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="sarahlawson@gmail.com" required>
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" value="+233 24 123 4567" required>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself">Passionate bridal designer specializing in elegant and timeless designs for the modern bride.</textarea>
                </div>

                <div class="form-group">
                    <label for="profile_picture">Update Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>

                <button type="submit" class="btn">Save Changes</button>
            </form>
        </section>
    </div>
</body>
<script src="../public/js/profile.js"></script>
</html>
