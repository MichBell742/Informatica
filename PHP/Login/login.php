<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php
        require 'const.php';
        $_SESSION["return_status"]="";
        //connessione db
        $conn = mysqli_connect("localhost","root","20070124","login");
        if(!$conn){
            die("errore connessione DB" .mysqli_connect_error());
        }
        //verifica delle credenziali
        if($_SERVER['REQUEST_METHOD']=="POST"){
            $username = $_POST["username"];
            $sql = "SELECT * FROM utenti WHERE username=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt,"s",$username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result)>0){
                $row=mysqli_fetch_assoc($result);
                $psw = $_POST["password"];
                if(password_verify($psw,$row['psw'])){
                    $_SESSION["logged"]=true;
                    $_SESSION["ruolo"]=$row['ruolo'];
                }else{
                    $_SESSION["return_status"]="password errata";
                }
            }else{
                $_SESSION["return_status"]="username inesistente";
            }
        }
        //verifico se è loggato e a che pagina appartiene
        if(isset($_SESSION["logged"]) && $_SESSION['logged']){
            echo "sei loggato";
            if (isset($_SESSION["ruolo"])) {
                echo "hai un ruolo";
                redirectToPageOfRuolo($_SESSION["ruolo"]);
            }
        }
    ?>
</head>
<body>
    <?php
        if (!empty($_SESSION["return_status"])) {
            echo "ATTENZIONE {$_SESSION["return_status"]}";
        }
    ?>
    <form action="login.php" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username" id="username"><br>
        <label for="password">Password: </label>
        <input type="password" name="password" id="password"><br>
        <input type="submit" value="Accedi">
    </form>
</body>
</html>