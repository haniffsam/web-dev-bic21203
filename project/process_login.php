<?php
session_start();
include 'db_connection.php'; // Ensure your database connection is established 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic Security: Sanitize inputs to prevent SQL Injection 
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to find user
    $sql = "SELECT id, password, role FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify hashed password for security 
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role']; // e.g., 'Engineer' or 'Operator' 
            
            // Redirect based on role defined in project requirements 
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Password'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location='login.php';</script>";
    }
}
?>