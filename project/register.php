<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - APG Safety System</title>
    <style>
        body { font-family: 'Times New Roman', serif; background-color: #ffffff; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; text-align: center; }
        
        h2 { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 2px; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: left; margin-bottom: 20px; }
        .full-width { grid-column: span 2; }
        
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        
        input[type="text"], input[type="password"], input[type="email"], select {
            width: 100%; padding: 12px; border: none; border-radius: 25px; background-color: #999; color: white; box-sizing: border-box;
        }

        .dob-group { display: flex; gap: 10px; }
        .dob-group select { background-color: #999; border-radius: 15px; padding: 8px; }

        .next-btn {
            float: right; margin-top: 30px; background-color: #999; color: black; padding: 10px 40px;
            border: none; border-radius: 20px; cursor: pointer; font-weight: bold; text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>REGISTER</h2>
    
    <form action="process_register.php" method="POST">
        <div class="form-grid">
            <div>
                <label>FIRST NAME:</label>
                <input type="text" name="first_name" required>
            </div>
            <div>
                <label>LAST NAME:</label>
                <input type="text" name="last_name" required>
            </div>
            
            <div class="full-width">
                <label>USERNAME:</label>
                <input type="text" name="username" required>
            </div>

            <div class="full-width">
                <label>BADGE ID:</label>
                <input type="text" name="badge_id" placeholder="e.g., CI240080" required>
            </div>
            
            <div class="full-width">
                <label>PASSWORD:</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="full-width">
                <label>SYSTEM ROLE:</label>
                <select name="role" required style="width: 100%; padding: 12px; border-radius: 25px; background-color: #999; color: white; border: none;">
                <option value="" disabled selected>Select your role</option>
                <option value="Engineer">Engineer (Submit Reports)</option>
                <option value="Operator">Operator (Verify Compliance)</option>
                </select>
            </div>
            
            <div class="full-width">
                <label>EMAIL:</label>
                <input type="email" name="email" required>
            </div>
        </div>
        
        <button type="submit" class="next-btn">NEXT</button>
    </form>
</div>

</body>
</html>