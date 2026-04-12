<?php
define('URL_FILE', "articolo.csv");

$conn= mysqli_connect('localhost', 'root', '20070124', 'magazzino');

if (!$conn) {
    die('Connessione fallita: ' . mysqli_connect_error());
}

$articolo="\n{$_POST['Cod_Articolo']},{$_POST['Descrizione']},{$_POST['Quantita']},{$_POST['Prezzo']},{$_POST['Cod_Fornitore']}";

$tuttoOk=false;

if ($_POST['azione']=="aggiungi"){
    //controllo che l'articolo non sia presente
    try {
        if(!checkForArticle()){
            $file=fopen(URL_FILE, "a");
            fwrite($file, $articolo);
            $tuttoOk=true;
        }else {
            echo "article gia prsente";
        }
    } catch (Exception $th) {
        echo $th->getMessage();
    }
    
} else if ($_POST['azione']=="crea"){
    $file=fopen(URL_FILE, "w");
    $testo="Cod_Articolo,Descrizione,Quantita,Prezzo,Cod_Fornitore";
    $testo.=$articolo;
    fwrite($file, $testo);
    $tuttoOk=true;
} else if($_POST['azione']=="carica DB"){
    $result=NULL;
    try {
        $result=getArticoli($conn);
    } catch (\Throwable $th) {
        $sql="CREATE TABLE articoli ( Cod_Articolo INTEGER PRIMARY KEY, Descrizione VARCHAR(100) NOT NULL, Quantita INTEGER NOT NULL, Prezzo DECIMAL(8,2) NOT NULL, Cod_Fornitore INTEGER NOT NULL )";
        mysqli_query($conn, $sql);
    }
    if (is_null($result)) {
        $result=getArticoli($conn);
    }
    if($file=fopen(URL_FILE, "r")){
        $contRow=0;
        if (mysqli_num_rows($result)>0) {
            while ($row=mysqli_fetch_assoc($result)) {
                $articles[$row['Cod_Articolo']]=true;
                echo $row['Cod_Articolo'];
            }
            while (!feof($file)) {
                $riga=fgetcsv($file);
                if ($contRow!=0) {
                    if (!isset($articles[$riga[0]])) {
                        $sql="INSERT INTO articoli (Cod_Articolo, Descrizione, Quantita, Prezzo, Cod_Fornitore) VALUES ({$riga['0']},'{$riga['1']}',{$riga['2']},{$riga['3']},{$riga['4']})";
                        mysqli_query($conn, $sql);
                    }
                }
                $contRow++;
            }
            $tuttoOk=true;
        }else{
            $sql="INSERT INTO articoli (Cod_Articolo, Descrizione, Quantita, Prezzo, Cod_Fornitore) VALUES ";
            while (!feof($file)) {
                $riga=fgetcsv($file);
                if ($contRow!=0) {
                    if ($contRow!=1) {
                        $sql.=",";
                    }
                    $sql.="({$riga['0']},'{$riga['1']}',{$riga['2']},{$riga['3']},{$riga['4']})";
                }
                $contRow++;
            }
            mysqli_query($conn, $sql);
            $tuttoOk=true;
        }
    }else{
        echo "il file è inesistente";
    }
}

if ($tuttoOk) {
    header('Location: index.html');
    exit;
}

function getArticoli($conn){
    $sql="SELECT * FROM articoli";
    $result=mysqli_query($conn, $sql);
    return $result;
}

function checkForArticle(){
    if($file=fopen(URL_FILE, "r")){
        while (!feof($file)) {
            $riga=fgetcsv($file);
            if($riga[0]==$_POST['Cod_Articolo']){
                return true;
            }
        }
        fclose($file);
    } else{
        throw new Exception("Error in opening file", 1);
    }
}

//header("Location:/5I2/EsercizioCSV/");
?>