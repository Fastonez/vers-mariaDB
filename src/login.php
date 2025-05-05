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


// Esegue multi_query
if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            while ($row = $result->fetch_assoc()) {
                echo "<pre>" . print_r($row, true) . "</pre>"; // Debug
            }
            $result->free();
        }
    } while ($conn->next_result());
} else {
    echo "Errore: " . $conn->error;
}

$conn->close();
?>
