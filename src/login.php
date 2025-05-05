<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// L'escaping è DISABILITATO per test (NON fare in produzione!)
$username = $_POST['username'];  
$password = $_POST['password'];

// Query multipla (vulnerabile a SQLi!)
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password';";

// Esecuzione multi_query
$login_success = false;


if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Se troviamo almeno un utente valido nella PRIMA query
                    if (isset($row['username']) ){
                        $login_success = true;
                        
                    }
                    
                }
                echo "ACCESSO CONSENTITO";
            }else{
                echo "ACCESSO NEGATO";
            }
            $result->free();
        }
    } while ($conn->next_result());
} else {
    echo "<p class='error-message'>❌ Errore nella query: " . $conn->error . "</p>";
}

$conn->close();
?>
