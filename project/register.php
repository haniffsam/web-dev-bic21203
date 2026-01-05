<?php
include 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $badge_id   = mysqli_real_escape_string($conn, $_POST['badge_id']);
    
    // Encrypting password for cybersecurity objectives 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $first_name . " " . $last_name;

    // Hardcoded role as 'Engineer' 
    $sql = "INSERT INTO users (username, password, full_name, role, badge_id) 
            VALUES ('$username', '$password', '$full_name', 'Engineer', '$badge_id')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful! You can now login.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - APG Safety System</title>
    <link rel="stylesheet" href="css/style_register.css">
</head>
<body>
<div class="register-container">
    <div class="register-card">
        <h2>Register</h2>
        <div class="title-underline"></div>
        
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="form-group full-width">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group full-width">
                    <label>Badge ID</label>
                    <input type="text" name="badge_id" placeholder="e.g., CI240080" required>
                </div>
                <div class="form-group full-width">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group full-width">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-register">Register as Engineer</button>
            </div>
        </form>
        <div class="auth-footer">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</div>
</body>
</html>