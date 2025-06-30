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
$stmt = 0;
$trank_id = (int)$_GET['id']; // Hier TrankID verwenden

// Hole alle Rezept- und Trankdetails für diesen Trank (und seine Basistränke)
$sql = "SELECT 
    Nr, Rezept_Nr.Rezept, Basistrank_TrankID.Basistrank, TrankID.Trank, BrennstoffID.Brennstoff, SpizialItemID.SpizialItem
FROM 
    Rezept r, Rezept m, Basistrank, SpizialItem, Brenstoff
WHERE
 Rezept.BasisTrank_TrankID = BasisTrank.BasisTrank_TrankID and Rezept.TrankID = Trank.TrankID and Rezept.BrennstoffID = Brennstoff.BrennstoffID and Rezept.SpezialItemID = SpezialItem.SpezialItemID"

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $trank_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo '<div class="no-data">Kein Rezept für diesen Trank gefunden.</div>';
    $db->disconnect();
    exit;
}

// Anzeige der Rezeptdetails
function zeigeRezeptDetails($data) {
    echo '<div class="details">';
    echo '<div class="detail-row"><span class="label">Trank:</span> ' . htmlspecialchars($data['TrankName']) . '</div>';
    if ($data['BasistrankID']) {
        echo '<div class="detail-row"><span class="label">Basistrank:</span> ' . htmlspecialchars($data['BasistrankName']);
        // Basistrank2 anzeigen (verschachtelt)
        if ($data['Basistrank2ID']) {
            echo '<div class="details" style="margin-left:18px">';
            echo '<div class="detail-row"><span class="label">Basistrank2:</span> ' . htmlspecialchars($data['Basistrank2Name']) . '</div>';
            echo '<div class="detail-row"><span class="label">RezeptNr:</span> ' . htmlspecialchars($data['Basistrank2_RezeptNr']) . '</div>';
            echo '<div class="detail-row"><span class="label">Nr:</span> ' . htmlspecialchars($data['Basistrank2_Nr']) . '</div>';
            echo '</div>';
        }
        echo '</div>'; // Basistrank
    }
    if ($data['SpezialitemName']) {
        echo '<div class="detail-row"><span class="label">Spezial-Item:</span> ' . htmlspecialchars($data['SpezialitemName']) . '</div>';
    }
    if ($data['BrennstoffName']) {
        echo '<div class="detail-row"><span class="label">Brennstoff:</span> ' . htmlspecialchars($data['BrennstoffName']) . '</div>';
    }
    echo '</div>';
}

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Rezept anzeigen</title>
    <!-- ... dein CSS wie gehabt ... -->
</head>
<body>
    <div class="container">
        <div class="grass-top"></div>
        <div class="dirt-section">
            <a href="item_details.php?id=<?= urlencode($trank_id) ?>" class="minecraft-btn">Zurück zum Item</a>
            <div class="minecraft-title">
                <div class="title-text">REZEPT ANZEIGEN</div>
                <div class="subtitle">Brauanleitung für den Trank</div>
            </div>
            <?php
                zeigeRezeptDetails($data);
            ?>
        </div>
    </div>
</body>
</html>
