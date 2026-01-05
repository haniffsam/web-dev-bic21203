<?php
// dashboard.php - Integrated Engineer Dashboard
require_once 'db_connection.php';

// Variables from Session
$username  = $_SESSION['username'] ?? 'User'; // Updated to use username
$user_role = $_SESSION['role'] ?? 'Engineer';
$badge_id  = $_SESSION['badge_id'] ?? '';

// 1. Security Check: Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Variables from Session
$full_name = $_SESSION['full_name'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'Engineer';
$badge_id  = $_SESSION['badge_id'] ?? '';
$user_id   = $_SESSION['user_id'];

// 3. Dynamic Stats from your 'reports' table
$total_reports = 0;
$approved_count = 0;
$pending_count = 0;
$drafts_count = 0;
$rejected_count = 0;
$compliance_rate = 0;

// Get Total Count
$total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM reports");
if ($total_res) {
    $total_reports = mysqli_fetch_assoc($total_res)['total'];
}

// Get Approved Count
$approved_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM reports WHERE approval_status = 'Approved'");
if ($approved_res) {
    $approved_count = mysqli_fetch_assoc($approved_res)['total'];
}

// Get Pending Count
$pending_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM reports WHERE approval_status = 'Pending'");
if ($pending_res) {
    $pending_count = mysqli_fetch_assoc($pending_res)['total'];
}

// Get Drafts and Rejections for Shortcuts
$drafts_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM reports WHERE report_status = 'Draft'");
$drafts_count = mysqli_fetch_assoc($drafts_res)['total'];

$rejected_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM reports WHERE approval_status = 'Rejected'");
$rejected_count = mysqli_fetch_assoc($rejected_res)['total'];

// Calculate Compliance Rate
$complies_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM reports WHERE report_status = 'Complies'");
if ($complies_res) {
    $complies_count = mysqli_fetch_assoc($complies_res)['total'];
    $compliance_rate = ($total_reports > 0) ? round(($complies_count / $total_reports) * 100) : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engineer Dashboard - APG Safety System</title>
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
            <div>
                <h1 style="font-size: 28px;">Welcome, <span style="color: #3498db;"><?php echo htmlspecialchars($full_name); ?></span></h1>
            </div>
            <div>
                <span class="status-dot"></span>
                <small>System Online: apg_safety_system</small>
            </div>
        </div>

        <div class="shortcut-container">
            <div class="shortcut-card" style="border-left: 5px solid #f1c40f;">
                <h4 style="color: #7f8c8d; font-size: 12px;">DRAFTS TO FINALIZE</h4>
                <p style="font-size: 24px; font-weight: bold;"><?php echo $drafts_count; ?></p>
            </div>
            <div class="shortcut-card" style="border-left: 5px solid #e74c3c;">
                <h4 style="color: #7f8c8d; font-size: 12px;">REVISIONS NEEDED</h4>
                <p style="font-size: 24px; font-weight: bold;"><?php echo $rejected_count; ?></p>
            </div>
        </div>

        <section class="summary-section">
            <div class="summary-cards">
                <div class="summary-card">
                    <div style="color: #3498db; margin-bottom: 10px;"><i class="fas fa-file-alt fa-2x"></i></div>
                    <div style="font-size: 12px; color: #7f8c8d;">TOTAL REPORTS</div>
                    <div class="card-value"><?php echo $total_reports; ?></div>
                </div>
                <div class="summary-card">
                    <div style="color: #2ecc71; margin-bottom: 10px;"><i class="fas fa-check-circle fa-2x"></i></div>
                    <div style="font-size: 12px; color: #7f8c8d;">APPROVED</div>
                    <div class="card-value"><?php echo $approved_count; ?></div>
                </div>
                <div class="summary-card">
                    <div style="color: #f1c40f; margin-bottom: 10px;"><i class="fas fa-clock fa-2x"></i></div>
                    <div style="font-size: 12px; color: #7f8c8d;">PENDING</div>
                    <div class="card-value"><?php echo $pending_count; ?></div>
                </div>
                <div class="summary-card">
                    <div style="color: #9b59b6; margin-bottom: 10px;"><i class="fas fa-percentage fa-2x"></i></div>
                    <div style="font-size: 12px; color: #7f8c8d;">COMPLIANCE RATE</div>
                    <div class="card-value"><?php echo $compliance_rate; ?>%</div>
                </div>
            </div>
        </section>

        <section class="recent-activity" id="activityTable">
            <h2 style="margin-bottom: 20px; color: #2c3e50;"><i class="fas fa-history"></i> Recent Reports</h2>
            <div class="activity-table">
                <table>
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>REPORT NO.</th>
                            <th>APPROVAL STATUS</th>
                            <th>REPORT STATUS</th>
                            <th>ADMIN NOTES</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = "SELECT * FROM reports ORDER BY report_date DESC LIMIT 5";
                        $activities = mysqli_query($conn, $query);
                        
                        if ($activities && mysqli_num_rows($activities) > 0):
                            while($row = mysqli_fetch_assoc($activities)): 
                                $status = $row['approval_status'];
                                $class = ($status == 'Approved') ? 'status-approved' : (($status == 'Rejected') ? 'status-rejected' : 'status-pending');
                        ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($row['report_date'])); ?></td>
                                <td><strong><?php echo htmlspecialchars($row['report_no']); ?></strong></td>
                                <td><span class="status <?php echo $class; ?>"><?php echo $status; ?></span></td>
                                <td><?php echo htmlspecialchars($row['report_status']); ?></td>
                                <td style="font-size: 12px; color: #666;">
                                    <?php echo !empty($row['admin_notes']) ? substr($row['admin_notes'], 0, 30) . '...' : '<em>None</em>'; ?>
                                </td>
                                <td><a href="edit_report.php?id=<?php echo $row['id']; ?>" class="action-link">EDIT</a></td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="6" style="text-align: center;">No reports found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <footer style="margin-top: 40px; text-align: center; color: #7f8c8d; font-size: 13px; padding-bottom: 20px;">
            <p>APG Safety System Dashboard &copy; 2025</p>
        </footer>
    </main>
</body>
</html>