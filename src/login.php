<?php
session_start();

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

$sql = "SELECT * FROM utenti WHERE username='$username' AND password='$password'";

if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc(); 

                session_start();
                $_SESSION['loggato'] = true;
                $_SESSION['username'] = $row['username'];
                $_SESSION['ruolo'] = $row['ruolo'];
                header("Location: home.php");
                exit();
            } else {
                echo "<h2>ACCESSO NEGATO</h2>";
            }
            $result->free();
        }
    } while ($conn->next_result());
} else {
    echo '<div class="result-icon error"><i class="fas fa-exclamation-triangle"></i></div>';
    echo '<div class="result-message">Errore nella query: ' . $conn->error . '</div>';
}

$conn->close();
?>
