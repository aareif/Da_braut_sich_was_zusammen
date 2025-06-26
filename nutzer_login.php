<?php
session_start(); // Session starten

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT Benutzername, Passwort FROM Nutzer WHERE Benutzername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Passwort'])) {
            $_SESSION['username'] = $username;
            header("Location: login_erfolgreich.php");
            exit;
        } else {
            $error = "Falsches Passwort.";
        }
    } else {
        $error = "Benutzer wurde nicht gefunden.";
    }
}

$db->disconnect();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Minecraft Login Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
<style>
html, body {
    overflow: hidden; /* 🧱 SCROLLEN VERHINDERT – EINZIGER FIX */
}

body {
    font-family: 'Press Start 2P', cursive;
    background-color: #87CEEB;
    margin: 0;
    padding: 0;
}

.login-container {
    width: 400px;
    margin: 100px auto;
    background-color: #A0522D;
    border: 5px solid #8B4513;
    padding: 20px;
    box-shadow: 10px 10px 0 #000;
}

.grass-top {
    height: 50px;
    background-color: #228B22;
    border-bottom: 5px solid #006400;
}

.minecraft-title {
    text-align: center;
    color: #FFD700;
    font-size: 24px;
    margin: 20px 0;
    text-shadow: 2px 2px #000;
}

.subtitle {
    text-align: center;
    color: #90EE90;
    font-size: 14px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-input {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    background-color: #F5F5DC;
    border: 2px solid #8B4513;
    box-sizing: border-box;
}

.minecraft-btn {
    width: 100%;
    padding: 10px;
    background-color: #32CD32;
    border: 2px solid #006400;
    color: white;
    font-size: 14px;
    cursor: pointer;
}

.minecraft-btn:hover {
    background-color: #228B22;
}
</style>
</head>
<body>

<div class="login-container">
    <div class="grass-top"></div>
    <div class="minecraft-title">Minecraft Login</div>
    <div class="subtitle">Bitte anmelden</div>

    <?php if (!empty($error)) : ?>
        <div class="error-message" style="color:red; text-align:center; margin-bottom:10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <input type="text" name="username" class="form-input" placeholder="Benutzername" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-input" placeholder="Passwort" required>
        </div>
        <button type="submit" class="minecraft-btn">Einloggen</button>
    </form>
</div>

</body>
</html>
