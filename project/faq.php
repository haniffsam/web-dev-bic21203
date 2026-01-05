<?php
// faq.php - Frequently Asked Questions
require_once 'db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Define variables used in the sidebar
$username  = $_SESSION['username'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'Engineer';
$badge_id  = $_SESSION['badge_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ - APG Safety System</title>
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
            <h1>Frequently Asked Questions</h1>
        </div>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="summary-card" style="text-align: left;">
                <h4 style="color:#3498db;"><i class="fas fa-info-circle"></i> Why are some fields in the Upload form uneditable?</h4>
                <p>To ensure data integrity, fields like <strong>Full Name</strong>, <strong>Badge ID</strong>, and <strong>Role</strong> are automated based on your profile session. If this information is incorrect, please update your details in the <strong>Profile</strong> section.</p>
            </div>

            <div class="summary-card" style="text-align: left;">
                <h4 style="color:#3498db;"><i class="fas fa-tasks"></i> What do the approval statuses mean?</h4>
                <p>
                    <strong>Pending:</strong> The report is in the queue for Admin review.<br>
                    <strong>Approved:</strong> The report meets safety standards.<br>
                    <strong>Rejected:</strong> Revisions are required. View <strong>Admin Notes</strong> in the Report View for specific feedback.
                </p>
            </div>

            <div class="summary-card" style="text-align: left;">
                <h4 style="color:#3498db;"><i class="fas fa-edit"></i> How can I modify or delete a report?</h4>
                <p>Navigate to <strong>Report View</strong>. You will find an <strong>EDIT</strong> link to update report details and a <strong>DELETE</strong> link to remove a submission permanently.</p>
            </div>

            <div class="summary-card" style="text-align: left;">
                <h4 style="color:#3498db;"><i class="fas fa-file-alt"></i> Where can I see Admin feedback?</h4>
                <p>Go to the <strong>Report View</strong> archive. The <strong>Admin Notes</strong> column displays italicized comments from the operator regarding your submission.</p>
            </div>
        </div>
    </main>
</body>
</html>