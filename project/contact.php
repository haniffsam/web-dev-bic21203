<?php
// contact.php - Contact & Support Page
require_once 'db_connection.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$username  = $_SESSION['username'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'Engineer';
$badge_id  = $_SESSION['badge_id'] ?? '';
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs to prevent SQL Injection
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // INSERT QUERY
    $sql = "INSERT INTO support_tickets (user_id, username, subject, message) 
            VALUES ('$user_id', '$username', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
        $success = "Your support request has been recorded in the database! Ticket ID: " . mysqli_insert_id($conn);
    } else {
        $error = "Error: Could not save your request. " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support - APG Safety System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style_dashboard.css">
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
            <h1>Support Center</h1>
        </div>

        <div class="summary-card" style="max-width: 600px; text-align: left; margin: 0 auto;">
            <?php if($success) echo "<p style='color: #28a745; background: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>$success</p>"; ?>
            <?php if($error) echo "<p style='color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>$error</p>"; ?>
            
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Subject</label>
                    <input type="text" name="subject" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Message</label>
                    <textarea name="message" rows="5" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;"></textarea>
                </div>
                <button type="submit" class="action-link" style="background:#3498db; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer; width:100%;">
                    Submit Support Ticket
                </button>
            </form>
        </div>
    </main>
</body>
</html>