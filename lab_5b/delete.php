// delete.php (FIXED)
<?php
include 'session_check.php';
include 'Database.php';
include 'User.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    // Capture the result (User.php's deleteUser returns true on success)
    $result = $user->deleteUser($matric);

    $db->close();
    
    // --- FIX START: Check result and redirect ---
    if ($result === true) {
        // Redirect upon successful deletion
        header("Location: read.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting user: " . $result;
    }
    // --- FIX END ---
} else {
    // If access is improper or matric is missing
    header("Location: read.php");
    exit;
}