<?php
require_once "navbar.php";
require_once "dbconnect.php"; // Verbinding maken met de database
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
// Initialisatie van zoekvariabele
$search = "";

// Verwerking van het zoekformulier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST["search"];
}

// Opstellen van de SQL-query met behulp van een WHERE-clausule als er wordt gezocht
$sql = "SELECT id, naam, email FROM klanten";
if (!empty($search)) {
    $sql .= " WHERE id LIKE '%$search%' OR naam LIKE '%$search%' OR email LIKE '%$search%'";
}

// Uitvoeren van de query
$result = mysqli_query($conn, $sql);

// Maak een lege array aan om klanten op te slaan
$klanten = [];

// Controleer of er resultaten zijn
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $klanten[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-containerklant">
    <h2>Klanten</h2>

    <!-- Zoekbalk -->
    <form method="POST" action="" class="zoekbaar">
        <label for="search">Zoeken:</label>
        <input type="text" name="search" value="<?php echo $search; ?>">
        <input type="submit" value="Zoeken">
    </form>
    <a href="beheer.php"><button>Terug naar Klachttabel</button></a>
    <!-- Klanttabel -->
    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>E-mail</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($klanten as $klant): ?>
            <tr>
                <td><?php echo $klant['id']; ?></td>
                <td><?php echo $klant['naam']; ?></td>
                <td><?php echo $klant['email']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
