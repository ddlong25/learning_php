<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Tài liệu học lập trình web</h1>
    <?php
    echo "<hr>";
    ?>
    <P>Tài liệu học HTML</P>
    <p>Tài liệu học CSS</p>
    <?php
    echo "<h2> Tài liệu học JavaScript</h2>";
    echo "<h3> Tài liệu học PHP</h3>";
    echo "<h3> Tài liệu học MySQL</h3>";
    ?>
    <hr>
    <?php
    $text="Từ cơ bản"." "." đến nâng cao";
    echo $text . "<br>";
    ?>

<?php
function showValue(){
    $a = 5;
    echo "Giá trị của a là: ";
    echo $a . "<br>";
}
showValue();  // Sửa: gọi hàm đúng
// Xóa: echo $a;  vì $a không tồn tại ngoài hàm
$a = 1;
$b = 2;
function sum(){
    global $a, $b;
    $b = $a + $b;
}
sum();
echo $b . "<br>";  // Kết quả: 3
?>
</body>
</html>