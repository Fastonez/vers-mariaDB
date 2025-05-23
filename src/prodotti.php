<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";

session_start();
if (!isset($_SESSION['loggato'])) {
    header("Location: index.php");
    exit();
}


$mysqli = new mysqli($host, $user, $pass, $db);

// Gestione delle azioni
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aggiunta prodotto
    if (isset($_POST['aggiungi'])) {
        $nome = $mysqli->real_escape_string($_POST['nome']);
        $prezzo = $mysqli->real_escape_string($_POST['prezzo']);
        
        // Controlla se il prodotto esiste già
        $check_sql = "SELECT id, quantita FROM prodotti WHERE nome = '$nome' AND prezzo = '$prezzo' LIMIT 1";
        $result = $mysqli->query($check_sql);
        
        if ($result->num_rows > 0) {
            // Prodotto esistente - incrementa quantità
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantita'] + 1;
            $update_sql = "UPDATE prodotti SET quantita = $new_quantity WHERE id = " . $row['id'];
            $mysqli->query($update_sql);
        } else {
            // Prodotto nuovo - inserisci
            $insert_sql = "INSERT INTO prodotti (nome, prezzo, quantita) VALUES ('$nome', '$prezzo', 1)";
            $mysqli->query($insert_sql);
        }
    }

    // Incremento quantità
    elseif (isset($_POST['incrementa'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE prodotti SET quantita = quantita + 1 WHERE id = $id";
        $mysqli->query($sql);
    }
    // Decremento quantità
    elseif (isset($_POST['decrementa'])) {
        $id = (int)$_POST['id'];
        $sql = "UPDATE prodotti SET quantita = GREATEST(0, quantita - 1) WHERE id = $id";
        $mysqli->query($sql);
    }
    // Elimina prodotto
    elseif (isset($_POST['elimina'])) {
        $id = (int)$_POST['id'];
        $sql = "DELETE FROM prodotti WHERE id = $id";
        $mysqli->query($sql);
    }
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
    <div class="admin-container">
        <div class="admin-header">
            <h2><i class="fas fa-boxes"></i> Gestione Prodotti</h2>
            <a href="index.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <div class="admin-content">
            <div class="add-product-form">
                <h3><i class="fas fa-plus-circle"></i> Aggiungi Nuovo Prodotto</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="nome">Nome Prodotto</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="prezzo">Prezzo (€)</label>
                        <input type="number" id="prezzo" name="prezzo" step="0.01" min="0" required>
                    </div>
                    <button type="submit" name="aggiungi" class="add-btn">
                        <i class="fas fa-save"></i> Aggiungi Prodotto
                    </button>
                </form>
            </div>

            <div class="products-list">
                <h3><i class="fas fa-list"></i> Lista Prodotti</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Prezzo</th>
                                <th>Quantità</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $mysqli->query("SELECT * FROM prodotti ORDER BY nome");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                                    echo "<td>€" . number_format($row['prezzo'], 2, ',', '.') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['quantita'] ?? 0) . "</td>";
                                    echo "<td class='actions'>";
                                    echo "<form method='post' class='action-form'>";
                                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                                    echo "<button type='submit' name='incrementa' class='action-btn increment' title='Aumenta quantità'><i class='fas fa-plus'></i></button>";
                                    echo "<button type='submit' name='elimina' class='action-btn delete' title='Elimina prodotto'><i class='fas fa-trash'></i></button>";
                                    echo "<button type='submit' name='decrementa' class='action-btn decrement' title='Diminuisci quantità'><i class='fas fa-minus'></i></button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='no-products'>Nessun prodotto trovato</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
