<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
</head>
<body>
    <?php
        require "db.php";
        $form='<form action="registrazione.php" method="post">
                <label for="username">Username</label><br>
                <input type="text" name="username" required><br>
                <label for="email">Email</label><br>
                <input type="email" name="email" required><br>
                <label for="password">Password</label><br>
                <input name="password" type="password" required><br>
                <label for="confPassword">Conferma Password</label><br>
                <input name="confPassword" type="password" required><br>
                <input type="submit" value="Registrati">
            </form>';
        if ($_SERVER['REQUEST_METHOD']=="POST") {
            $tuttoOK=true;
            $post = purifyString($conn, $_POST);
            if (strlen($_POST["password"])<6) {
                echo "<h3> LA PASSWORD HA UNA LUNGHEZZA TROPPO PICCOLA </h3>";
                echo $form;
                $tuttoOK=false;
            }
            if ($_POST["password"]!=$_POST["confPassword"] && $tuttoOK) {
                echo "<h3> LE PASSWORD NON COINCIDONO </h3>";
                echo $form;
                $tuttoOK=false;
            }
            if (strlen($_POST["password"])<6 && $tuttoOK) {
                echo "<h3> LA PASSWORD HA UNA LUNGHEZZA TROPPO PICCOLA </h3>";
                echo $form;
                $tuttoOK=false;
            }
            $sql="SELECT username FROM utenti WHERE username='{$post['username']}'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result)>0){
                echo "ATTENZIONE UTENTE GIA PRESENTE CAMBIA USERNAME";
            }else{
                if($tuttoOK){
                    $psw_hash=password_hash($post['password'], PASSWORD_DEFAULT);
                    $sql="INSERT INTO utenti (username,email,password_hash) VALUES ('{$post['username']}','{$post['email']}','{$psw_hash}')";
                    $result = mysqli_query($conn, $sql);
                    echo "REGISTRAZIONE ESEGUITA CON SUCCESSO";
                }
            }
        }else{
            echo $form;
        }
    ?>
    <!--<form action="registrazione.php" method="post">
        <label for="username">Username</label><br>
        <input type="text" name="username" required><br>
        <label for="email">Email</label><br>
        <input type="email" name="email" required><br>
        <label for="password">Password</label><br>
        <input name="password" type="password" required><br>
        <label for="confPassword">Conferma Password</label><br>
        <input name="confPassword" type="password" required><br>
        <input type="submit" value="Registrati">
    </form>-->
</body>
</html>
