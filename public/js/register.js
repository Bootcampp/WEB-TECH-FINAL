document.getElementById('signupForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission until validation passes

    // Get form field values
    const fullName = document.getElementById('fullname').value.trim();
    // const lastName = document.getElementById('lastname').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // expression for email validation (must end with @gmail.com)
    const emailRegex = /^[^\s@]+@gmail\.com$/;
    
    // Password validation: At least 1 uppercase, 3 digits, 1 special character, and minimum 8 characters
    const passwordRegex = /^(?=.*[A-Z])(?=(.*\d){3,})(?=.*[!@#\$%\^&\*\|])[A-Za-z\d!@#\$%\^&\*\|]{8,}$/;

    // Check if all fields are filled
    if (!fullName || !email || !password || !confirmPassword) {
        alert('All fields are required.');
        return;
    }

    // Validate email format
    if (!emailRegex.test(email)) {
        alert('Email must end with @gmail.com.');
        return;
    }

    // Validate password strength
    if (!passwordRegex.test(password)) {
        alert('Password must be at least 8 characters long, contain an uppercase letter, at least 3 digits, and a special character.');
        return;
    }

    // Check if passwords match
    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    // If all validation passes, submit the form
    this.submit(); 
});
