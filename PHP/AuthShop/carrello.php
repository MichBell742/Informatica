<?php
    require 'db.php';
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('Location: login.php');
        exit;
    }
    if($_SERVER['REQUEST_METHOD']=="GET"){
        if (isset($_GET['delete'])) {
            $sql="DELETE FROM carrello WHERE prodotto_id={$_GET['id']}";
            mysqli_query($conn, $sql);
        }
    }else if($_SERVER['REQUEST_METHOD']=="POST"){
            $sql="UPDATE carrello SET quantita = {$_POST['quantita']} WHERE user_id = {$_SESSION['user_id']} AND prodotto_id = {$_POST['id']}";
            mysqli_query($conn, $sql);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>carrello</title>
</head>
<body>
    <table>
        <tr><th>Carrello</th></tr>
        <tr><th>nome</th><th>prezzo</th><th>quant.</th><th>subtot</th></tr>
        <?php
            $sql="SELECT p.id, p.nome, p.prezzo, c.quantita, (c.quantita * p.prezzo) as subtotal FROM prodotti as p INNER JOIN carrello as c ON p.id=c.prodotto_id INNER JOIN utenti ON utenti.id=c.user_id WHERE utenti.id={$_SESSION['user_id']}";
            $response=mysqli_query($conn, $sql);
            if(mysqli_num_rows($response)>0){
                $totale=0;
                while($row=mysqli_fetch_assoc($response)){
                    $totale+=$row['subtotal'];
                    $formQuantita="
                        <form action='carrello.php' method='POST'>
                            <input type='number' value='{$row['id']}' name='id' style='display:none'>
                            <input type='number' value='{$row['quantita']}' name='quantita'>
                            <input type='submit' value='salva'>
                        </form>";
                    echo "<tr><td>{$row['nome']}</td><td>{$row['prezzo']}</td><td>$formQuantita</td><td>{$row['subtotal']}</td><td><a href=\"carrello.php?delete=1&id={$row['id']}\">Rimuovi</a></td></tr>";
                }
            }
        ?>  
    </table>
    <?php
        echo "Totale: € $totale ";
    ?>
</body>
</html>