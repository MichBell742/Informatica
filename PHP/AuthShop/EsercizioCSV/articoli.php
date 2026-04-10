<?php
define('URL_FILE', "articolo.csv");

$conn= mysqli_connect('localhost', 'root', '123456', 'magazzino');

if (!$conn) {
    die('Connessione fallita: ' . mysqli_connect_error());
}

$articolo="\n{$_POST['Cod_Articolo']},{$_POST['Descrizione']},{$_POST['Quantita']},{$_POST['Prezzo']},{$_POST['Cod_Fornitore']}";

if ($_POST['azione']=="aggiungi"){
    //controllo che l'articolo non sia presente
    if(!checkForArticle()){
        $file=fopen(URL_FILE, "a");
        fwrite($file, $articolo);
    }
} else if ($_POST['azione']=="crea"){
    $file=fopen(URL_FILE, "w");
    $testo="Cod_Articolo,Descrizione,Quantita,Prezzo,Cod_Fornitore";
    $testo.=$articolo;
    fwrite($file, $testo);
    echo "creazione";
} else if($_POST['azione']=="carica DB"){
    $sql="SELECT * FROM articoli";
    $result=mysqli_query($conn, $sql);
    //print_r($result);
    while ($row=mysqli_fetch_assoc($result)) {
        fopen(URL_FILE, "r");
        //controllare se l'articolo è gia presente nel DB
        echo $row['Cod_Articolo'];
    }
}

function checkForArticle(){
    $file=fopen(URL_FILE, "r");
    while (!feof($file)) {
        $riga=fgetcsv($file);
        if($riga[0]==$_POST['Cod_Articolo']){
            return true;
        }
    }
    fclose($file);
}

//header("Location:/5I2/EsercizioCSV/");
?>