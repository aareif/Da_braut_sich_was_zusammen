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
    echo "Ungültige Item-ID.";
    exit;
}

$id = (int)$_GET['id'];

// Join zwischen Item und Trank (LEFT JOIN, falls kein Trank existiert)
$query = "
    SELECT 
        Item.ID as ItemID, Item.Name as ItemName, Beschreibung as ItemBeschreibung, 
        Trank.TrankID, Trank.Art, Trank.Stufe, Trank.Name as TrankName, Trank.Dauer, Trank.Beschreibung as TrankBeschreibung
    FROM Item
    LEFT JOIN Trank ON Item.ID = Trank.ItemID
    WHERE Item.ID = ?
    LIMIT 1
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Item Details</title>
     <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="logout-container">
    <form action="nutzer_logout.php" method="post">
        <button type="submit" class="logout-btn">Abmelden</button>
    </form>
</div>
    <div class="container">
        <div class="clearfix">
            <a href="suche.php" class="back-btn">Zurück zur Suche</a>
        </div>
        <h1>Item Details</h1>
        <?php if ($data): ?>
            <?php if (!empty($data['TrankID'])): ?>
                <div class="details">
                    <div class="detail-row"><span class="label">Name:</span> <?= htmlspecialchars($data['TrankName']) ?></div>
                    <div class="detail-row"><span class="label">Art:</span> <?= htmlspecialchars($data['Art']) ?></div>
                    <div class="detail-row"><span class="label">Stufe:</span> <?= htmlspecialchars($data['Stufe']) ?></div>
                    <div class="detail-row"><span class="label">Dauer:</span> <?= htmlspecialchars($data['Dauer']) ?></div>
                    <?php if (!empty($data['TrankBeschreibung'])): ?>
                        <div class="detail-row"><span class="label">Beschreibung:</span> <?= nl2br(htmlspecialchars($data['TrankBeschreibung'])) ?></div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="details">
                    <div class="detail-row"><span class="label">ID:</span> <?= htmlspecialchars($data['ItemID']) ?></div>
                    <div class="detail-row"><span class="label">Name:</span> <?= htmlspecialchars($data['ItemName']) ?></div>
                    <?php if (!empty($data['ItemBeschreibung'])): ?>
                        <div class="detail-row"><span class="label">Beschreibung:</span> <?= nl2br(htmlspecialchars($data['ItemBeschreibung'])) ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>Kein Item mit dieser ID gefunden.</p>
        <?php endif; ?>
    </div>
</body>
</html>
