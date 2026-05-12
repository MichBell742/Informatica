<?php
    require "psw.php";
    $conn = mysqli_connect('localhost', 'root', PSW_DB, 'authshop');

    if (!$conn) {
        die('Connessione fallita: ' . mysqli_connect_error());
    }

    function purifyString($conn, $strings) {
        $purifiedValues;
        foreach ($strings as $key => $value) {
            $purifiedValues[$key]=mysqli_real_escape_string($conn, $value);
        }
        return $purifiedValues;
    }
?>