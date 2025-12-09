<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1-100</title>
    <style>
        .even { color: red; font-weight: 700; }
        .odd  { color: green; font-weight: 700; font-style: italic; }
    </style>
</head>
<body>
<?php
for ($i = 1; $i <= 100; $i++) {
    if ($i % 2 === 0) {
        echo "<span class='even'>$i</span><br>";
    } else {
        echo "<span class='odd'>$i</span><br>";
    }
}
?>
</body>
</html>