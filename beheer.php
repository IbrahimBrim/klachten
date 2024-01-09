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
// Aantal klachten per pagina
$klachtenPerPage = 5;

// Bereken de offset op basis van de huidige pagina
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $klachtenPerPage;

// Zoekopdracht
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT id, klanten_id, lon, lan, omschrijving, statuss, foto, datum FROM klachten";
if (!empty($searchTerm)) {
    $sql .= " WHERE id LIKE '%$searchTerm%' OR klanten_id LIKE '%$searchTerm%' OR omschrijving LIKE '%$searchTerm%'";
}
$sql .= " LIMIT $klachtenPerPage OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Maak een lege array aan om klachten op te slaan
$klachten = [];

// Controleer of er resultaten zijn
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $klachten[] = $row;
    }
}

// Totaal aantal klachten voor paginering
$totalKlachtenSql = "SELECT COUNT(*) as total FROM klachten";
if (!empty($searchTerm)) {
    // Voeg zoekvoorwaarden toe aan de query
    $totalKlachtenSql .= " WHERE id LIKE '%$searchTerm%' OR klanten_id LIKE '%$searchTerm%' OR omschrijving LIKE '%$searchTerm%'";
}
$totalResult = mysqli_query($conn, $totalKlachtenSql);
$totalKlachten = mysqli_fetch_assoc($totalResult)['total'];

// Functie om statuss in tekst te converteren
function getStatussText($statuss)
{
    switch ($statuss) {
        case 0:
            return 'Ingediend';
        case 1:
            return 'In behandeling';
        case 2:
            return 'Klaar';
        default:
            return 'Onbekende status';
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
<a href="beheer.php?logout=true">Uitloggen</a>
<div class="contener">
    <div id="klachtentabel">
        <h2>Klachten</h2>
        <form method="GET" action="" class="zoekbaar">
            <label for="search">Zoek:</label>
            <input type="text" name="search" id="search" value="<?php echo $searchTerm; ?>">
            <input type="submit" value="Zoeken">
        </form>
        <a href="klachtenopmap.php"><button>map</button></a>
        <a href="klanttabel.php"><button>Klanttabel</button></a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Klant-ID</th>
                <th>Datum</th>
                <th>Omschrijving</th>
                <th>Statuss</th>
                <th>Foto</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($klachten as $klacht): ?>
                <tr>
                    <td><?php echo $klacht['id']; ?></td>
                    <td><?php echo $klacht['klanten_id']; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($klacht['datum'])); ?></td>
                    <td><?php echo $klacht['omschrijving']; ?></td>
                    <td><?php echo getStatussText($klacht['statuss']); ?></td>

                    <td>
                        <?php
                        // Controleren of de foto beschikbaar is
                        if (!empty($klacht['foto'])) {
                            $name = "userUpload_" . $klacht['id'];
                           $data = base64_decode($klacht['foto']);
                            file_put_contents($name, $data);
                        echo '<img src="' . $name . '" alt="Klacht Foto" class="klacht-foto">';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="wijzig.php?id=<?php echo $klacht['id']; ?>">
                            <button>wijzig</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Voeg navigatieknoppen toe -->
        <div class="pagination" >
            <?php
            $totalPages = ceil($totalKlachten / $klachtenPerPage);

            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '&search=' . $searchTerm . '" class="aherf">Vorige</a>';
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?page=' . $i . '&search=' . $searchTerm . '" class="aherf">' . $i . '</a>';
            }

            if ($page < $totalPages) {
                echo '<a  href="?page=' . ($page + 1) . '&search=' . $searchTerm . '" class="aherf">Volgende</a>';
            }
            ?>
        </div>
    </div>
    <!-- Voeg deze code toe onderaan je HTML-bestand -->
    <div id="fotoModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImg"  style="width: 100%;">
    </div>
</div>


<script>
    function openModal() {
        document.getElementById('fotoModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('fotoModal').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        var klachtFotos = document.querySelectorAll('.klacht-foto');

        klachtFotos.forEach(function (foto) {
            foto.addEventListener('click', function () {
                var klachtId = this.getAttribute('data-klacht-id');
                var modal = document.getElementById('fotoModal');
                var modalImg = document.getElementById('modalImg');

                // Laad de foto in de modal
                modalImg.src = this.src;
                modalImg.alt = 'Klacht Foto ' + klachtId;

                // Toon de modal
                openModal();
            });
        });

        // Sluit de modal wanneer er buiten wordt geklikt
        window.addEventListener('click', function (event) {
            var modal = document.getElementById('fotoModal');
            if (event.target === modal) {
                closeModal();
            }
        });
    });
</script>

</body>
</html>
