<?php
    //gestione sessione
    session_start();

    const DIR_STUD = '/Login/studente.php';
    const DIR_DOC = '/Login/docente.php';
    const DIR_ADM = '/Login/admin.php';
    const DIR_LOG = '/Login/login.php';
    const DIR_CONST = '/Login/const.php';
    
    //metodo universale che gestisce i permessi
    /**
     * verifica che il proprietario della sessione sia loggato e che abbia il ruolo richiesto
     * se manca la prima viene reindirizzato al log-in; se manca la seconda viene rimandato alla sua pagina
     */
    function checkPermition($ruoloRichiesto){
        if (!(isset($_SESSION['logged']) && $_SESSION['logged'])) {
            header('Location: '. DIR_LOG);
            exit;
        }
        if (!(isset($_SESSION['ruolo']) && $_SESSION['ruolo']==$ruoloRichiesto)) {
            redirectToPageOfRuolo($_SESSION['ruolo']);
        }
    }

    function redirectToPageOfRuolo($ruolo){
        switch ($ruolo) {
            case 's':
                header("Location:". DIR_STUD);
                exit;
                break;
            case 'd':
                header("Location:". DIR_DOC);
                exit;
                break;
            case 'a':
                header("Location:". DIR_ADM);
                exit;
                break;
            default:
                $_SESSION["return_status"]="sei loggato con un ruolo imprevisto.";
                header("Location: login.php");
                exit;
                break;
        }
    }

    function logout(){
        session_unset();
        session_destroy();
    }
    if(isset($_GET['logout'])) {
        logout();
        header('Location: '.DIR_LOG);
    }

?>