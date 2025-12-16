<?php
    $conn = new mysqli('localhost', 'root', '', 'gestione_scuola'); // Insert your database credentials

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    $filter = $_POST['filter1'];

    switch( $filter ) {
        case 'ascending':
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome ASC";
            break;
        case 'descending':
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome DESC";
            break;
        case 'pass':
            $sql = "SELECT nome, cognome FROM studenti WHERE valutazioni.voto >= 6";
            break;
        case 'fail':
            $sql = "SELECT nome, cognome FROM studenti WHERE valutazioni.voto < 6";
            break;
        default:
            die("Filtro non valido.");
    }
?>