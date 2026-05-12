<?php

$conn= mysqli_connect('localhost', 'root', '20070124', 'magazzino');

if (!$conn) {
    die('Connessione fallita: ' . mysqli_connect_error());
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $codArticolo = isset($_POST["cod_articolo"]) ? $_POST["cod_articolo"] : "";
    $descrizione = isset($_POST["descrizione"]) ? $_POST["descrizione"] : "";
    $quantita = isset($_POST["quantita"]) ? $_POST["quantita"] : "";
    $prezzo = isset($_POST["prezzo"]) ? $_POST["prezzo"] : "";
    $codFornitore = isset($_POST["cod_fornitore"]) ? $_POST["cod_fornitore"] : "";

    $riga = "$codArticolo,$descrizione,$quantita,$prezzo,$codFornitore\n";

    if($_POST['azione']=="crea"){
        $file = fopen('articolo.csv', 'w');
        fwrite($file, $riga);
        fclose($file);
        header("Location: index.html");
    }
    if($_POST['azione']=="aggiungi"){
        $file = fopen('articolo.csv', 'a');
        fwrite($file, $riga);
        fclose($file);
        header("Location: index.html");
    }
    if($_POST['azione']=="carica DB"){
        $file = fopen('articolo.csv', 'r');
        
        while(($campi = fgetcsv($file, 1000, ",")) !== false){
            $codArt = mysqli_real_escape_string($conn, $campi[0]);
            $desc = mysqli_real_escape_string($conn, $campi[1]);
            $q = mysqli_real_escape_string($conn, $campi[2]);
            $p = mysqli_real_escape_string($conn, $campi[3]);
            $codForn = mysqli_real_escape_string($conn, $campi[4]);

            $sqlControllo = "SELECT COUNT(*) as totale FROM articoli WHERE cod_articolo = '$codArt'";
            $risultato = mysqli_query($conn, $sqlControllo);
            $riga=mysqli_fetch_assoc($risultato);
            if($riga["totale"] == 0){
                $sqlInserimento = "INSERT INTO articoli (cod_articolo, descrizione, quantita, prezzo, cod_fornitore)
                    VALUES ('$codArt', '$desc', '$q', '$p', '$codForn' )";
                $risultatoInsert = mysqli_query($conn, $sqlInserimento);
            }
        }
        fclose($file);
        header("Location: index.html");
    }
}

?>