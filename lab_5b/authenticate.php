// authenticate.php (FIXED)
<?php
session_start();

include 'Database.php';
include 'User.php';

if (isset($_POST['submit']) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
    $database = new Database();
    $db = $database->getConnection();

    $matric = $db->real_escape_string($_POST['matric']);
    $password = $db->real_escape_string($_POST['password']);

    if (!empty($matric) && !empty($password)) {
        $user = new User($db);
        $userDetails = $user->getUser($matric);

        // Check if user exists and verify password
        if ($userDetails && password_verify($password, $userDetails['password'])) {
            
            // --- FIX START: Set Session and Redirect ---
            $_SESSION['loggedin'] = true;
            $_SESSION['matric'] = $userDetails['matric'];
            $_SESSION['name'] = $userDetails['name'];
            $_SESSION['role'] = $userDetails['role'];

            // Redirect to the display page (read.php) upon success (Question 6)
            header("Location: read.php");
            exit; // Must exit after header redirection

            // --- FIX END ---
            
        } else {
            // AUTHENTICATION FAILED (Figure 6)
            // You can add a specific session variable for the error to display it neatly in login.php
            echo 'Invalid username or password, try <a href="login.php">login</a> again.';
        }
    } else {
        echo 'Please fill in all required fields.';
    }
    // You need to close the connection only if you haven't exited
    $db->close(); 
}
// The extra closing brace '}' at the end of the file should also be removed if it exists.