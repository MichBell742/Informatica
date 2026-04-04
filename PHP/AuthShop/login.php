<?php
    require 'db.php';
    session_start();
    if(isset($_SESSION['user_id'])){
        header('Location: home.php');
    }
    if($_SERVER['REQUEST_METHOD']=="POST"){
        $post=purifyString($conn, $_POST);
        $sql="SELECT * FROM utenti WHERE username='{$post['username']}'";
        $result=mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)>0) {
            $row=mysqli_fetch_assoc($result);
            if(password_verify($post['password'], $row["password_hash"])){
                $_SESSION["user_id"]=$row["id"];
                $_SESSION["username"]=$row["username"];
                setcookie('ricorda_username', $row['username'], time() + 7 * 24 * 3600, '/');
                header('Location: home.php');
            }
        } else {
            echo "ATTENZIONE UTENTE O PASSWORD SBAGLIATI";
        }
    }
    $user_precompilato = $_COOKIE['ricorda_username'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accedi</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Username</label><br>
        <input type="text" name="username" required value="<?php echo "$user_precompilato"; ?>" ><br>
        <label for="password">Password</label><br>
        <input name="password" type="password" required><br>
        <input type="submit" value="Accedi">
    </form>
</body>
</html>