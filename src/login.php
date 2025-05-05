<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica se i dati sono stati inviati
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<h2>Accesso consentito</h2>";
    } else {
        echo "<h2>Accesso negato</h2>";
    }
} else {
    echo "<h2>Errore: Metodo di richiesta non valido</h2>";
}

$conn->close();
?>
