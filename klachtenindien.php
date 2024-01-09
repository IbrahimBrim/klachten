<?php
require_once "navbar.php";
require_once "dbconnect.php"; // Verbinding maken met de database
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = $_POST["naam"];
    $email = $_POST["email"];
    $omschrijving = $_POST["omschrijving"];
    $lon = $_POST["lon"];
    $lan = $_POST["lan"];

    // Controleer of lon en lan leeg zijn
    if (empty($lon) || empty($lan)) {
        echo "<div class='foutmelding'>Lon en Lan zijn verplicht.</div>";
        exit; // Stop met verwerken als lon of lan ontbreken
    }

    // Stel $foto in op null als er geen foto is geüpload
    $foto = null;

    // Controleer of er een bestand is geüpload
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
        $foto = base64_encode(file_get_contents($_FILES["foto"]["tmp_name"]));
    }

    // Haal de huidige datum en tijd op
    $datum = date("Y-m-d H:i:s");

    // Controleer of de e-mail al in de database staat
    $checkEmailQuery = "SELECT id FROM klanten WHERE email = '$email'";
    $checkEmailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        // E-mail bestaat al in de database, haal klant-id op
        $row = mysqli_fetch_assoc($checkEmailResult);
        $klantId = $row["id"];
    } else {
        // E-mail bestaat niet, voeg nieuwe klant toe en haal klant-id op
        $insertKlantQuery = "INSERT INTO klanten (naam, email) VALUES ('$naam', '$email')";
        mysqli_query($conn, $insertKlantQuery);
        $klantId = mysqli_insert_id($conn);
    }

    // Voeg de klacht toe aan de database
    $insertKlachtQuery = "INSERT INTO klachten (klanten_id, omschrijving, foto, lon, lan, datum) VALUES ('$klantId', '$omschrijving', '$foto', '$lon', '$lan', '$datum')";
    mysqli_query($conn, $insertKlachtQuery);

    echo "<div class='succesmelding'>De klacht is toegevoegd.</div>";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>

<div class="form-container" style="display: flex;">
    <div>
        <h2>Klacht Indienen</h2>
        <form method="POST" action="klachtenindien.php" enctype="multipart/form-data">
            <label for="naam">Naam:</label>
            <input type="text" name="naam" required><br>

            <label for="email">E-mail:</label>
            <input type="email" name="email" required><br>

            <label for="omschrijving">Omschrijving:</label>
            <textarea name="omschrijving" required></textarea><br>

            <label for="foto">Foto:</label>
            <input type="file" name="foto" accept="image/png, image/jpg, image/jpeg"><br>

            <!-- Voeg verborgen invoervelden toe voor lon en lan -->
            <input type="hidden" name="lon" id="lon" value="" required>
            <input type="hidden" name="lan" id="lan" value="" required>

            <input type="submit" value="Indienen">
        </form>
    </div>
    <div id="map" style="height: 600px;margin: 10px; " ></div>
</div>

<script>
    var map = L.map('map').setView([51.954380, 4.550823], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    // Voeg een klikgebeurtenis toe aan de kaart
    map.on('click', function (e) {
        // Verwijder de oude marker als die bestaat
        if (marker) {
            map.removeLayer(marker);
        }

        // Voeg een nieuwe marker toe op de geklikte locatie
        marker = L.marker(e.latlng).addTo(map);

        // Haal de coördinaten op en vul de verborgen invoervelden
        document.getElementById('lon').value = e.latlng.lng;
        document.getElementById('lan').value = e.latlng.lat;
    });

    // Zorg ervoor dat de kaart het klikken toelaat
    map.tap.enable();
</script>
</body>
</html>
