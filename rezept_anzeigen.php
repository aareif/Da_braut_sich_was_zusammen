<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: nutzer_login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Ungültige Trank-ID.";
    exit;
}

$item_id = (int)$_GET['id'];

// Hilfsfunktion zum rekursiven Ausgeben des Rezepts
function zeigeRezept($conn, $item_id, $istBasistrank = false) {
    // Hole den Trank anhand der ItemID
    $sql = "SELECT 
                Trank.TrankID, Trank.Name AS TrankName, Trank.BasistrankID, 
                Trank.SpezialItem, Trank.Brennstoff,
                Basistrank.Name AS BasistrankName
            FROM Trank 
            LEFT JOIN Trank AS Basistrank ON Trank.BasistrankID = Basistrank.TrankID
            WHERE Trank.ItemID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $trank = $result->fetch_assoc();

    if (!$trank) {
        echo '<div class="no-data">Kein Rezept für diesen Trank gefunden.</div>';
        return;
    }

    echo '<div class="details">';
    echo '<div class="detail-row"><span class="label">'.($istBasistrank ? 'Basistrank' : 'Trank').':</span> ' . htmlspecialchars($trank['TrankName']) . '</div>';
    if ($trank['BasistrankID']) {
        echo '<div class="detail-row"><span class="label">Basistrank:</span>';
        // Rekursiv das Rezept des Basistranks anzeigen (verschachtelt)
        zeigeRezept($conn, $trank['BasistrankID'], true);
        echo '</div>';
    }
    echo '<div class="detail-row"><span class="label">Spezial-Item:</span> ' . htmlspecialchars($trank['SpezialItem']) . '</div>';
    echo '<div class="detail-row"><span class="label">Brennstoff:</span> ' . htmlspecialchars($trank['Brennstoff']) . '</div>';
    echo '</div>';
}

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rezept anzeigen</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        /* Hier kannst du deinen gesamten CSS-Code aus item_details.php übernehmen */
        /* ... (kopiere alles aus dem <style> Bereich oben) ... */
        /* Damit die Details auch verschachtelt hübsch aussehen: */
        .details .details {
            margin-left: 18px;
            margin-top: 6px;
            background: #f8f8f0;
            border-color: #c2b280;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="bg-elements">
        <div class="sun"></div>
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>
    <!-- Grass at bottom -->
    <div class="grass"></div>

    <div class="container">
        <div class="grass-top"></div>
        <div class="dirt-section">
            <a href="item_details.php?id=<?= urlencode($item_id) ?>" class="minecraft-btn">Zurück zum Item</a>
            <div class="minecraft-title">
                <div class="title-text">REZEPT ANZEIGEN</div>
                <div class="subtitle">Brauanleitung für den Trank</div>
            </div>
            <?php
                // Verbindung erneut aufbauen, da sie oben nach Disconnect geschlossen wurde
                $db = new Database();
                $conn = $db->connect();
                zeigeRezept($conn, $item_id, false);
                $db->disconnect();
            ?>
        </div>
    </div>
</body>
</html>
