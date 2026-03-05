<?php
// 1. Connessione al database
$conn = new mysqli('localhost', 'root', '', 'gestione_scuola');

if ($conn->connect_error) {
    die('Connessione fallita: ' . $conn->connect_error);
}

$result = null;
$show_vote = false;

// 2. Logica Postback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search1']) && !empty($_POST['search1'])) {
        $search = "%" . $_POST['search1'] . "%";
        $stmt = $conn->prepare("SELECT nome, cognome FROM studenti WHERE cognome LIKE ?");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();
    } 
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
        if ($sql != "") { $result = $conn->query($sql); }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Studenti</title>
    <style>
        /* RESET E BASE */
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        h2 { color: #2c3e50; margin-top: 0; font-size: 1.2rem; }

        /* LAYOUT DEI FORM */
        .forms-wrapper {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            flex: 1;
            min-width: 300px;
            max-width: 450px;
        }

        /* ELEMENTI INPUT */
        label { display: block; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }

        button:hover { background-color: #2980b9; }

        /* TABELLA RISULTATI */
        .results-container {
            max-width: 920px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f8f9fa; color: #7f8c8d; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        tr:hover { background-color: #f1f1f1; }

        .no-results { text-align: center; color: #95a5a6; padding: 20px; }
        
        /* Badge per il voto */
        .voto-badge {
            font-weight: bold;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            background-color: #27ae60; /* Verde per sufficienza */
        }
        .voto-insufficiente { background-color: #e74c3c; }
    </style>
</head>
<body>

    <div class="forms-wrapper">
        <form method="POST" action="" class="card">
            <h2>Cerca Studente</h2>
            <label for="search1">Cognome dello studente</label>
            <input type="text" id="search1" name="search1" placeholder="Inserisci il cognome...">
            <button type="submit">Cerca</button>
        </form>

        <form method="POST" action="" class="card">
            <h2>Filtra Risultati</h2>
            <label for="filter1">Opzioni di ordinamento</label>
            <select name="filter1" id="filter1">
                <option value="">-- Seleziona filtro --</option>
                <option value="nascending">Nome A–Z</option>
                <option value="ndescending">Nome Z–A</option>
                <option value="sascending">Cognome A–Z</option>
                <option value="sdescending">Cognome Z–A</option>
                <option value="hvote">Voto più alto</option>
                <option value="lvote">Voto più basso</option>
                <option value="passed">Sufficienti (>= 6)</option>
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
                                <td>
                                    <span class="voto-badge <?php echo ($row['voto'] < 6) ? 'voto-insufficiente' : ''; ?>">
                                        <?php echo $row['voto']; ?>
                                    </span>
                                </td> 
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="no-results">Nessun record trovato per la selezione corrente.</div>
        <?php else: ?>
            <div class="no-results">Usa i moduli sopra per visualizzare i dati degli studenti.</div>
        <?php endif; ?>
    </div>

</body>
</html>