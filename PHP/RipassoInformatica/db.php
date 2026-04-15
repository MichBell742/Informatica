<?php
//$nomeVar=msqli_connect("localhost","username","password","nomeDB")
//XAMPP username="root" psw=""

$conn=mysqli_connect("localhost","root","20070124", "login");
if(!$conn){
    die("errore di connessione: ".mysqli_connect_error());
}
?>