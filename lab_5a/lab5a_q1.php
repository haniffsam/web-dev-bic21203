<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lab 5a Q1</title>
</head>
<body>
    <?php 
        $name = "Muhammad Haniff Bin Nor Sam";
        $matricNumber = "ci240071";
        $course = "Bachelor of Computer Science (Information Security) with Honours";
        $yearofStudy = "2nd Year";
        $address = "B-0-2 Desa Bangsawan,<br>Bandar Tun Razak,<br>Cheras 56000,<br>Kuala Lumpur.";
    ?>

    <table>
        <tr>
            <td>Name: </td>
            <td><?php echo "$name"; ?></td> 
        </tr>
        <tr>
            <td>Matric Number: </td>
            <td><?php echo "$matricNumber"; ?></td> 
        </tr>
        <tr>
            <td>Course: </td>
            <td><?php echo "$course"; ?></td> 
        </tr>
        <tr>
            <td>Year of Study: </td>
            <td><?php echo "$yearofStudy"; ?></td> 
        </tr>
        <tr>
            <td>Address: </td>
            <td><?php echo "$address"; ?></td> 
        </tr>
    </table>
    
</body>
</html>