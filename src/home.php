<?php
session_start();
if (!isset($_SESSION['loggato'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="result-container">
        <?php echo "<h2>Benvenuto, " . htmlspecialchars($_SESSION['username']) . "!</h2>";?>
        <?php echo "<p>Ruolo: " . htmlspecialchars($_SESSION['ruolo']) . "</p>";?>

        <?php if ($_SESSION['ruolo'] === 'amministratore'): ?>
            <form action="gestione_prodotti.php" method="get" class="button-form">
                <button type="submit" class="admin-button"><i class="fas fa-boxes"></i> Gestione prodotti</button>
            </form>

            <form action="gestione_magazzini.php" method="get" class="button-form">
                <button type="submit" class="admin-button"><i class="fas fa-warehouse"></i> Gestione magazzini</button>
            </form>
        <?php endif; ?>

        <form action="shop.php" method="get" class="button-form">
            <button type="submit" class="admin-button"><i class="fas fa-shopping-cart"></i> Shop</button>
        </form>

        <form action="index.php" method="post" class="button-form">
            <button type="submit" class="admin-button"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>
</body>
</html>
