<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="page-header">
        <nav>
            <a class="reset-link" href="reset.php" aria-label="Resetta il database">
                <i class="fas fa-database"></i> Reset Database
            </a>
        </nav>
    </header>

    <main class="main-content">
        <div class="form-container">
            <h1 class="form-title">Accesso</h1>
            
            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required aria-required="true" autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required aria-required="true" autocomplete="current-password">
                </div>
                
                <input type="submit" value="Login" class="login-btn">
            </form>
        </div>
    </main>
</body>
</html>

