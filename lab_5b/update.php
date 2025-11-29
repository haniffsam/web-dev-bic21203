// update.php (FIXED)
<?php
include 'session_check.php';
include 'Database.php';
include 'User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    // Capture the result (User.php's updateUser returns true on success)
    $result = $user->updateUser($matric, $name, $role); 

    $db->close();

    // --- FIX START: Check result and redirect ---
    if ($result === true) {
        // Redirect upon successful update
        header("Location: read.php?msg=updated");
        exit;
    } else {
        echo "Error updating user: " . $result;
    }
    // --- FIX END ---
}