<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];  
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password';";

$login_success = false;

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
        <?php
        if ($conn->multi_query($sql)) {
            do {
                if ($result = $conn->store_result()) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            if (isset($row['username'])) {
                                $login_success = true;
                            }
                        }
                        echo '<div class="result-icon success"><i class="fas fa-check-circle"></i></div>';
                        echo '<div class="result-message">ACCESSO CONSENTITO</div>';
                    } else {
                        echo '<div class="result-icon error"><i class="fas fa-times-circle"></i></div>';
                        echo '<div class="result-message">ACCESSO NEGATO</div>';
                    }
                    $result->free();
                }
            } while ($conn->next_result());
        } else {
            echo '<div class="result-icon error"><i class="fas fa-exclamation-triangle"></i></div>';
            echo '<div class="result-message">Errore nella query: ' . $conn->error . '</div>';
        }
        ?>
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Torna al Login</a>
    </div>
</body>
</html>
<?php
$conn->close();
?>