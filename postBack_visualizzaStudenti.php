<?php
// 1. Connessione al database
$conn = new mysqli('localhost', 'root', '', 'gestione_scuola');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Inizializziamo le variabili per i risultati
$result = null;
$show_vote = false;

// 2. Gestione Logica Postback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // CASO A: Ricerca per cognome
    if (isset($_POST['search1']) && !empty($_POST['search1'])) {
        $search = "%" . $_POST['search1'] . "%";
        $stmt = $conn->prepare("SELECT nome, cognome FROM studenti WHERE cognome LIKE ?");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();
    } 
    
    // CASO B: Filtri avanzati
    elseif (isset($_POST['filter1']) && !empty($_POST['filter1'])) {
        $filter = $_POST['filter1'];
        $sql = "";

        switch($filter) {
            case "nascending":  $sql = "SELECT nome, cognome FROM studenti ORDER BY nome ASC"; break;
            case "ndescending": $sql = "SELECT nome, cognome FROM studenti ORDER BY nome DESC"; break;
            case "sascending":  $sql = "SELECT nome, cognome FROM studenti ORDER BY cognome ASC"; break;
            case "sdescending": $sql = "SELECT nome, cognome FROM studenti ORDER BY cognome DESC"; break;
            case "hvote":
                $sql = "SELECT s.nome, s.cognome, v.voto FROM studenti s JOIN valutazioni v ON s.matricola = v.id_studente ORDER BY v.voto DESC";
                $show_vote = true;
                break;
            case "lvote":
                $sql = "SELECT s.nome, s.cognome, v.voto FROM studenti s JOIN valutazioni v ON s.matricola = v.id_studente ORDER BY v.voto ASC";
                $show_vote = true;
                break;
            case "passed":
                $sql = "SELECT s.nome, s.cognome, v.voto FROM studenti s JOIN valutazioni v ON s.matricola = v.id_studente WHERE v.voto >= 6";
                $show_vote = true;
                break;
        }

        if ($sql != "") {
            $result = $conn->query($sql);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Studenti - Postback</title>
    <link rel="stylesheet" href="show.css">
    <style>
        .results-container { margin-top: 20px; width: 100%; display: flex; justify-content: center; }
        table { border-collapse: collapse; width: 80%; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>

    <div class="forms-wrapper">
        <form method="POST" action="" class="card">
            <h2>Cerca Studente</h2>
            <label for="search1">Cerca per cognome</label>
            <input type="text" id="search1" name="search1" placeholder="Es. Rossi">
            <button type="submit">Cerca</button>
        </form>

        <form method="POST" action="" class="card">
            <h2>Filtra Risultati</h2>
            <label for="filter1">Opzioni di ordinamento</label>
            <select name="filter1" id="filter1">
                <option value="">-- Seleziona --</option>
                <option value="nascending">Nome A–Z</option>
                <option value="ndescending">Nome Z–A</option>
                <option value="sascending">Cognome A–Z</option>
                <option value="sdescending">Cognome Z–A</option>
                <option value="hvote">Voto più alto</option>
                <option value="lvote">Voto più basso</option>
                <option value="passed">Sufficienti</option>
            </select>
            <button type="submit">Applica Filtro</button>
        </form>
    </div>

    <div class="results-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <?php if ($show_vote): ?> <th>Voto</th> <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['cognome']); ?></td>
                            <?php if ($show_vote): ?> 
                                <td><?php echo $row['voto']; ?></td> 
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>Nessun risultato trovato.</p>
        <?php endif; ?>
    </div>

</body>
</html>