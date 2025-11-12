<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login Form</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="login form">
      <header>Login</header>
      <form action="#">
        <input type="text" placeholder="Enter your email" required>
        <input type="password" placeholder="Enter your password" required>
        <a href="#">Forgot password?</a>
        <input type="button" class="button" value="Login">
        <div class="signup">
          <span>Don't have an account?
            <a href="register.php">Signup</a>
          </span>
        </div>
      </form>
    </div>
  </div>
</body>
</html>