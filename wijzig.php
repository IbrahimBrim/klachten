<?php
require_once "navbar.php";
require_once "dbconnect.php";
global $conn;

session_start();

// Controleer of de gebruiker is ingelogd, anders stuur ze terug naar login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Log uit functie
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_destroy(); // Vernietig de sessie
    header("Location: login.php");
    exit();
}
// Controleer of het ID is opgegeven in de URL
if (!isset($_GET['id'])) {
    echo "Geen ID opgegeven.";
    exit;
}
$id = $_GET['id'];
// Haal klachtinformatie op basis van het ID
$sql = "SELECT * FROM klachten WHERE id = $id";
$result = mysqli_query($conn, $sql);

// Controleer of de klacht is gevonden
if (mysqli_num_rows($result) == 0) {
    echo "Klacht niet gevonden.";
    exit;
}

$klacht = mysqli_fetch_assoc($result);

// Verwerk formuliergegevens als het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Haal de gegevens uit het formulier
    $omschrijving = $_POST['omschrijving'];
    $statuss=$_POST['statuss'];
    // Voer de updatequery uit
    $updateSql = "UPDATE klachten SET omschrijving = '$omschrijving',statuss='$statuss' WHERE id = $id";
    $updateResult = mysqli_query($conn, $updateSql);

    if ($updateResult) {
        // Doorsturen naar beheer.php als de update is geslaagd
        header("Location: beheer.php");
        exit;
    } else {
        echo "Er is een fout opgetreden bij het bijwerken van de klacht.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container" style="display: block;">
    <h2>Wijzig klacht</h2>

    <form method="POST" action="">
        <label for="statuss">Status:</label>
        <select name="statuss">
            <option value="0" <?php echo ($klacht['statuss'] === 'ingediend') ? 'selected' : ''; ?>>Ingediend</option>
            <option value="1" <?php echo ($klacht['statuss'] === 'in_behandeling') ? 'selected' : ''; ?>>In behandeling</option>
            <option value="2" <?php echo ($klacht['statuss'] === 'klaar') ? 'selected' : ''; ?>>Klaar</option>
        </select>
        <label for="omschrijving">Omschrijving:</label>
        <textarea style="margin-bottom: 20px;" name="omschrijving" id="omschrijving" required><?php echo $klacht['omschrijving']; ?></textarea>
        <input type="submit" value="Opslaan">
    </form>

    <!-- Knop om terug te gaan naar beheer.php zonder melding -->
    <a href="beheer.php">Terug naar Beheer</a>
</div>
</body>
</html>
