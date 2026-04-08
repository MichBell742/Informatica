<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <?php
        require 'db.php';
        session_start();
        if(!isset($_SESSION['user_id'])){
            header('Location: login.php');
        }
        $numero=$_SESSION['user_id'];
        $result=mysqli_query($conn, "SELECT * FROM preferenze WHERE user_id='$numero'");
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
        }else{
            $row["tema"]='chiaro';
            $row["lingua"]='it';
        }
        if($row["tema"]=='scuro'){
            $style="background:#222; color:#eee";
        }else{
            $style="";
        }
        
    ?>
</head>
<body style="<?php echo $style; ?>">
    <h3>Salve <?php echo $_SESSION['username']; ?> <a href="logout.php">LOGOUT</a> </h3>
    <?php
        echo "<h4>tema: {$row['tema']}</h4> <br>";
        echo "<h4>lingua: {$row['lingua']}</h4><br>";
    ?>
    <h2>Gestisci <a href="preferenze.php">preferenze</a></h2>
    <h2>Catalogo <a href="catalogo.php">catalogo</a></h2>
    <h2>carrello <a href="carrello.php">carrello</a></h2>
</body>
</html>