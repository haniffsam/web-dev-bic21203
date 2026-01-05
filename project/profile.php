<?php
// profile.php - User Profile Management
require_once 'db_connection.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// FIX: Define these variables from the session so the sidebar can find them
$user_id   = $_SESSION['user_id'];
$username  = $_SESSION['username'] ?? 'User'; 
$user_role = $_SESSION['role'] ?? 'Engineer';
$badge_id  = $_SESSION['badge_id'] ?? '';
$success = "";
$error = "";

// 2. Fetch Live User Data from the 'users' table
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

// 3. Handle Password Update (Security Objective)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password = '$hashed_pass' WHERE id = '$user_id'";
        
        if (mysqli_query($conn, $update_sql)) {
            $success = "Password updated successfully!";
        } else {
            $error = "Error updating password: " . mysqli_error($conn);
        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - APG Safety System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style_dashboard.css">
    <link rel="stylesheet" href="css/style_profile.css">
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-clipboard-check"></i>
            <h1>APG Safety</h1>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="create_report.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'create_report.php' ? 'active' : ''; ?>">
                    <i class="fas fa-file-upload"></i>
                    <span>Report Upload</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="view_reports.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_reports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-eye"></i>
                    <span>Report View</span>
                </a>
            </li>

            <hr style="border: 0.5px solid #34495e; margin: 10px 20px;">

            <li class="nav-item">
                <a href="faq.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'faq.php' ? 'active' : ''; ?>">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                    <i class="fas fa-headset"></i>
                    <span>Support</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="profile.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-user-section">
            <div class="profile-img">
                <?php echo strtoupper(substr($username, 0, 2)); ?>
            </div>
            <div class="user-info-sidebar">
                <div class="user-name-sidebar"><?php echo htmlspecialchars($username); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($user_role); ?> | <?php echo htmlspecialchars($badge_id); ?></div>
            </div>
            <a href="logout.php" class="logout-btn-sidebar" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-header">
            <div>
                <h1 style="font-size: 28px;">Account Settings</h1>
            </div>
        </div>

        <div class="profile-grid">
            <div class="profile-card">
                <div class="card-header" style="color: #3498db;">
                    <i class="fas fa-id-card"></i>
                    <h3>Personal Information</h3>
                </div>
                
                <div class="info-group">
                    <span class="info-label">Full Name</span>
                    <div class="info-data"><?php echo htmlspecialchars($user_data['full_name']); ?></div>
                </div>
                
                <div class="info-group">
                    <span class="info-label">Username</span>
                    <div class="info-data"><?php echo htmlspecialchars($user_data['username']); ?></div>
                </div>
                
                <div class="info-group">
                    <span class="info-label">Badge ID</span>
                    <div class="info-data"><?php echo htmlspecialchars($user_data['badge_id']); ?></div>
                </div>
            </div>

            <div class="profile-card security-card">
                <div class="card-header" style="color: #e74c3c;">
                    <i class="fas fa-user-shield"></i>
                    <h3>Security Update</h3>
                </div>
                
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" class="profile-form">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" placeholder="Enter new password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
                    </div>
                    
                    <button type="submit" name="update_password" class="btn-update">
                        <i class="fas fa-save"></i>
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>