<!-- This file retrieves and displays student names and surnames from the database based on the selected filter criteria (ascending, descending, pass, fail). -->

<?php
    $conn = new mysqli('localhost', 'root', '', 'gestione_scuola'); // Insert your database credentials

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    $filter = $_POST['filter1'];

    switch( $filter ) {
        case "nascending":
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome ASC";
            break;
        case "ndescending":
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome DESC";
            break;
        case "sascending":
            $sql = "SELECT nome, cognome FROM studenti ORDER BY cognome ASC";
            break;
        case "sdescending":
            $sql = "SELECT nome, cognome FROM studenti ORDER BY cognome DESC";
            break;
        case "hvote":
            $sql = "SELECT s.nome, s.cognome, v.voto FROM studenti s, valutazioni v WHERE s.matricola = v.id_studente ORDER BY v.voto DESC";
            break;
        case "lvote":
            $sql = "SELECT s.nome, s.cognome, v.voto FROM studenti s, valutazioni v WHERE s.matricola = v.id_studente ORDER BY v.voto ASC";
            break;
        case "passed":
            $sql = "SELECT s.nome, s.cognome, v.voto FROM studenti s, valutazioni v WHERE s.matricola = v.id_studente AND v.voto >= 6";
            break;
        default:
            die("Filtro non valido.");
    }
    $result = $conn->query($sql);

    if($filter == "hvote" || $filter == "lvote" || $filter == "passed") {
        if($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Voto</th>
                    </tr>";
        }

        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['nome'] . "</td>
                    <td>" . $row['cognome'] . "</td>
                    <td>" . $row['voto'] . "</td>
                  </tr>";
        }
        exit();
    }

    if($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                </tr>";
    }

    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['nome'] . "</td>
                <td>" . $row['cognome'] . "</td>
              </tr>";
    }
?>