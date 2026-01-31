<?php
    $conn = new mysqli('localhost', 'root', '', 'gestione_scuola'); // Insert your database credentials

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $search = $_POST['search1'];

    $sql = "SELECT nome, cognome FROM studenti WHERE cognome LIKE '%$search%'";

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
    exit();
?>