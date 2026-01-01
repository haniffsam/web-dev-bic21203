<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username   = mysqli_real_escape_string($conn, $_POST['username']);
    $badge_id   = mysqli_real_escape_string($conn, $_POST['badge_id']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']); // Capturing the role
    
    // Encrypting password for cybersecurity objectives 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $full_name = $first_name . " " . $last_name;

    // Insert data into the users table
    $sql = "INSERT INTO users (username, password, full_name, role, badge_id) 
            VALUES ('$username', '$password', '$full_name', '$role', '$badge_id')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful as $role'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>