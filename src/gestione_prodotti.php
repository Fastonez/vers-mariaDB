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
    $nome = $mysqli->real_escape_string($_POST['nome'] ?? '');
    $posizione = $mysqli->real_escape_string($_POST['posizione'] ?? '');
    $prezzo = $mysqli->real_escape_string($_POST['prezzo'] ?? '');

    // Recupera o crea magazzino
    $query_mag = "SELECT id FROM Magazzino WHERE posizione = '$posizione' LIMIT 1";
    $res_mag = $mysqli->query($query_mag);
    if ($res_mag->num_rows > 0) {
        $magazzinoId = $res_mag->fetch_assoc()['id'];
    } else {
        $mysqli->query("INSERT INTO Magazzino (posizione) VALUES ('$posizione')");
        $magazzinoId = $mysqli->insert_id;
    }

    // Aggiunta prodotto
    if (isset($_POST['aggiungi'])) {
        // Verifica se prodotto già esistente
        $check_sql = "SELECT id FROM prodotti WHERE nome = '$nome' AND prezzo = '$prezzo' LIMIT 1";
        $result = $mysqli->query($check_sql);

        if ($result->num_rows > 0) {
            $prodottoId = $result->fetch_assoc()['id'];
        } else {
            $mysqli->query("INSERT INTO prodotti (nome, prezzo) VALUES ('$nome', '$prezzo')");
            $prodottoId = $mysqli->insert_id;
        }

        // Inserisci o aggiorna in MagazzinoProdotti
        $check_mp = "SELECT quantita FROM MagazzinoProdotti WHERE prodottoId = $prodottoId AND magazzinoId = $magazzinoId";
        $res_mp = $mysqli->query($check_mp);
        if ($res_mp->num_rows > 0) {
            $mysqli->query("UPDATE MagazzinoProdotti SET quantita = quantita + 1 WHERE prodottoId = $prodottoId AND magazzinoId = $magazzinoId");
        } else {
            $mysqli->query("INSERT INTO MagazzinoProdotti (prodottoId, magazzinoId, quantita) VALUES ($prodottoId, $magazzinoId, 1)");
        }
    }

    // Incremento quantità
    elseif (isset($_POST['incrementa'])) {
        $prodottoId = (int)$_POST['id'];
        $posizione = $mysqli->real_escape_string($_POST['posizione']);
        $magResult = $mysqli->query("SELECT id FROM Magazzino WHERE posizione = '$posizione'");
        if ($magResult->num_rows > 0) {
            $magazzinoId = $magResult->fetch_assoc()['id'];
            $mysqli->query("UPDATE MagazzinoProdotti SET quantita = quantita + 1 WHERE prodottoId = $prodottoId AND magazzinoId = $magazzinoId");
        }
    }

    // Decremento quantità
    elseif (isset($_POST['decrementa'])) {
        $prodottoId = (int)$_POST['id'];
        $posizione = $mysqli->real_escape_string($_POST['posizione']);
        $magResult = $mysqli->query("SELECT id FROM Magazzino WHERE posizione = '$posizione'");
        if ($magResult->num_rows > 0) {
            $magazzinoId = $magResult->fetch_assoc()['id'];
            $mysqli->query("UPDATE MagazzinoProdotti SET quantita = GREATEST(0, quantita - 1) WHERE prodottoId = $prodottoId AND magazzinoId = $magazzinoId");
        }
    }

    // Elimina prodotto dal magazzino
    elseif (isset($_POST['elimina'])) {
        $prodottoId = (int)$_POST['id'];
        $posizione = $mysqli->real_escape_string($_POST['posizione']);
        $magResult = $mysqli->query("SELECT id FROM Magazzino WHERE posizione = '$posizione'");
        if ($magResult->num_rows > 0) {
            $magazzinoId = $magResult->fetch_assoc()['id'];
            $mysqli->query("DELETE FROM MagazzinoProdotti WHERE prodottoId = $prodottoId AND magazzinoId = $magazzinoId");

            // elimina prodotto se non più presente in alcun magazzino
            $check = $mysqli->query("SELECT 1 FROM MagazzinoProdotti WHERE prodottoId = $prodottoId LIMIT 1");
            if ($check->num_rows === 0) {
                $mysqli->query("DELETE FROM prodotti WHERE id = $prodottoId");
            }
        }
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
                        <label for="posizione">Posizione</label>
                        <input type="text" id="posizione" name="posizione" required>
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
                                <th>Posizione</th>
                                <th>Prezzo</th>
                                <th>Quantità</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $result = $mysqli->query("SELECT 
                            p.id AS id,
                            p.nome AS prodotto,
                            m.posizione AS magazzino,
                            mp.quantita AS quantita,
                            p.prezzo AS prezzo
                        FROM 
                            prodotti p
                        JOIN 
                            MagazzinoProdotti mp ON p.id = mp.prodottoId
                        JOIN 
                            Magazzino m ON mp.magazzinoId = m.id
                        ORDER BY 
                            p.nome, m.posizione;");

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['prodotto']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['magazzino']) . "</td>";
                                echo "<td>€" . number_format($row['prezzo'], 2, ',', '.') . "</td>";
                                echo "<td>" . htmlspecialchars($row['quantita']) . "</td>";
                                echo "<td class='actions'>";
                                echo "<form method='post' class='action-form'>";
                                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                                echo "<input type='hidden' name='posizione' value='" . htmlspecialchars($row['magazzino']) . "'>";
                                echo "<button type='submit' name='incrementa' class='action-btn increment' title='Aumenta quantità'><i class='fas fa-plus'></i></button>";
                                echo "<button type='submit' name='decrementa' class='action-btn decrement' title='Diminuisci quantità'><i class='fas fa-minus'></i></button>";
                                echo "<button type='submit' name='elimina' class='action-btn delete' title='Elimina prodotto'><i class='fas fa-trash'></i></button>";
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
