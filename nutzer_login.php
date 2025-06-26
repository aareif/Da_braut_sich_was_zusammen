<?php
session_start(); // Session starten

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Benutzer anhand des Benutzernamens suchen
    $stmt = $conn->prepare("SELECT Benutzername, Passwort FROM Nutzer WHERE Benutzername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Wenn Benutzer existiert
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Passwort überprüfen
        if (password_verify($password, $row['Passwort'])) {
            // Erfolgreich eingeloggt
            $_SESSION['username'] = $username;
            echo "Login erfolgreich!";
            header("Location: login_erfolgreich.php"); // Weiterleitung nach Login
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
  <title>Minecraft Login</title>
  <style>
    @font-face {
      font-family: 'Minecraftia';
      src: url('https://cdn.jsdelivr.net/gh/IdreesInc/Minecraft-Font@master/Minecraftia.ttf') format('truetype');
    }

    body {
      background-color: #1e1e1e;
      font-family: 'Minecraftia', monospace;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-box {
      background-color: #121212;
      border: 5px solid #2a2a2a;
      padding: 30px;
      width: 400px;
      box-shadow: 0 0 0 4px #000, inset 0 0 0 4px #000;
    }

    .login-box h1 {
      text-align: center;
      background-color: #000;
      border: 2px solid #444;
      padding: 10px;
      margin-bottom: 30px;
      color: #ffffff;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 2px solid #88cc33;
      background-color: #e0e0e0;
      color: #333;
      font-family: 'Minecraftia', monospace;
      font-size: 12px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      border: 2px solid #444;
      background-color: #88cc33;
      color: white;
      font-family: 'Minecraftia', monospace;
      font-size: 14px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #76b82a;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h1>Willkommen!</h1>
    <form>
      <input type="text" placeholder="Benutzername">
      <input type="password" placeholder="Passwort">
      <input type="submit" value="Anmelden">
    </form>
  </div>
</body>
</html>
