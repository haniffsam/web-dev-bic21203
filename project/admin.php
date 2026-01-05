<?php
// admin.php - Main Admin Page
require_once 'db_connection.php';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_status'])) {
        $id = $_POST['id'];
        $approval_status = mysqli_real_escape_string($conn, $_POST['approval_status']);
        $report_status = isset($_POST['report_status']) ? mysqli_real_escape_string($conn, $_POST['report_status']) : '';
        $admin_notes = mysqli_real_escape_string($conn, $_POST['admin_notes']);
        
        $sql = "UPDATE reports SET 
                approval_status = '$approval_status',
                report_status = '$report_status',
                admin_notes = '$admin_notes'
                WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Report updated successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
    
    if (isset($_POST['delete_report'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM reports WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Report deleted successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Get all reports from database
$sql = "SELECT * FROM reports ORDER BY report_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Report Management</title>
    <link rel="stylesheet" href="css/style_admin.css">
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Admin Dashboard - Report Management System</h1>
        <div class="admin-info">
            <span>Welcome, Admin!</span>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container">
        <!-- Stats Cards -->
        <div class="stats">
            <?php
            // Get counts for stats
            $total_sql = "SELECT COUNT(*) as total FROM reports";
            $pending_sql = "SELECT COUNT(*) as pending FROM reports WHERE approval_status = 'Pending'";
            $approved_sql = "SELECT COUNT(*) as approved FROM reports WHERE approval_status = 'Approved'";
            $rejected_sql = "SELECT COUNT(*) as rejected FROM reports WHERE approval_status = 'Rejected'";
            
            $total_result = mysqli_query($conn, $total_sql);
            $pending_result = mysqli_query($conn, $pending_sql);
            $approved_result = mysqli_query($conn, $approved_sql);
            $rejected_result = mysqli_query($conn, $rejected_sql);
            
            $total = mysqli_fetch_assoc($total_result)['total'];
            $pending = mysqli_fetch_assoc($pending_result)['pending'];
            $approved = mysqli_fetch_assoc($approved_result)['approved'];
            $rejected = mysqli_fetch_assoc($rejected_result)['rejected'];
            ?>
            
            <div class="stat-card">
                <h3>Total Reports</h3>
                <div class="number"><?php echo $total; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Pending Review</h3>
                <div class="number"><?php echo $pending; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Approved</h3>
                <div class="number"><?php echo $approved; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Rejected</h3>
                <div class="number"><?php echo $rejected; ?></div>
            </div>
        </div>
        
        <!-- Success/Error Messages -->
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Approval Reports Table -->
        <div class="table-section">
            <h2>Approval Report / Status</h2>
            
            <table>
                <tr>
                    <th>Report No.</th>
                    <th>Date</th>
                    <th>Description/Notes</th>
                    <th>View Report</th>
                    <th>Approval Status</th>
                    <th>Actions</th>
                </tr>
                
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong><?php echo $row['report_no']; ?></strong></td>
                        <td><?php echo date('d/m/Y', strtotime($row['report_date'])); ?></td>
                        <td><?php echo $row['description'] ?: '-'; ?></td>
                        <td>
                            <?php if (!empty($row['file_path'])): ?>
                                <a href="view_file.php?id=<?php echo $row['id']; ?>" class="view-btn" target="_blank">View</a>
                            <?php else: ?>
                                <span style="color: #999;">No file</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status status-<?php echo strtolower(str_replace(' ', '-', $row['approval_status'])); ?>">
                                <?php echo $row['approval_status']; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" style="display: flex; gap: 10px; flex-direction: column;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                
                                <label>Approval Status:</label>
                                <select name="approval_status">
                                    <option value="Pending" <?php echo $row['approval_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo $row['approval_status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo $row['approval_status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                    <option value="In Progress" <?php echo $row['approval_status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                </select>

                                <label>Report Status:</label>
                                <select name="report_status">
                                    <option value="Draft" <?php echo $row['report_status'] == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="Complies" <?php echo $row['report_status'] == 'Complies' ? 'selected' : ''; ?>>Complies</option>
                                    <option value="Pending Review" <?php echo $row['report_status'] == 'Pending Review' ? 'selected' : ''; ?>>Pending Review</option>
                                </select>
                                
                                <textarea name="admin_notes" placeholder="Admin notes..."><?php echo $row['admin_notes']; ?></textarea>
                                
                                <button type="submit" name="update_status" class="btn btn-update">Update Status</button>
                                
                                <button type="submit" name="delete_report" class="btn btn-delete" 
                                        onclick="return confirm('Are you sure you want to delete this report?')">
                                    Delete Report
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #999;">
                            No reports found in the system.
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <!-- Status of Report Table -->
        <div class="table-section">
            <h2>Status of Report</h2>
            
            <table>
                <tr>
                    <th>Report No.</th>
                    <th>Date</th>
                    <th>Description/Notes</th>
                    <th>View Report</th>
                    <th>Status Report</th>
                </tr>
                
                <?php 
                // Reset pointer and fetch again
                mysqli_data_seek($result, 0);
                if (mysqli_num_rows($result) > 0): 
                ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['report_no']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['report_date'])); ?></td>
                        <td><?php echo $row['description'] ?: '-'; ?></td>
                        <td>
                            <?php if (!empty($row['file_path'])): ?>
                                <a href="view_file.php?id=<?php echo $row['id']; ?>" class="view-btn" target="_blank">View</a>
                            <?php else: ?>
                                <span style="color: #999;">No file</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            $status_text = $row['report_status'];
                            $status_class = '';
                            if ($status_text == 'Draft') $status_class = 'status-pending';
                            if ($status_text == 'Complies') $status_class = 'status-approved';
                            if ($status_text == 'Pending Review') $status_class = 'status-in-progress';
                            ?>
                            <span class="status <?php echo $status_class; ?>">
                                <?php echo $status_text; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                            No reports found.
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    
    <script>
        // Simple confirmation for delete
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>