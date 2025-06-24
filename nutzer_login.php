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
<title>Login</title>
<link href="https://fonts.cdnfonts.com/css/minecraftia" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: 'Minecraftia', sans-serif;
        background: #8ecafc;
        overflow: hidden;
    }

    /* ANIMIERTE SONNE */
    .sun {
        position: absolute;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 40px;
        background: #ffe066;
        box-shadow: inset 0 0 5px #fffab3, 0 0 10px #ffd700;
        animation: sunMove 8s ease-in-out infinite alternate;
    }

    @keyframes sunMove {
        0% { transform: translateX(-50%) translateY(0); }
        100% { transform: translateX(-50%) translateY(20px); }
    }

    /* PIXEL-WOLKEN */
    .cloud {
        position: absolute;
        top: 100px;
        left: 20%;
        width: 100px;
        height: 60px;
        background: #fff;
        box-shadow:
            20px 10px #fff,
            40px 0 #fff,
            60px 10px #fff,
            80px 0 #fff;
        animation: cloudMove 60s linear infinite;
    }

    @keyframes cloudMove {
        0% { left: -150px; }
        100% { left: 110%; }
    }

    /* GRASBLOCK ALS LOGIN-KASTEN */
    .container {
        position: relative;
        top: 180px;
        margin: 0 auto;
        width: 320px;
        padding: 20px;
        border: 4px solid #3c2f1f;
        background: repeating-linear-gradient(
            to bottom,
            #3fa23f, #3fa23f 20px,
            #865f3a 20px, #865f3a 120px
        );
        box-shadow: 0 0 20px #000;
        border-radius: 4px;
        text-align: center;
    }

    .container h1 {
        margin-bottom: 20px;
        font-size: 16px;
        color: #fff;
        text-shadow: 1px 1px #000;
    }

    .container form input {
        width: 100%;
        margin-bottom: 15px;
        padding: 10px;
        background: #d7c19f;
        border: 2px solid #654321;
        color: #2d1a00;
        font-family: 'Minecraftia', sans-serif;
    }

    .container form button {
        width: 100%;
        padding: 10px;
        background: #4caf50;
        border: 2px solid #2e7d32;
        color: white;
        font-family: 'Minecraftia', sans-serif;
        cursor: pointer;
    }

    .container form button:hover {
        background: #388e3c;
    }

    .error {
        color: #ff4d4d;
        margin-bottom: 15px;
    }

    /* MEER & STRAND */
    .sand {
        position: absolute;
        bottom: 100px;
        width: 100%;
        height: 50px;
        background: repeating-linear-gradient(
            45deg,
            #f2d18a,
            #f2d18a 10px,
            #e5c37f 10px,
            #e5c37f 20px
        );
    }

    .ocean {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100px;
        background: repeating-linear-gradient(
            -45deg,
            #0099ff,
            #0099ff 10px,
            #33bbff 10px,
            #33bbff 20px
        );
        animation: wave 8s ease-in-out infinite alternate;
    }

    @keyframes wave {
        0% { background-position: 0 0; }
        100% { background-position: 40px 20px; }
    }
</style>
</head>
<body>

<div class="sun"></div>
<div class="cloud"></div>

<div class="container">
    <h1>Anmeldung</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="nutzer_login.php">
        <input type="text" name="username" placeholder="Spielername" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <button type="submit">Anmeldung</button>
    </form>

    <form action="nutzer_registrierung.php" method="get" style="margin-top: 10px;">
        <button type="submit">Registrierung</button>
    </form>
</div>

<div class="sand"></div>
<div class="ocean"></div>

</body>
</html>
