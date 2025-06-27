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

$trank_id = (int)$_GET['id']; // Hier TrankID verwenden

// Hole alle Rezept- und Trankdetails für diesen Trank (und seine Basistränke)
$sql = "SELECT 
    r.TrankID AS Trank,
    t.Name AS TrankName,
    r.Basistrank_TrankID AS BasistrankID,
    bt.Name AS BasistrankName,
    r2.Rezept_nr AS Basistrank_RezeptNr,
    r2.Nr AS Basistrank_Nr,
    r2.Basistrank_TrankID AS Basistrank2ID,
    bt2.Name AS Basistrank2Name,
    r3.Rezept_nr AS Basistrank2_RezeptNr,
    r3.Nr AS Basistrank2_Nr,
    r.Spezialitem_ID,
    si.Name AS SpezialitemName,
    r.Brennstoff_ID,
    b.Name AS BrennstoffName
FROM 
    Rezept r
LEFT JOIN Trank t ON r.TrankID = t.TrankID
LEFT JOIN Trank bt ON r.Basistrank_TrankID = bt.TrankID
LEFT JOIN Rezept r2 ON r.Basistrank_TrankID = r2.TrankID
LEFT JOIN Trank bt2 ON r2.Basistrank_TrankID = bt2.TrankID
LEFT JOIN Rezept r3 ON r2.Basistrank_TrankID = r3.TrankID
LEFT JOIN Spezialitem si ON r.Spezialitem_ID = si.ID
LEFT JOIN Brennstoff b ON r.Brennstoff_ID = b.ID
WHERE r.TrankID = ?";
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
