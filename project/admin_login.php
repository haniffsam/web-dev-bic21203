<?php
// admin_login.php
session_start();

// Simple admin login (for demo only)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Simple credentials check (change these in production!)
    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = 'Admin';
        header('Location: admin.php');
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - APG Safety System</title>
    <link rel="stylesheet" href="css/style_admin_login.css">
</head>
<body class="admin-login-body">
    <div class="login-box">
    <h2>Admin Portal</h2>
    
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="demo">
        <strong>System Access:</strong><br>
        Username: <strong>admin</strong><br>
        Password: <strong>admin123</strong>
    </div>
    
    <form method="POST">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Authorized Login</button>
    </form>

    <div class="back-to-login">
        <a href="login.php">← Back to Engineer Login</a>
    </div>
</div>
</body>
</html>