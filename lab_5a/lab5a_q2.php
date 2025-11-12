<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lab 5a Q2</title>
    <style>
        table{
            border: solid 1px;
        }
        td{
            border: solid 1px;
        }

    </style>
</head>
<body>
    <?php 
        $students = [
            [
                'name' => 'Alice',
                'program' => 'BIP',
                'age' => 21
            ],
            [
                'name' => 'Bob',
                'program' => 'BIS',
                'age' => 20
            ],
            [
                'name' => 'Raju',
                'program' => 'BIT',
                'age' => 22
            ]    
        ];
            echo "<table><tr><td>Name</td><td>Program</td><td>Age</td></tr>";
            foreach($students as $student) {
                echo "<tr>";
                echo "<td class='name-cell'>" . $student['name'] . "</td>";
                echo "<td class='program-cell'>" . $student['program'] . "</td>";
                echo "<td class='age-cell'>" . $student['age'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
    ?>
    
</body>
</html>