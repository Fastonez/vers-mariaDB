<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

session_start();
if (!isset($_SESSION['loggato'])) {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli($host, $user, $pass, $db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $prezzo = $_POST['prezzo'];

    // Query vulnerabile a SQL Injection
    $sql = "INSERT INTO prodotti (nome, prezzo) VALUES ('$nome', '$prezzo')";
    $mysqli->query($sql);
}
?>

<h2>Gestione Prodotti</h2>

<form method="post">
    Nome: <input name="nome"><br>
    Prezzo: <input name="prezzo"><br>
    <input type="submit" value="Aggiungi Prodotto">
</form>

<h3>Lista Prodotti</h3>
<ul>
<?php
$result = $mysqli->query("SELECT * FROM prodotti");
while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['nome']) . " - €" . htmlspecialchars($row['prezzo']) . "</li>";
}
?>
</ul>
