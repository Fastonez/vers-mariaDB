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
                $prodotto = $_POST["prodotto"]; // Intenzionalmente vulnerabile
                $sql_prod = "SELECT nome, quantita FROM prodotti WHERE nome LIKE '$prodotto';";

                if ($conn->multi_query($sql_prod)) {
                    do {
                        if ($result = $conn->store_result()) {
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $_SESSION['loggato'] = true;

                                echo "<div class='success-message'>";
                                echo "<strong>Prodotto:</strong> " . $row['nome'] . "<br>";
                                echo "<strong>Disponibili:</strong> " . $row['quantita'];
                                echo "</div>";
                            } else {
                                echo "<div class='error-message'>Prodotto non trovato o esaurito.</div>";
                            }
                            $result->free();
                        }
                    } while ($conn->next_result());
                } else {
                    echo "<div class='error-message'><i class='fas fa-exclamation-circle'></i> Errore: " . $conn->error . "</div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php $conn->close(); ?>
</body>
</html>
