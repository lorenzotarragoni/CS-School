<!-- This file retrieves and displays student names and surnames from the database based on the selected filter criteria (ascending, descending, pass, fail). -->

<?php
    $conn = new mysqli('localhost', 'root', '', 'gestione_scuola'); // Insert your database credentials

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    $filter = $_POST['filter1'];
    if ( !isset( $filter ) ) {
        die("Nessun filtro selezionato.");
    }

    switch( $filter ) {
        case 'ascending':
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome ASC";
            break;
        case 'descending':
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome DESC";
            break;
        case 'pass':
            $sql = "SELECT nome, cognome FROM studenti JOIN valutazioni ON valutazioni.id_studente = studenti.matricola WHERE valutazioni.voto >= 6";
            break;
        case 'fail':
            $sql = "SELECT nome, cognome FROM studenti JOIN valutazioni ON valutazioni.id_studente = studenti.matricola WHERE valutazioni.voto < 6";
            break;
        default:
            die("Filtro non valido.");
    }
    $result = $conn->query($sql);

    while( $row = $result->fetch_assoc() ) {
        echo "Nome: " . $row['nome'] . " - Cognome: " . $row['cognome'] . "<br>";
    }
?>