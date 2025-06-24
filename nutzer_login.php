<?php
session_start();

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

    .sun {
        position: absolute;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 80px;
        background: #ffe066;
        box-shadow: inset 0 0 10px #fffab3, 0 0 20px #ffd700;
        animation: sunMove 8s ease-in-out infinite alternate;
    }

    @keyframes sunMove {
        0% { transform: translateX(-50%) translateY(0); }
        100% { transform: translateX(-50%) translateY(20px); }
    }

    .cloud {
        position: absolute;
        width: 100px;
        height: 60px;
        background: #fff;
        box-shadow:
            20px 10px #fff,
            40px 0 #fff,
            60px 10px #fff,
            80px 0 #fff;
        animation: cloudMove linear infinite;
    }

    /* Cloud Variationen */
    .cloud1 {
        top: 50px;
        left: -150px;
        transform: scale(1);
        animation-duration: 80s;
    }

    .cloud2 {
        top: 100px;
        left: -250px;
        transform: scale(0.9);
        animation-duration: 100s;
    }

    .cloud3 {
        top: 150px;
        left: -200px;
        transform: scale(1.1);
        animation-duration: 70s;
    }

    .cloud4 {
        top: 70px;
        left: -300px;
        transform: scale(0.8);
        animation-duration: 90s;
    }

    .cloud5 {
        top: 180px;
        left: -100px;
        transform: scale(1.2);
        animation-duration: 110s;
    }

    @keyframes cloudMove {
        0% { left: -200px; }
        100% { left: 110%; }
    }

    .container {
        position: relative;
        top: 200px;
        margin: 0 auto;
        width: 380px;
        padding: 0;
        border: 4px solid #3c2f1f;
        box-shadow: 0 0 20px #000;
        border-radius: 4px;
        text-align: center;
        background: linear-gradient(
            to bottom,
            #3fa23f 0px,
            #3fa23f 50px,
            #865f3a 50px,
            #865f3a 100%
        );
    }

    .container h1 {
        padding-top: 20px;
        margin-bottom: 20px;
        font-size: 18px;
        color: #fff;
        text-shadow: 1px 1px #000;
    }

    .container form {
        padding: 0 20px 20px 20px;
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

<!-- Sonne -->
<div class="sun"></div>

<!-- Wolken in verschiedenen Höhen -->
<div class="cloud cloud1"></div>
<div class="cloud cloud2"></div>
<div class="cloud cloud3"></div>
<div class="cloud cloud4"></div>
<div class="cloud cloud5"></div>

<!-- Grasblock -->
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

<!-- Strand und Meer -->
<div class="sand"></div>
<div class="ocean"></div>

</body>
</html>
