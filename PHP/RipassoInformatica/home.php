<?php
require 'db.php';
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.html");
}
print_r($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>titolo pagina grosso</h1>
    <p>si,io <?php 
        echo "{$_SESSION['username']} ";
    ?> so ntel sito, adesso so felice</p>
</body>
</html>