<?php
require_once 'db_connection.php';

// 1. Security & Identity Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Automated Values from Session
$full_name = $_SESSION['full_name'] ?? 'User'; 
$badge_id  = $_SESSION['badge_id'] ?? 'CI000000';
$username  = $_SESSION['username'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'Engineer';

$success = "";
$error = "";

// 3. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_report'])) {
    $report_no = "RPT-" . date("Y") . "-" . rand(1000, 9999); 
    $title     = mysqli_real_escape_string($conn, $_POST['report_title']);
    $desc      = mysqli_real_escape_string($conn, $_POST['description']);
    
    // --- FILE UPLOAD LOGIC START ---
    $target_dir = "uploads/"; // Ensure this folder exists and is writable
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $original_file_name = basename($_FILES["report_file"]["name"]);
    $file_extension = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
    
    // Create a unique filename to prevent overwriting
    $new_file_name = $report_no . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_file_name;
    
    $upload_ok = true;

    // Check file size (Example: 100MB limit)
    if ($_FILES["report_file"]["size"] > 104857600) {
        $error = "Sorry, your file is too large (Max 100MB).";
        $upload_ok = false;
    }

    // Allow certain file formats
    $allowed_types = ['pdf', 'docx', 'xlsx'];
    if (!in_array($file_extension, $allowed_types)) {
        $error = "Sorry, only PDF, DOCX, & XLSX files are allowed.";
        $upload_ok = false;
    }

    if ($upload_ok) {
        if (move_uploaded_file($_FILES["report_file"]["tmp_name"], $target_file)) {
            // Use the file path for the database
            $sql = "INSERT INTO reports (report_no, role, report_title, description, file_path, approval_status, report_status) 
                    VALUES ('$report_no', '$user_role', '$title', '$desc', '$target_file', 'Pending', 'Pending Review')";

            if (mysqli_query($conn, $sql)) {
                $success = "Report $report_no submitted successfully with the attached file!";
            } else {
                $error = "Database Error: " . mysqli_error($conn);
            }
        } else {
            $error = "Sorry, there was an error uploading your physical file.";
        }
    }
    // --- FILE UPLOAD LOGIC END ---
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Report - APG Safety</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style_dashboard.css">
    <link rel="stylesheet" href="css/style_create_report.css">
    <style>
        /* Small fix to ensure the file input looks okay */
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo"><i class="fas fa-clipboard-check"></i> <h1>APG Safety</h1></div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i><span>Home</span></a></li>
            <li class="nav-item"><a href="create_report.php" class="nav-link active"><i class="fas fa-file-upload"></i><span>Report Upload</span></a></li>
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
            <h1>Upload Report</h1>
            <p>Identity: <strong><?php echo htmlspecialchars($full_name); ?></strong></p>
        </div>

        <div class="upload-container">
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-error' style='color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 20px;'>$error</div>"; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Full Name (Automated)</label>
                    <input type="text" value="<?php echo htmlspecialchars($full_name); ?>" readonly style="background: #eef2f7; cursor: not-allowed;">
                </div>
                
                <div class="form-group">
                    <label>Badge ID (Automated)</label>
                    <input type="text" value="<?php echo htmlspecialchars($badge_id); ?>" readonly style="background: #eef2f7; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label>Assigned Role (Automated)</label>
                    <input type="text" value="<?php echo htmlspecialchars($user_role); ?>" readonly style="background: #eef2f7; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label>Report Title</label>
                    <input type="text" name="report_title" placeholder="Enter report title" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Provide a detailed description of your findings" required></textarea>
                </div>

                <div class="form-group">
                    <label>Select Report File (.pdf, .docx, .xlsx)</label>
                    <input type="file" name="report_file" required>
                </div>

                <div class="file-upload-area">
                    <i class="fas fa-cloud-upload-alt fa-3x" style="color: #3498db; margin-bottom: 10px;"></i>
                    <h3>File Upload Ready</h3>
                    <p>Accepted: .pdf, .docx, .xlsx | Max: 100MB</p>
                </div>

                <button type="submit" name="submit_report" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Report
                </button>
            </form>
        </div>
    </main>
</body>
</html>