<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lab 5a Q3</title>
</head>
<body>
    <?php

    function calculate_area($width,$height){
        return $width * $height;
    }
    $width = 4;
    $height = 2;
    $area = calculate_area($width, $height);

    echo "<b>The area of a rectangle with a width of $width and height of $height is $area</b>";
    ?>
    
</body>
</html>
