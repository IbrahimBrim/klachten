<?php
?>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<!-- navbar.php -->
<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo links -->
        <a href="klachtenindien.php" class="logo">
            <img src="logo_rotterdam.svg" alt="Logo" class="logo-img">
        </a>

        <!-- Inlogknop rechts -->
        <div class="login-button">
            <?php
            // Controleer of de gebruiker is ingelogd en pas de weergave dienovereenkomstig aan
            if (isset($_SESSION['user'])) {
                echo '<span>Welkom, ' . $_SESSION['user'] . '</span> | <a href="logout.php">Uitloggen</a>';
            } else {
                echo '<a href="login.php">Inloggen</a>';
            }
            ?>
        </div>
    </div>
</nav>
