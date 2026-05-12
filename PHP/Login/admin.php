<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        require 'const.php';
        checkPermition('a');
    ?>
</head>
<body>
    <a href=<?php echo DIR_CONST."?logout"?>>LOGOUT</a>
</body>
</html>