<?php
require_once 'db_connection.php';

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

// 2. Fetch Existing Report Data
if (isset($_GET['id'])) {
    $report_id = intval($_GET['id']);
    $query = "SELECT * FROM reports WHERE id = $report_id";
    $result = mysqli_query($conn, $query);
    $report = mysqli_fetch_assoc($result);

    if (!$report) {
        die("Report not found.");
    }
} else {
    header("Location: view_reports.php");
    exit();
}

// 3. Handle Update Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_report'])) {
    $title = mysqli_real_escape_string($conn, $_POST['report_title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $file = mysqli_real_escape_string($conn, $_POST['file_name']);

    $update_sql = "UPDATE reports SET 
                   report_title = '$title', 
                   description = '$desc', 
                   file_path = '$file' 
                   WHERE id = $report_id";

    if (mysqli_query($conn, $update_sql)) {
        $success = "Report updated successfully!";
        // Refresh local data
        $report['report_title'] = $title;
        $report['description'] = $desc;
        $report['file_path'] = $file;
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}

// Variables for Sidebar Consistency
$username  = $_SESSION['username'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'Engineer';
$badge_id  = $_SESSION['badge_id'] ?? '';
$full_name = $_SESSION['full_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Report - APG Safety</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style_dashboard.css">
    <link rel="stylesheet" href="css/style_create_report.css">
</head>
<body>
    <aside class="sidebar">
        <div class="logo"><i class="fas fa-clipboard-check"></i> <h1>APG Safety</h1></div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i><span>Home</span></a></li>
            <li class="nav-item"><a href="create_report.php" class="nav-link"><i class="fas fa-file-upload"></i><span>Report Upload</span></a></li>
            <li class="nav-item">
                <a href="view_reports.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_reports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-eye"></i>
                    <span>Report View</span>
                </a>
            </li>
            <hr style="border: 0.5px solid #34495e; margin: 10px 20px;">
            <li class="nav-item"><a href="faq.php" class="nav-link"><i class="fas fa-question-circle"></i><span>FAQ</span></a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link"><i class="fas fa-headset"></i><span>Support</span></a></li>
            <li class="nav-item"><a href="profile.php" class="nav-link"><i class="fas fa-user-circle"></i><span>Profile</span></a></li>
        </ul>
        <div class="sidebar-user-section">
            <div class="profile-img"><?php echo strtoupper(substr($username, 0, 2)); ?></div>
            <div class="user-info-sidebar">
                <div class="user-name-sidebar"><?php echo htmlspecialchars($username); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($user_role); ?> | <?php echo htmlspecialchars($badge_id); ?></div>
            </div>
            <a href="logout.php" class="logout-btn-sidebar"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-header">
            <h1>Edit Report</h1>
            <a href="view_reports.php" style="color: #3498db; text-decoration: none; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Back to Archive
            </a>
        </div>

        <div class="upload-container">
            <?php if($success) echo "<div class='status status-approved' style='margin-bottom:20px; padding:15px;'>$success</div>"; ?>
            <?php if($error) echo "<div class='status status-rejected' style='margin-bottom:20px; padding:15px;'>$error</div>"; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Report ID (Read Only)</label>
                    <input type="text" value="<?php echo htmlspecialchars($report['report_no']); ?>" readonly style="background: #eef2f7;">
                </div>

                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($full_name); ?>" readonly style="background: #eef2f7;">
                    </div>
                    <div class="form-group">
                        <label>Badge ID</label>
                        <input type="text" value="<?php echo htmlspecialchars($badge_id); ?>" readonly style="background: #eef2f7;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Report Title</label>
                    <input type="text" name="report_title" value="<?php echo htmlspecialchars($report['report_title']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required><?php echo htmlspecialchars($report['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Current File Path</label>
                    <input type="text" name="file_name" value="<?php echo htmlspecialchars($report['file_path']); ?>" required>
                </div>

                <div class="file-upload-area" onclick="alert('Upload new version active.')">
                    <i class="fas fa-exchange-alt fa-3x" style="color: #3498db; margin-bottom: 10px;"></i>
                    <h3>Click to replace current file</h3>
                    <p>Current: <?php echo htmlspecialchars($report['file_path']); ?></p>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="submit" name="update_report" class="btn-submit" style="flex: 2;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button type="reset" class="btn-submit" style="flex: 1; background: #95a5a6;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>