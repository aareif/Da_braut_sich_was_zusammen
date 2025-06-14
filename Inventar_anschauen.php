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
    <style>
        body {font-family: Arial, sans-serif; color: #4a5568; background: #edf2f7; margin: 0; padding: 20px;}
        .container {max-width: 500px; margin: 50px auto; padding: 30px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px -1px #0000001a;}
        h1 {margin-bottom: 20px;}
        ul {padding-left: 20px;}
        li {margin-bottom: 8px;}
        .empty {color: #e53e3e;}
        a.button {display: inline-block; margin-top: 25px; padding: 10px 20px; background: #3182ce; color: #fff; border-radius: 6px; text-decoration: none;}
        a.button {display: inline-block; margin-top: 25px; margin-right: 10px; padding: 10px 20px; background: #3182ce; color: #fff; border-radius: 6px; text-decoration: none;}
        a.button:hover {background: #2c5282;}
        .logout-container {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1000;
}
.logout-btn {
    padding: 10px 20px;
    background: #e53e3e;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 1em;
    cursor: pointer;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(0,0,0,0.07);
    transition: background 0.2s;
}
.logout-btn:hover {
    background: #a60000;
}
    </style>
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
