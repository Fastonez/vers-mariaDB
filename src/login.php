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

$username = $_POST['username'];  
$password = $_POST['password'];

$sql = "SELECT * FROM utenti WHERE username='$username' AND password='$password';";

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $_SESSION['loggato'] = true;
                $_SESSION['username'] = $row['username'];
                $_SESSION['ruolo'] = $row['ruolo'];
                
                // Mostra la schermata di benvenuto invece di redirect
                ?>
                <!DOCTYPE html>
                <html lang="it">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Accesso Riuscito</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <link rel="stylesheet" href="style.css">
                </head>
                <body>
                    <div class="result-container">
                        <div class="result-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Accesso riuscito!</h2>
                        <p>Benvenuto, <?php echo htmlspecialchars($row['username']); ?>!</p>
                        <p>Ruolo: <?php echo htmlspecialchars($row['ruolo']); ?></p>
                        
                        <div class="action-links">
                            <?php if ($_SESSION['ruolo'] === 'amministratore'): ?>
                                <a href="prodotti.php" class="login-btn">
                                    <i class="fas fa-boxes"></i> Gestione Prodotti
                                </a>
                            <?php endif; ?>
                            <a href="logout.php" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </body>
                </html>
                <?php
                exit();
            }
            $result->free();
        }
    } while ($conn->next_result());
}

// Se arriva qui significa che il login ha fallito
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Result</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="result-container">
        <div class="result-icon error">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2>ACCESSO NEGATO</h2>
        <p>Username o password errati</p>
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Torna al Login</a>
    </div>
</body>
</html>
<?php
$conn->close();
?>