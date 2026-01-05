<?php
include 'db_connection.php'; // Ensure this file starts the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // FIXED: Added username, full_name, and badge_id to the SELECT query
    $sql = "SELECT id, username, password, role, full_name, badge_id FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            if ($user['role'] == 'Engineer') {
                // SUCCESS: Now all these variables actually exist in the $user array
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username']; 
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['badge_id']  = $user['badge_id'];
                $_SESSION['role']      = $user['role'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<script>alert('Operators must use the Admin Portal link below.'); window.location='login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid Password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - APG Safety System</title>
    <link rel="stylesheet" href="css/style_login.css">
</head>
<body>
<div class="login-container">
    <div class="auth-card"> <h2>Login</h2>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-primary">Sign In</button>
                <a href="admin_login.php" class="btn-admin">Admin Portal</a>
            </div>
        </form>
        <div class="auth-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</div>
</body>
</html>