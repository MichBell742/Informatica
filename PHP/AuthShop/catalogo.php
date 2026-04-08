<?php
    require 'db.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo</title>
    <?php
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD']=="GET") {
            if(isset($_GET['aggiungi'])){
                $sql="INSERT INTO carrello (user_id, prodotto_id) VALUES ({$_SESSION['user_id']}, {$_GET['id']}) ON DUPLICATE KEY UPDATE quantita = quantita + 1";
                $result=mysqli_query($conn, $sql);
            }
        }
    ?>
</head>
<body>
    <table>
        <tr><th>Catalogo</th></tr>
        <?php
            $sql="SELECT id, nome, prezzo FROM prodotti";
            $result=mysqli_query($conn, $sql);
            if(mysqli_num_rows($result)>0){
                while ($row=mysqli_fetch_assoc($result)) {
                    $path="catalogo.php?aggiungi=1&id={$row['id']}";
                    echo "<tr> <td> {$row['nome']} </td> <td> {$row['prezzo']} </td> <td> <input type='button' value='aggiungi' onclick=\"window.location.href ='$path'\"> </td> </tr>";
                }
            }else{
                echo "<tr><th>NESSUN PRODOTTO</th></tr>";
            }
            
        ?>
    </table>
</body>
</html>