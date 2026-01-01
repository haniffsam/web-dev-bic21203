<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - APG Safety System</title>
    <style>
        body { font-family: 'Times New Roman', serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        /* Header matching Figure 5 wireframe */
        .header { background-color: #999; padding: 10px; text-align: center; position: relative; }
        .user-info { font-size: 14px; font-weight: bold; }
        
        /* Main Login Container */
        .login-container { width: 400px; margin: 100px auto; background: white; padding: 40px; border: 1px solid #ccc; text-align: center; }
        h2 { border-bottom: 1px solid #333; padding-bottom: 10px; margin-bottom: 30px; }
        
        .input-group { margin-bottom: 20px; text-align: left; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { 
            width: 100%; padding: 12px; border: none; border-radius: 20px; background-color: #aaa; color: white;
        }
        
        .forgot-pass { color: blue; font-size: 12px; text-decoration: none; display: block; margin: 10px 0; }
        
        .btn-signin { 
            background-color: #888; color: black; padding: 10px 40px; border: none; border-radius: 15px; cursor: pointer; font-weight: bold;
        }
        
        .btn-register { 
            position: fixed; bottom: 20px; right: 20px; background-color: #888; padding: 10px 20px; border-radius: 15px; text-decoration: none; color: black; font-size: 12px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>LOGIN</h2>
    
    <form action="process_login.php" method="POST">
        <div class="input-group">
            <label>Username:</label> 
            <input type="text" name="username" required>
        </div>
        
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit" class="btn-signin">Sign In</button>
    </form>
</div>

<a href="register.php" class="btn-register">REGISTER</a> 

</body>
</html>