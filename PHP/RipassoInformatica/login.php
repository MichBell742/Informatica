<?php

/*
SE STAI FACENDO LA REGISTRAZIONE PER LA PASSWORD è UTILE SAPERE:
    
    $password = inputUtente;
    $hash = password_hash($password, PASSWORD_DEFAULT);

*/

require 'db.php';
session_start();
echo "<br>------------------------------------<br>";
if($_SERVER['REQUEST_METHOD']=="POST"){
    //-----metodo soggetto ad SQL Injection
    //$email=$_POST['email'];
    //$psw=$_POST['password'];
    //----metodo vecchio ma funzionale
    $email=mysqli_real_escape_string($conn, $_POST['email']);
    $psw=mysqli_real_escape_string($conn, $_POST['password']);
    $sql="SELECT * FROM utenti WHERE email='$email'";
    $result=mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);
        if(password_verify($psw, $row['psw'])){
            echo "password corretta, bravo";
            $_SESSION=$row;
            print_r($_SESSION);
            header('Location: home.php');
        }else{
            echo "la password non è corretta";
            setcookie("tentativi", isset($_COOKIE['tentativi']) ? $_COOKIE['tentativi']+1 : 1, time()+24*60*60);
            if(isset($_COOKIE['tentativi'])){
                if($_COOKIE['tentativi']>=2){
                    echo "attenzione hai gia sbagliato la password una volta";
                }
            }
        }
    }else{
        echo "l'email non esiste nel DB";
    }
}else{
    header('Location: login.html');
}

?>