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
// Haal klachten op uit de database (vervang dit met echte databasequery's)
$sql = "SELECT id, lon, lan, omschrijving FROM klachten";
$result = mysqli_query($conn, $sql);

// Maak een lege array aan om klachten op te slaan
$klachten = [];

// Controleer of er resultaten zijn
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $klachten[] = $row;
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
<div id="map" ></div>
<script>
    // Initialiseer de kaart
    var mymap = L.map('map').setView([51.954380, 4.550823], 10);

    // Voeg de OpenStreetMap-laag toe aan de kaart
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    // Gebruik de PHP-variabele om klachten uit de database te markeren op de kaart
    var klachten = <?php echo json_encode($klachten); ?>;

    // Loop door de klachten en voeg markeringen toe aan de kaart
    klachten.forEach(function (klacht) {
        L.marker([klacht.lan, klacht.lon]).addTo(mymap)
            .bindPopup('<strong>ID:</strong> ' + klacht.id  + '<br><strong>Omschrijving:</strong> ' + klacht.omschrijving + '<br><button onclick="redirectToWijzigPage(' + klacht.id + ')">Wijzig</button>');
    });

    // Functie om door te verwijzen naar wijzig.php
    function redirectToWijzigPage(id) {
        window.location.href = 'wijzig.php?id=' + id;
    }
</script>
</body>
</html>
