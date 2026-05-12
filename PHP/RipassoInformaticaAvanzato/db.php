<?php
$conn=mysqli_connect("localhost","root","20070124","test");
if (!$conn) {
    die("errore di connessione: ". mysqli_connect_error($conn));
}
?>