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

// Angepasstes Query; passe die Tabellennamen und Felder ggf. an!
$sql = "SELECT 
    r.Nr,
    r.Rezept,
    b.Name AS BasistrankName,
    t.Name AS TrankName,
    br.Name AS BrennstoffName,
    s.Name AS SpezialitemName
FROM 
    Rezept r
    LEFT JOIN Basistrank b ON r.BasisTrank_TrankID = b.Basistrank_TrankID
    LEFT JOIN Trank t ON r.TrankID = t.TrankID
    LEFT JOIN Brennstoff br ON r.BrennstoffID = br.BrennstoffID
    LEFT JOIN SpezialItem s ON r.SpezialItemID = s.SpezialItemID
WHERE
    r.TrankID = ?";

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
    if (!empty($data['BasistrankName'])) {
        echo '<div class="detail-row"><span class="label">Basistrank:</span> ' . htmlspecialchars($data['BasistrankName']) . '</div>';
    }
    if (!empty($data['SpezialitemName'])) {
        echo '<div class="detail-row"><span class="label">Spezial-Item:</span> ' . htmlspecialchars($data['SpezialitemName']) . '</div>';
    }
    if (!empty($data['BrennstoffName'])) {
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
