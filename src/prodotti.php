<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

session_start();


// Controllo accesso
if (!isset($_SESSION['loggato']) || $_SESSION['ruolo'] !== 'amministratore') {
    $_SESSION['accesso_negato'] = "Accesso riservato agli amministratori";
    header("Location: login.php");
    exit();
}


$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Aggiungi nuovo prodotto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiungi'])) {
    $nome = $_POST['nome'];
    $prezzo = $_POST['prezzo'];
    
    $check = $mysqli->query("SELECT id, quantita FROM prodotti WHERE nome='$nome' AND prezzo='$prezzo'");
    
    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $new_quantita = $row['quantita'] + 1;
        $mysqli->query("UPDATE prodotti SET quantita=$new_quantita WHERE id=".$row['id']);
    } else {
        $mysqli->query("INSERT INTO prodotti (nome, prezzo, quantita) VALUES ('$nome', '$prezzo', 1)");
    }
    header("Location: prodotti.php");
    exit();
}

// Gestione azioni sui prodotti
if (isset($_GET['azione'])) {
    $id = intval($_GET['id']);
    
    switch ($_GET['azione']) {
        case 'incrementa':
            $mysqli->query("UPDATE prodotti SET quantita = quantita + 1 WHERE id=$id");
            break;
            
        case 'decrementa':
            $result = $mysqli->query("SELECT quantita FROM prodotti WHERE id=$id");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['quantita'] > 1) {
                    $mysqli->query("UPDATE prodotti SET quantita = quantita - 1 WHERE id=$id");
                } else {
                    // Se la quantità è 1, elimina il prodotto
                    $mysqli->query("DELETE FROM prodotti WHERE id=$id");
                }
            }
            break;
            
        case 'elimina':
            $mysqli->query("DELETE FROM prodotti WHERE id=$id");
            break;
    }
    
    header("Location: prodotti.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Prodotti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="page-header">
        <nav>
            <span class="user-info">
                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['ruolo']); ?>)
            </span>
            <a class="reset-link" href="logout.php" aria-label="Logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>

    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title">Gestione Prodotti</h1>
            
            <form method="post" class="login-form">
                <div class="form-group">
                    <label for="nome">Nome Prodotto:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                
                <div class="form-group">
                    <label for="prezzo">Prezzo (€):</label>
                    <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" required>
                </div>
                
                <button type="submit" name="aggiungi" class="login-btn">
                    <i class="fas fa-plus-circle"></i> Aggiungi Prodotto
                </button>
            </form>

            <div class="products-list">
                <h2><i class="fas fa-list"></i> Lista Prodotti</h2>
                <div class="product-grid">
                    <?php
                    $result = $mysqli->query("SELECT * FROM prodotti");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <div class="product-card">
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($row['nome']); ?></div>
                            <div class="product-meta">
                                <span class="product-price">€<?php echo number_format($row['prezzo'], 2); ?></span>
                            </div>
                        </div>
                        <div class="product-actions">
                            <a href="prodotti.php?azione=decrementa&id=<?php echo $row['id']; ?>" class="quantity-btn minus">
                                <i class="fas fa-minus"></i>
                            </a>
                            <span class="product-quantity"><?php echo $row['quantita']; ?></span>
                            <a href="prodotti.php?azione=incrementa&id=<?php echo $row['id']; ?>" class="quantity-btn plus">
                                <i class="fas fa-plus"></i>
                            </a>
                            <a href="prodotti.php?azione=elimina&id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Sei sicuro di voler eliminare questo prodotto?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                    <?php
                        endwhile;
                    } else {
                        echo "<p>Nessun prodotto presente</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>




