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
<title>Minecraft Login Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    font-family: 'Press Start 2P', monospace;
    background: linear-gradient(135deg, #87CEEB 0%, #98FB98 50%, #90EE90 100%);
    position: relative;
    overflow-x: hidden;
}

/* Animated background elements */
.bg-elements {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.cloud {
    position: absolute;
    background: white;
    border-radius: 0;
    opacity: 0.8;
    animation: float 20s infinite linear;
}

.cloud:before,
.cloud:after {
    content: '';
    position: absolute;
    background: white;
}

.cloud1 {
    width: 80px;
    height: 20px;
    top: 20%;
    left: -100px;
    box-shadow: 
        0 0 0 4px white,
        20px 0 0 4px white,
        40px 0 0 4px white,
        60px 0 0 4px white;
}

.cloud2 {
    width: 60px;
    height: 20px;
    top: 40%;
    left: -80px;
    animation-delay: -10s;
    box-shadow: 
        0 0 0 4px white,
        20px 0 0 4px white,
        40px 0 0 4px white;
}

.cloud3 {
    width: 100px;
    height: 20px;
    top: 60%;
    left: -120px;
    animation-delay: -5s;
    box-shadow: 
        0 0 0 4px white,
        20px 0 0 4px white,
        40px 0 0 4px white,
        60px 0 0 4px white,
        80px 0 0 4px white;
}

@keyframes float {
    from { transform: translateX(-200px); }
    to { transform: translateX(calc(100vw + 200px)); }
}

/* Pixelated grass effect */
.grass {
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 60px;
    background: 
        repeating-linear-gradient(
            90deg,
            #228B22 0px,
            #228B22 20px,
            #32CD32 20px,
            #32CD32 40px
        );
    z-index: 1;
}

/* Main container */
.login-container {
    position: relative;
    z-index: 10;
    max-width: 420px;
    margin: 80px auto 0;
    background: 
        linear-gradient(45deg, #8B4513 25%, transparent 25%),
        linear-gradient(-45deg, #8B4513 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #8B4513 75%),
        linear-gradient(-45deg, transparent 75%, #8B4513 75%),
        #A0522D;
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    border: 6px solid #654321;
    border-radius: 0;
    box-shadow: 
        0 0 0 2px #8B4513,
        8px 8px 0 0 #2F4F2F,
        8px 8px 0 2px #1C3A1C;
    padding: 40px 35px;
    position: relative;
}

/* Minecraft logo/title */
.minecraft-title {
    text-align: center;
    margin-bottom: 30px;
    position: relative;
}

.title-text {
    font-size: 18px;
    color: #FFD700;
    text-shadow: 
        2px 2px 0 #B8860B,
        4px 4px 0 #8B6914,
        6px 6px 8px rgba(0,0,0,0.3);
    letter-spacing: 2px;
    margin-bottom: 10px;
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from { text-shadow: 2px 2px 0 #B8860B, 4px 4px 0 #8B6914, 6px 6px 8px rgba(0,0,0,0.3), 0 0 10px #FFD700; }
    to { text-shadow: 2px 2px 0 #B8860B, 4px 4px 0 #8B6914, 6px 6px 8px rgba(0,0,0,0.3), 0 0 20px #FFD700, 0 0 30px #FFD700; }
}

.subtitle {
    font-size: 8px;
    color: #90EE90;
    text-shadow: 1px 1px 0 #006400;
}

/* Creeper face decoration */
.creeper-face {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    background: #0F5132;
    border: 3px solid #0A3D26;
    box-shadow: 
        inset 0 0 0 2px #0F5132,
        4px 4px 0 0 #2F4F2F;
}

.creeper-face:before {
    content: '';
    position: absolute;
    top: 15px;
    left: 15px;
    width: 8px;
    height: 8px;
    background: #000;
    box-shadow: 
        12px 0 0 0 #000,
        0 8px 0 0 #000,
        4px 8px 0 0 #000,
        8px 8px 0 0 #000,
        12px 8px 0 0 #000,
        6px 16px 0 0 #000;
}

/* Form styling */
.form-group {
    margin-bottom: 20px;
    position: relative;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    font-family: 'Press Start 2P', monospace;
    font-size: 10px;
    background: 
        linear-gradient(45deg, #F5F5DC 25%, transparent 25%),
        linear-gradient(-45deg, #F5F5DC 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #F5F5DC 75%),
        linear-gradient(-45deg, transparent 75%, #F5F5DC 75%),
        #FFFACD;
    background-size: 8px 8px;
    background-position: 0 0, 0 4px, 4px -4px, -4px 0px;
    border: 3px solid #8B4513;
    border-radius: 0;
    color: #2F4F2F;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: 
        inset 2px 2px 0 0 #DDD,
        inset -2px -2px 0 0 #999;
}

.form-input:focus {
    border-color: #FFD700;
    box-shadow: 
        inset 2px 2px 0 0 #DDD,
        inset -2px -2px 0 0 #999,
        0 0 0 3px rgba(255, 215, 0, 0.3);
    background: 
        linear-gradient(45deg, #FFFAF0 25%, transparent 25%),
        linear-gradient(-45deg, #FFFAF0 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #FFFAF0 75%),
        linear-gradient(-45deg, transparent 75%, #FFFAF0 75%),
        #FFFFF0;
    background-size: 8px 8px;
}

.form-input::placeholder {
    color: #8B7355;
    opacity: 0.8;
}

/* Button styling */
.minecraft-btn {
    width: 100%;
    padding: 15px;
    font-family: 'Press Start 2P', monospace;
    font-size: 12px;
    background: 
        linear-gradient(45deg, #32CD32 25%, transparent 25%),
        linear-gradient(-45deg, #32CD32 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #32CD32 75%),
        linear-gradient(-45deg, transparent 75%, #32CD32 75%),
        #228B22;
    background-size: 12px 12px;
    background-position: 0 0, 0 6px, 6px -6px, -6px 0px;
    color: white;
    border: 4px solid #006400;
    border-radius: 0;
    cursor: pointer;
    text-shadow: 2px 2px 0 #004d00;
    box-shadow: 
        inset 2px 2px 0 0 #90EE90,
        inset -2px -2px 0 0 #006400,
        4px 4px 0 0 #2F4F2F;
    transition: all 0.2s ease;
    margin-bottom: 15px;
}

.minecraft-btn:hover {
    background: 
        linear-gradient(45deg, #90EE90 25%, transparent 25%),
        linear-gradient(-45deg, #90EE90 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #90EE90 75%),
        linear-gradient(-45deg, transparent 75%, #90EE90 75%),
        #32CD32;
    background-size: 12px 12px;
    transform: translate(2px, 2px);
    box-shadow: 
        inset 2px 2px 0 0 #98FB98,
        inset -2px -2px 0 0 #006400,
        2px 2px 0 0 #2F4F2F;
}

.minecraft-btn:active {
    transform: translate(4px, 4px);
    box-shadow: 
        inset 2px 2px 0 0 #98FB98,
        inset -2px -2px 0 0 #006400;
}

/* Secondary button (register) */
.secondary-btn {
    background: 
        linear-gradient(45deg, #4169E1 25%, transparent 25%),
        linear-gradient(-45deg, #4169E1 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #4169E1 75%),
        linear-gradient(-45deg, transparent 75%, #4169E1 75%),
        #0000CD;
    background-size: 12px 12px;
    border-color: #000080;
    box-shadow: 
        inset 2px 2px 0 0 #6495ED,
        inset -2px -2px 0 0 #000080,
        4px 4px 0 0 #2F4F2F;
}

.secondary-btn:hover {
    background: 
        linear-gradient(45deg, #6495ED 25%, transparent 25%),
        linear-gradient(-45deg, #6495ED 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #6495ED 75%),
        linear-gradient(-45deg, transparent 75%, #6495ED 75%),
        #4169E1;
    background-size: 12px 12px;
}

/* Error message */
.error-message {
    background: 
        linear-gradient(45deg, #FF6347 25%, transparent 25%),
        linear-gradient(-45deg, #FF6347 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #FF6347 75%),
        linear-gradient(-45deg, transparent 75%, #FF6347 75%),
        #DC143C;
    background-size: 8px 8px;
    color: white;
    padding: 15px;
    margin-bottom: 20px;
    border: 3px solid #8B0000;
    font-size: 8px;
    text-shadow: 1px 1px 0 #8B0000;
    box-shadow: 
        inset 2px 2px 0 0 #FF7F7F,
        inset -2px -2px 0 0 #8B0000;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Responsive design */
@media (max-width: 500px) {
    .login-container {
        margin: 40px 20px 0;
        padding: 30px 25px;
    }
    
    .title-text {
        font-size: 14px;
    }
    
    .form-input {
        font-size: 9px;
        padding: 10px 12px;
    }
    
    .minecraft-btn {
        font-size: 10px;
        padding: 12px;
    }
}

/* Particle effects */
.particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 5;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: #FFD700;
    animation: sparkle 3s infinite;
}

.particle:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; }
.particle:nth-child(2) { left: 80%; top: 30%; animation-delay: 1s; }
.particle:nth-child(3) { left: 60%; top: 70%; animation-delay: 2s; }
.particle:nth-child(4) { left: 30%; top: 80%; animation-delay: 0.5s; }
.particle:nth-child(5) { left: 90%; top: 60%; animation-delay: 1.5s; }

@keyframes sparkle {
    0%, 100% { opacity: 0; transform: scale(0); }
    50% { opacity: 1; transform: scale(1); }
}
</style>
</head>
<body>
    <!-- Background elements -->
    <div class="bg-elements">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>
    
    <!-- Grass at bottom -->
    <div class="grass"></div>
    
    <!-- Particle effects -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="login-container">
        <!-- Creeper face decoration -->
        <div class="creeper-face"></div>
        
        <!-- Title -->
        <div class="minecraft-title">
            <div class="title-text">CRAFT LOGIN</div>
            <div class="subtitle">Enter the World</div>
        </div>

        <!-- Error message -->
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Login form -->
        <form method="POST" action="nutzer_login.php">
            <div class="form-group">
                <input type="text" name="username" class="form-input" placeholder="Spielername" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" class="form-input" placeholder="Geheimes Passwort" required>
            </div>
            
            <button type="submit" class="minecraft-btn">Anmelden</button>
        </form>

        <!-- Register button -->
        <form action="nutzer_registrierung.php" method="get">
            <button type="submit" class="minecraft-btn secondary-btn">Registrieren</button>
        </form>
    </div>
</body>
</html>
