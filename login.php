<?php
require_once "navbar.php";

session_start(); // Start de sessie

// Controleer of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "dbconnect.php"; // Verbinding maken met de database
    global $conn;

    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Controleer of de gebruiker bestaat in de database
    $sql = "SELECT * FROM beheer WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Controleer het wachtwoord met password_verify voor beveiligde wachtwoordopslag
        if (password_verify($password, $row['password'])) {
            // Wachtwoord komt overeen, inloggen en doorverwijzen naar beheer.php
            $_SESSION['username'] = $username;
            header("Location: beheer.php");
            exit();
        } else {
            // Ongeldig wachtwoord
            echo "<p style='text-align: center'>Ongeldig wachtwoord</p>";
        }
    } else {
        // Gebruiker niet gevonden
        echo "<p style='text-align: center'>Gebruiker niet gevonden</p>";
    }

    // Verbinding sluiten
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Inloggen</button>
    </form>
</div>
</body>
</html>
