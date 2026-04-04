<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>preferenze</title>
    <?php
        require 'db.php';
        session_start();
        if(!isset($_SESSION['user_id'])){
            header('Location: login.php');
        }
        print_r($_SESSION);
        $numero=$_SESSION['user_id'];
        $result=mysqli_query($conn, "SELECT * FROM preferenze WHERE user_id='$numero'");
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
        }else{
            $row["tema"]='chiaro';
            $row["lingua"]='it';
        }
        
        if ($_SERVER['REQUEST_METHOD']=="POST") {
            $user_id=$_SESSION['user_id'];
            $tema=$_POST['tema'];
            $lingua=$_POST['lingua'];
            $sql = "INSERT INTO preferenze (user_id, tema, lingua)
                    VALUES ($user_id, '$tema', '$lingua')
                    ON DUPLICATE KEY UPDATE
                        tema   = VALUES(tema),
                        lingua = VALUES(lingua)";
            mysqli_query($conn, $sql);
            header('Location: home.php');
        }

    ?>
</head>
<body>
    <form action="preferenze.php" method="POST">
    <label for="tema">TEMA:</label><br>
    <select name="tema" id="tema">
        <option value="scuro" <?php echo ($row['tema'] == 'scuro') ? 'selected' : ''; ?>>scuro</option>
        <option value="chiaro" <?php echo ($row['tema'] == 'chiaro') ? 'selected' : ''; ?>>chiaro</option>
    </select><br>

    <label for="lingua">LINGUA:</label><br>
    <select name="lingua" id="lingua">
        <option value="it" <?php echo ($row['lingua'] == 'it') ? 'selected' : ''; ?>>it</option>
        <option value="en" <?php echo ($row['lingua'] == 'en') ? 'selected' : ''; ?>>en</option>
    </select>
    <input type="submit" value="cambia">
</form>

    
</body>
</html>