<?php
$host = "mariadb";
$user = "user";
$pass = "userpass";
$db = "testdb";
$conn = new mysqli($host, $user, $pass, $db);

session_start();
if (!isset($_SESSION['loggato'])) {
    header("Location: index.php");
    exit();
}


$success = '';
$error = '';

// Aggiunta magazzino
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['posizione'])) {
    $posizione = trim($_POST['posizione']);
    if (!empty($posizione)) {
        $stmt = $conn->prepare("INSERT INTO Magazzino (posizione) VALUES (?)");
        $stmt->bind_param("s", $posizione);
        if ($stmt->execute()) {
            $success = "Magazzino aggiunto correttamente.";
        } else {
            $error = "Errore durante l'aggiunta del magazzino.";
        }
        $stmt->close();
    } else {
        $error = "La posizione non può essere vuota.";
    }
}

// Eliminazione magazzino
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    // Verifica che non ci siano prodotti associati
    $stmt1 = $conn->prepare("DELETE FROM MagazzinoProdotti WHERE magazzinoId = ?");
    $stmt1->bind_param("i", $delete_id);
    $stmt1->execute();
    $stmt1->close();

    // 2. Elimina il magazzino
    $stmt2 = $conn->prepare("DELETE FROM Magazzino WHERE id = ?");
    $stmt2->bind_param("i", $delete_id);
    $stmt2->execute();
    $stmt2->close();

}

// Recupera magazzini
$result = $conn->query("SELECT id, posizione FROM Magazzino ORDER BY id ASC");
$magazzini = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <div class="admin-header">
        <div class="header-left">
            <a href="home.php" class="logout-btn"><i class="fas fa-arrow-left"></i>Indietro</a>
        </div>
        <meta charset="UTF-8">
        <title>Gestione Magazzini</title>
        <link rel="stylesheet" href="style.css">
        <div>
            <a href="index.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </div>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <h2><i class="fas fa-boxes"></i>Gestione Magazzini</h2>
    </div>

    <?php if ($success): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="add-product-form">
        <h3>Aggiungi Magazzino</h3>
        <form method="POST" class="form-group">
            <label for="posizione">Posizione</label>
            <input type="text" name="posizione" id="posizione" required>
            <button type="submit" class="add-btn">Aggiungi</button>
        </form>
    </div>

    <div class="products-list">
        <h3>Elenco Magazzini</h3>
        <?php if (count($magazzini) === 0): ?>
            <p class="no-products">Nessun magazzino presente.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th>Posizione</th>
                        <th>Azioni</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($magazzini as $mag): ?>
                        <tr>
                            <td><?= htmlspecialchars($mag['posizione']) ?></td>
                            <td class="actions">
                                <form method="POST" class="action-form" onsubmit="return confirm('Eliminare il magazzino selezionato?');">
                                    <input type="hidden" name="delete_id" value="<?= $mag['id'] ?>">
                                    <button type="submit" class="action-btn delete"><i>&#10006;</i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>
</div>
</body>
</html>
