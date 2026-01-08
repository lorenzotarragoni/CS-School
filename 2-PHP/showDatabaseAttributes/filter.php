<!-- This file retrieves and displays student names and surnames from the database based on the selected filter criteria (ascending, descending, pass, fail). -->

<?php
    $conn = new mysqli('localhost', 'root', '', 'gestione_scuola'); // Insert your database credentials

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    
    $filter = $_POST['filter1'];

    switch( $filter ) {
        case "ascending":
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome ASC";
            break;
        case "descending":
            $sql = "SELECT nome, cognome FROM studenti ORDER BY nome DESC";
            break;
        default:
            die("Filtro non valido.");
    }
    $result = $conn->query($sql);

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