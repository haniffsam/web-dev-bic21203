<?php
// Include session check to restrict access (Question 8)
include 'session_check.php';
include 'Database.php';
include 'User.php';

// Check if the request is GET AND if the 'matric' parameter is present in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['matric'])) {
    
    // Retrieve the matric value from the GET request (This is the primary key for the record)
    $matric_to_update = $_GET['matric'];

    // Create an instance of the Database class and get the connection
    $database = new Database();
    $db = $database->getConnection();

    // Create an instance of the User class and fetch the specific user's details
    $user = new User($db);
    $userDetails = $user->getUser($matric_to_update);

    $db->close();

    // Check if user details were successfully fetched
    if (!$userDetails) {
        // If the matric doesn't exist, redirect back to the user list
        header("Location: read.php?msg=user_not_found");
        exit;
    }

    // Display the update form with the fetched user data (Figure 8)
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update User</title>
    </head>

    <body>
        <h1>Update User</h1> <form action="update.php" method="post">
            
            <label for="matric">Matric</label>
            <input 
                type="text" 
                name="matric" 
                id="matric" 
                value="<?php echo htmlspecialchars($userDetails['matric']); ?>" 
                readonly><br>
            
            <label for="name">Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="<?php echo htmlspecialchars($userDetails['name']); ?>"
                required><br>
            
            <label for="role">Access Level</label>
            <select name="role" id="role" required>
                <option value="lecturer" <?php if ($userDetails['role'] == 'lecturer')
                    echo "selected" ?>>Lecturer</option>
                <option value="student" <?php if ($userDetails['role'] == 'student')
                    echo "selected" ?>>Student</option>
            </select><br>
            
            <input type="submit" value="Update">
            <a href="read.php">Cancel</a> </form>
    </body>

    </html>
    <?php
} else {
    // If matric is missing from the URL (causes the "Undefined array key" warning), redirect to the user list
    header("Location: read.php");
    exit;
}
?>