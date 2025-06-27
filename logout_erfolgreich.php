<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Abmeldung erfolgreich</title>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    height: 100vh;
    font-family: 'Press Start 2P', monospace;
    background: linear-gradient(180deg, #87CEEB 0%, #B0E0E6 50%, #87CEFA 100%);
    position: relative;
    overflow: hidden;
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

/* Minecraft Sun */
.sun {
    position: absolute;
    top: 15%;
    right: 20%;
    width: 80px;
    height: 80px;
    background: 
        linear-gradient(0deg, 
            #FFD700 0%, #FFD700 12.5%,
            #FFFF00 12.5%, #FFFF00 25%,
            #FFD700 25%, #FFD700 37.5%,
            #FFFF00 37.5%, #FFFF00 50%,
            #FFD700 50%, #FFD700 62.5%,
            #FFFF00 62.5%, #FFFF00 75%,
            #FFD700 75%, #FFD700 87.5%,
            #FFFF00 87.5%, #FFFF00 100%
        ),
        linear-gradient(90deg, 
            #FFD700 0%, #FFD700 12.5%,
            #FFFF00 12.5%, #FFFF00 25%,
            #FFD700 25%, #FFD700 37.5%,
            #FFFF00 37.5%, #FFFF00 50%,
            #FFD700 50%, #FFD700 62.5%,
            #FFFF00 62.5%, #FFFF00 75%,
            #FFD700 75%, #FFD700 87.5%,
            #FFFF00 87.5%, #FFFF00 100%
        );
    background-size: 10px 10px, 10px 10px;
    border: 2px solid #DAA520;
    animation: sunMove 30s ease-in-out infinite;
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.6);
}

.sun::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: 
        linear-gradient(0deg, 
            #FFFF99 0%, #FFFF99 25%,
            #FFFFCC 25%, #FFFFCC 50%,
            #FFFF99 50%, #FFFF99 75%,
            #FFFFCC 75%, #FFFFCC 100%
        );
    background-size: 5px 5px;
}

@keyframes sunMove {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(5deg); }
}

/* Minecraft Clouds */
.cloud {
    position: absolute;
    background: 
        repeating-linear-gradient(
            0deg,
            #FFFFFF 0px,
            #FFFFFF 4px,
            #F0F8FF 4px,
            #F0F8FF 8px
        );
    opacity: 0.9;
    animation: float 25s infinite linear;
}

.cloud1 {
    width: 96px;
    height: 32px;
    top: 20%;
    left: -120px;
    background: 
        repeating-linear-gradient(
            0deg,
            #FFFFFF 0px,
            #FFFFFF 8px,
            #F0F8FF 8px,
            #F0F8FF 16px
        ),
        repeating-linear-gradient(
            90deg,
            #FFFFFF 0px,
            #FFFFFF 8px,
            #F0F8FF 8px,
            #F0F8FF 16px
        );
    background-size: 16px 16px, 16px 16px;
}

.cloud2 {
    width: 80px;
    height: 24px;
    top: 35%;
    left: -100px;
    animation-delay: -12s;
    background: 
        repeating-linear-gradient(
            0deg,
            #FFFFFF 0px,
            #FFFFFF 6px,
            #F0F8FF 6px,
            #F0F8FF 12px
        ),
        repeating-linear-gradient(
            90deg,
            #FFFFFF 0px,
            #FFFFFF 6px,
            #F0F8FF 6px,
            #F0F8FF 12px
        );
    background-size: 12px 12px, 12px 12px;
}

.cloud3 {
    width: 112px;
    height: 40px;
    top: 50%;
    left: -140px;
    animation-delay: -6s;
    background: 
        repeating-linear-gradient(
            0deg,
            #FFFFFF 0px,
            #FFFFFF 10px,
            #F0F8FF 10px,
            #F0F8FF 20px
        ),
        repeating-linear-gradient(
            90deg,
            #FFFFFF 0px,
            #FFFFFF 10px,
            #F0F8FF 10px,
            #F0F8FF 20px
        );
    background-size: 20px 20px, 20px 20px;
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
    height: 80px;
    background: 
        repeating-linear-gradient(
            90deg,
            #228B22 0px,
            #228B22 16px,
            #32CD32 16px,
            #32CD32 32px,
            #228B22 32px,
            #228B22 48px,
            #90EE90 48px,
            #90EE90 64px
        );
    z-index: 1;
}

/* Main container - Minecraft Grass Block */
.container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 10;
    max-width: 500px;
    width: 90%;
    border: 0;
    border-radius: 0;
    box-shadow: 
        8px 8px 0 0 #2F4F2F,
        8px 8px 0 2px #1C3A1C,
        16px 16px 20px rgba(0,0,0,0.3);
    padding: 0;
    overflow: hidden;
}

/* Grass top part */
.grass-top {
    height: 60px;
    background: 
        repeating-linear-gradient(
            0deg,
            #228B22 0px,
            #228B22 8px,
            #32CD32 8px,
            #32CD32 16px,
            #90EE90 16px,
            #90EE90 24px,
            #228B22 24px,
            #228B22 32px
        ),
        repeating-linear-gradient(
            90deg,
            #228B22 0px,
            #228B22 8px,
            #32CD32 8px,
            #32CD32 16px,
            #90EE90 16px,
            #90EE90 24px,
            #228B22 24px,
            #228B22 32px
        );
    background-size: 32px 32px, 32px 32px;
    border-bottom: 4px solid #1C3A1C;
    position: relative;
}

/* Grass blades on top */
.grass-top::before {
    content: '';
    position: absolute;
    top: -8px;
    left: 0;
    right: 0;
    width: 100%;
    height: 16px;
    background: 
        repeating-linear-gradient(
            90deg,
            transparent 0px,
            transparent 12px,
            #228B22 12px,
            #228B22 16px,
            transparent 16px,
            transparent 20px,
            #32CD32 20px,
            #32CD32 24px,
            transparent 24px,
            transparent 36px,
            #90EE90 36px,
            #90EE90 40px,
            transparent 40px,
            transparent 48px
        );
}

/* Dirt/Earth part */
.dirt-section {
    background: 
        repeating-linear-gradient(
            0deg,
            #8B4513 0px,
            #8B4513 8px,
            #A0522D 8px,
            #A0522D 16px,
            #654321 16px,
            #654321 24px,
            #8B4513 24px,
            #8B4513 32px
        ),
        repeating-linear-gradient(
            90deg,
            #8B4513 0px,
            #8B4513 8px,
            #A0522D 8px,
            #A0522D 16px,
            #654321 16px,
            #654321 24px,
            #8B4513 24px,
            #8B4513 32px
        );
    background-size: 32px 32px, 32px 32px;
    padding: 40px 35px;
    text-align: center;
}

/* Minecraft logo/title */
.minecraft-title {
    text-align: center;
    margin-bottom: 25px;
    position: relative;
}

.title-text {
    font-size: 16px;
    color: #FFD700;
    text-shadow: 
        2px 2px 0 #B8860B,
        4px 4px 0 #8B6914,
        6px 6px 8px rgba(0,0,0,0.3);
    letter-spacing: 2px;
    margin-bottom: 8px;
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

/* Content styling */
.success-message {
    color: #90EE90;
    font-size: 10px;
    text-shadow: 1px 1px 0 #006400;
    margin-bottom: 30px;
}

/* Button styling */
.minecraft-btn {
    width: 100%;
    padding: 15px;
    font-family: 'Press Start 2P', monospace;
    font-size: 10px;
    background: 
        repeating-linear-gradient(
            0deg,
            #32CD32 0px,
            #32CD32 6px,
            #228B22 6px,
            #228B22 12px
        ),
        repeating-linear-gradient(
            90deg,
            #32CD32 0px,
            #32CD32 6px,
            #228B22 6px,
            #228B22 12px
        );
    background-size: 12px 12px, 12px 12px;
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
        repeating-linear-gradient(
            0deg,
            #90EE90 0px,
            #90EE90 6px,
            #32CD32 6px,
            #32CD32 12px
        ),
        repeating-linear-gradient(
            90deg,
            #90EE90 0px,
            #90EE90 6px,
            #32CD32 6px,
            #32CD32 12px
        );
    background-size: 12px 12px, 12px 12px;
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

/* Secondary button */
.secondary-btn {
    background: 
        repeating-linear-gradient(
            0deg,
            #4169E1 0px,
            #4169E1 6px,
            #0000CD 6px,
            #0000CD 12px
        ),
        repeating-linear-gradient(
            90deg,
            #4169E1 0px,
            #4169E1 6px,
            #0000CD 6px,
            #0000CD 12px
        );
    background-size: 12px 12px, 12px 12px;
    border-color: #000080;
    box-shadow: 
        inset 2px 2px 0 0 #6495ED,
        inset -2px -2px 0 0 #000080,
        4px 4px 0 0 #2F4F2F;
}

.secondary-btn:hover {
    background: 
        repeating-linear-gradient(
            0deg,
            #6495ED 0px,
            #6495ED 6px,
            #4169E1 6px,
            #4169E1 12px
        ),
        repeating-linear-gradient(
            90deg,
            #6495ED 0px,
            #6495ED 6px,
            #4169E1 6px,
            #4169E1 12px
        );
    background-size: 12px 12px, 12px 12px;
}

/* Responsive design */
@media (max-width: 600px) {
    .container {
        position: relative;
        top: auto;
        left: auto;
        transform: none;
        margin: 50px 20px 0;
        max-width: none;
        width: calc(100% - 40px);
    }
    
    .dirt-section {
        padding: 30px 25px;
    }
    
    .title-text {
        font-size: 14px;
    }
    
    .minecraft-btn {
        font-size: 9px;
        padding: 12px;
    }
}
</style>
</head>
<body>
    <!-- Background elements -->
    <div class="bg-elements">
        <div class="sun"></div>
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>
    
    <!-- Grass at bottom -->
    <div class="grass"></div>

    <div class="container">
        <!-- Grass top section -->
        <div class="grass-top"></div>
        
        <!-- Dirt/Earth section -->
        <div class="dirt-section">
            <!-- Title -->
            <div class="minecraft-title">
                <div class="title-text">ABMELDUNG ERFOLGREICH</div>
                <div class="subtitle">Auf Wiedersehen</div>
            </div>

            <!-- Success message -->
            <p class="success-message">Du wurdest abgemeldet.</p>

            <!-- Navigation buttons -->
            <form action="nutzer_login.php" method="get">
                <button class="minecraft-btn" type="submit">Zurück zur Anmeldung</button>
            </form>
            <form action="nutzer_registrierung.php" method="get">
                <button class="minecraft-btn secondary-btn" type="submit">Zur Registrierung</button>
            </form>
        </div>
    </div>
</body>
</html>
