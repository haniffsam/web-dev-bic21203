<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="registration form">
      <header>Registration</header>
      <form action="#">
        <input type="text" placeholder="Enter your ID number" required>
        <input type="text" placeholder="Enter your name" required>
        <input type="text" placeholder="Enter your email" required>
        <input type="password" placeholder="Create password" required>
        <input type="password" placeholder="Confirm password" required>
        <input type="button" class="button" value="Register">
        <div class="signup">
          <span>Already have an account?
            <a href="login.php">Login</a>
          </span>
        </div>
      </form>
    </div>
  </div>
</body>
</html>