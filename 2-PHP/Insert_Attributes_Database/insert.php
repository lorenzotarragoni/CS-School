<?php
$conn = new mysqli('localhost', 'root', '', 'gestione_scuola'); // Insert your database credentials

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$name = $_POST['name'];
$surname = $_POST['surname'];
$dob = $_POST['dob'];
$nationality = $_POST['nationality'];

$sql = "INSERT INTO studenti (nome, cognome, data_nascita, nazionalita) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Errore prepare(): " . $conn->error);
}
 
$stmt->bind_param("ssss", $name, $surname, $dob, $nationality);

if ($stmt->execute()) {
    echo "Dati inseriti con successo.";
} else {
    echo "Errore execute(): " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
