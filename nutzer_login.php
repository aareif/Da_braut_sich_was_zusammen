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

    /* Sonne */
    .sun {
        position: absolute;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 100px;
        background: #ffe066;
        box-shadow: inset 0 0 10px #fffab3, 0 0 20px #ffd700;
        animation: sunMove 8s ease-in-out infinite alternate;
    }

    @keyframes sunMove {
        0% { transform: translateX(-50%) translateY(0); }
        100% { transform: translateX(-50%) translateY(20px); }
    }

    /* Wolken */
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
        animation: cloudDrift 60s ease-in-out infinite alternate;
    }

    @keyframes cloudDrift {
        0% { transform: translateX(0); }
        100% { transform: translateX(20px); }
    }

    .cloud1 { top: 40px; left: 10%; transform: scale(1); z-index: 1; }
    .cloud2 { top: 90px; left: 30%; transform: scale(0.9); z-index: 2; }
    .cloud3 { top: 60px; left: 55%; transform: scale(1.1); z-index: 1; }
    .cloud4 { top: 120px; left: 70%; transform: scale(0.8); z-index: 2; }
    .cloud5 { top: 30px; left: 85%; transform: scale(1.2); z-index: 1; }

    /* Grasblock */
    .container {
        position: relative;
        top: 180px;
        margin: 0 auto;
        width: 420px;
        padding: 0;
        border: 4px solid #3c2f1f;
        box-shadow: 0 0 20px #000;
        text-align: center;
        background: linear-gradient(
            to bottom,
            #3fa23f 0px,
            #3fa23f 30px,
            #865f3a 30px,
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

    /* Sand und Meer */
    .sand {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 50px;
        background: repeating-linear-gradient(
            45deg,
            #f2d18a,
            #f2d18a 10px,
            #e5c37f 10px,
            #e5c37f 20px
        );
        z-index: 0;
    }

    .ocean {
        position: absolute;
        bottom: 50px;
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
        z-index: 0;
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

<!-- Wolken -->
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

<!-- Landschaft -->
<div class="ocean"></div>
<div class="sand"></div>

</body>
</html>
