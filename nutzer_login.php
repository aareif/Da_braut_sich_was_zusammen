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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Minecraft Login</title>

  <!-- Minecraft-Font eingebettet -->
  <style>
    @font-face {
      font-family: 'Minecraftia';
      src: url('https://fonts.cdnfonts.com/s/19893/Minecraftia.woff') format('woff');
    }

    html, body {
      margin: 0;
      padding: 0;
      font-family: 'Minecraftia', monospace;
      background-color: #1e1e1e;
      background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABg3Am1AAAAyUlEQVRoQ+2ZMQ6DMAxFbQ3//0sXjAUB7LtQ9N2S3EmXkQUBPQ7Bh3Kp8GzIyxVMBcM8lwCxMDwvF+wSVAAZ3zn8sR+d7B4RbEEp0QkH4nA+HQFTkBzTQCLxQkNgCML+1oQmyMrb8JSsB2RaDBJBK2FZyL5RC5qOHYMEGy0yF1hxQkFMCTcgK3GUEXyAKxFU3AeOswhaw9TViQmxVfxb2FuVP/BxyDh0oUvo0fcpgAAAABJRU5ErkJggg==');
      background-size: 64px;
      image-rendering: pixelated;
    }

    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .minecraft-block {
      background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABg3Am1AAAAyUlEQVRoQ+2ZMQ6DMAxFbQ3//0sXjAUB7LtQ9N2S3EmXkQUBPQ7Bh3Kp8GzIyxVMBcM8lwCxMDwvF+wSVAAZ3zn8sR+d7B4RbEEp0QkH4nA+HQFTkBzTQCLxQkNgCML+1oQmyMrb8JSsB2RaDBJBK2FZyL5RC5qOHYMEGy0yF1hxQkFMCTcgK3GUEXyAKxFU3AeOswhaw9TViQmxVfxb2FuVP/BxyDh0oUvo0fcpgAAAABJRU5ErkJggg==');
      background-size: cover;
      border: 4px solid #000;
      box-shadow: 0 0 0 8px #3f3f3f, 0 0 16px #000;
      width: 320px;
      padding: 20px;
      box-sizing: border-box;
      text-align: center;
    }

    .login-header {
      font-size: 18px;
      color: white;
      margin-bottom: 16px;
      background-color: rgba(0, 0, 0, 0.4);
      padding: 4px;
      border: 2px solid #555;
      box-shadow: inset 0 0 4px #000;
    }

    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      margin: 8px 0;
      padding: 8px;
      font-family: 'Minecraftia', monospace;
      font-size: 14px;
      background-color: #dedede;
      border: 2px solid #333;
      box-shadow: inset 2px 2px #fff, inset -2px -2px #999;
    }

    .login-form button {
      width: 100%;
      padding: 10px;
      font-family: 'Minecraftia', monospace;
      font-size: 14px;
      background-color: #6b8e23;
      color: white;
      border: 2px solid #444;
      cursor: pointer;
      box-shadow: inset 2px 2px #9acd32, inset -2px -2px #556b2f;
    }

    .login-form button:hover {
      background-color: #556b2f;
      box-shadow: inset 1px 1px #9acd32, inset -1px -1px #2f4f2f;
    }

    .login-form input:focus,
    .login-form button:focus {
      outline: none;
      border-color: #9acd32;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="minecraft-block">
      <div class="login-header">Willkommen!</div>
      <form class="login-form" method="post">
        <input type="text" name="username" placeholder="Benutzername" required />
        <input type="password" name="password" placeholder="Passwort" required />
        <button type="submit">Anmelden</button>
      </form>
    </div>
  </div>
</body>
</html>

