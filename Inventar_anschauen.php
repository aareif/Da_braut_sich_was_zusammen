<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Falls der Nutzer kein Login hat:
    header("Location: nutzer_login.php");
    exit;
}

$username = $_SESSION['username'];

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

// Items, die der Nutzer bereits hat
$userItems = $conn->prepare("
    SELECT hat.Anzahl, Item.name 
    FROM hat 
    JOIN Item ON hat.hat_ItemID = Item.ID 
    WHERE hat.hat_Benutzername = ?
    ORDER BY Item.name
");
$userItems->bind_param("s", $username);
$userItems->execute();
$userItemsResult = $userItems->get_result();

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mein Inventar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="logout-container">
    <form action="nutzer_logout.php" method="post">
        <button type="submit" class="logout-btn">Abmelden</button>
    </form>
</div>
    <div class="container">
        <h1>Mein Inventar</h1>
        <?php if ($userItemsResult->num_rows > 0): ?>
            <ul>
                <?php while ($row = $userItemsResult->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($row['name']) ?>: <strong><?= (int)$row['Anzahl'] ?></strong></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="empty">Du hast noch keine Gegenstände gespeichert.</p>
        <?php endif; ?>
        <a href="suche.php" class="button">Suche</a>
        <a href="Inventar_hinzufügen.php" class="button">Gegenstand hinzufügen</a>
        <a href="Inventar_löschen.php" class="button">Löschen</a>
    </div>
</body>
</html>
