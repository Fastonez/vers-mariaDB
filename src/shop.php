<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Ricerca Prodotti</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="main-content">
    <div class="form-container">
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i>Torna al Login</a>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="prodotto">Cerca prodotto:</label>
                <input type="text" id="prodotto" name="prodotto" required placeholder="Inserisci nome prodotto">
            </div>
            <button type="submit" class="login-btn">Cerca</button>
        </form>

        <div class="result-container">
            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["prodotto"])) {
                $prodotto = $_POST["prodotto"]; // Input non sanificato - VULNERABILE
                
                // Query vulnerabile a SQL injection
                $sql = "SELECT p.nome AS prodotto, m.posizione AS magazzino, mp.quantita 
                        FROM prodotti p
                        JOIN MagazzinoProdotti mp ON p.id = mp.prodottoId
                        JOIN Magazzino m ON mp.magazzinoId = m.id
                        WHERE p.nome LIKE '%$prodotto%'";
                

                $result = $conn->query($sql);
                
                if ($result && $result->num_rows > 0) {
                    echo "<h3>Disponibilità del prodotto</h3>";
                    echo "<table class='result-table'>";
                    echo "<tr><th>Prodotto</th><th>Magazzino</th><th>Quantità</th></tr>";
                    
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['prodotto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['magazzino']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantita']) . "</td>";
                        echo "</tr>";
                    }
                    
                    echo "</table>";
                } else {
                    echo "<div class='error-message'>Nessun risultato trovato per: " . htmlspecialchars($prodotto) . "</div>";
                }
                
                if ($result) {
                    $result->free();
                }

                
            }
            ?>
        </div>
    </div>
</div>

<?php $conn->close(); ?>
</body>
</html>