<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 6px 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <TABLE BORDER=1>
        <TR>
            <TH>Cognome</TH>
            <TH>Voto Max</TH>
            <TH>Voto Min</TH>
            <TH>Media Voti</TH>
            <th>Numero Voti</th>
        </TR>
    </TABLE>
</body>

<?php
    $conn = mysqli_connect("localhost", "root", "", "gestione_scuola");

    $sql = "SELECT s.cognome, MAX(v.voto) AS max_voto, MIN(v.voto) AS min_voto, AVG(v.voto) AS media_voti, COUNT(v.voto) AS num_voti FROM studenti s LEFT JOIN valutazioni v ON s.matricola = v.id_studente GROUP BY s.cognome";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['cognome']}</td>
                    <td>{$row['max_voto']}</td>
                    <td>{$row['min_voto']}</td>
                    <td>" . round($row['media_voti'], 2) . "</td>
                    <td>{$row['num_voti']}</td>
                </tr>";
        }
    } else {
        echo "0 results";
    }
?>