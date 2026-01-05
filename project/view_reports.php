<?php
// view_reports.php - Dedicated Report Archive
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Session Identity for Sidebar
$username  = $_SESSION['username'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'Engineer';
$badge_id  = $_SESSION['badge_id'] ?? '';
$full_name = $_SESSION['full_name'] ?? 'User';

$success = "";
$error = "";

// Handle Delete Action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    if (mysqli_query($conn, "DELETE FROM reports WHERE id = $delete_id")) {
        $success = "Report deleted successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// Fetch Reports
$results = mysqli_query($conn, "SELECT * FROM reports ORDER BY report_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report View - APG Safety</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style_dashboard.css">
    <style>
        .table-container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .notes-text { font-size: 13px; color: #666; font-style: italic; max-width: 200px; }
        .delete-link { color: #e74c3c; font-weight: bold; margin-left: 10px; text-decoration: none; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo"><i class="fas fa-clipboard-check"></i> <h1>APG Safety</h1></div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i><span>Home</span></a></li>
            <li class="nav-item"><a href="create_report.php" class="nav-link"><i class="fas fa-file-upload"></i><span>Report Upload</span></a></li>
            <li class="nav-item"><a href="view_reports.php" class="nav-link active"><i class="fas fa-eye"></i><span>Report View</span></a></li>
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
        <div class="top-header"><h1>Report Archive</h1></div>

        <?php if($success) echo "<div class='status status-approved' style='margin-bottom:20px; padding:15px;'>$success</div>"; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>REPORT NO.</th>
                        <th>TITLE</th>
                        <th>APPROVAL</th>
                        <th>STATUS</th>
                        <th>ADMIN NOTES</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($results)): 
                        // FIXED: Removed space from array key
                        $class = ($row['approval_status'] == 'Approved') ? 'status-approved' : (($row['approval_status'] == 'Rejected') ? 'status-rejected' : 'status-pending');
                    ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($row['report_date'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['report_no']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['report_title']); ?></td>
                        <td><span class="status <?php echo $class; ?>"><?php echo $row['approval_status']; ?></span></td>
                        <td><?php echo htmlspecialchars($row['report_status']); ?></td>
                        <td class="notes-text"><?php echo !empty($row['admin_notes']) ? htmlspecialchars($row['admin_notes']) : 'No notes'; ?></td>
                        <td>
                            <a href="edit_report.php?id=<?php echo $row['id']; ?>" class="action-link">EDIT</a>
                            <a href="view_reports.php?delete_id=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Delete this report?')">DELETE</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>