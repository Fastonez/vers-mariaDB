<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = new mysqli("mariadb", "user", "userpass", "testdb");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $sql = file_get_contents("setup.sql");
    if ($mysqli->multi_query($sql)) {
        $message = '<div class="result-icon success"><i class="fas fa-check-circle"></i></div><div class="result-message">Database reset eseguito con successo</div>';
    } else {
        $message = '<div class="result-icon error"><i class="fas fa-times-circle"></i></div><div class="result-message">Errore reset DB: ' . $mysqli->error . '</div>';
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Database</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title">Resetta il database</h1>
            
            <?php if (!empty($message)) echo $message; ?>
            
            <form method="post" class="reset-form">
                <button type="submit" class="reset-btn">
                    <i class="fas fa-database"></i> Esegui Reset DB
                </button>
            </form>
            
            <div class="action-links">
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Torna al Login
                </a>
            </div>
        </div>
    </main>
</body>
</html>
