<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = new mysqli("mariadb", "user", "userpass", "testdb");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $sql = file_get_contents("setup.sql");
    if ($mysqli->multi_query($sql)) {
        $message = "<p class='success-message'>✅ Database reset eseguito con successo.</p>";
    } else {
        $message = "<p class='error-message'>❌ Errore reset DB: " . $mysqli->error . "</p>";
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title">Resetta il database</h1>
            
            <?php if (!empty($message)) echo $message; ?>
            
            <form method="post" class="reset-form">
                <button type="submit" class="reset-btn">🔄 Esegui Reset DB</button>
            </form>
            
            <div class="action-links">
                <a href="index.php" class="back-link">← Torna al Login</a>
            </div>
        </div>
    </main>
</body>
</html>
