<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();
$suchbegriff = "";

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: nutzer_login.php");
    exit;
}
$username = htmlspecialchars($_SESSION['username']);

if (isset($_GET['suchbegriff'])) {
    $suchbegriff = $conn->real_escape_string($_GET['suchbegriff']);
    $sql = "SELECT id, name FROM Item WHERE name LIKE '%$suchbegriff%'";
    $result = $conn->query($sql);
}
$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Minecraft Suche</title>
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
    background: linear-gradient(180deg, #87CEEB 0%, #B0E0E6 50%, #87CEFA 100%);
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
.search-container {
    position: relative;
    z-index: 10;
    max-width: 600px;
    margin: 40px auto 0;
    border: 0;
    border-radius: 0;
    box-shadow: 
        8px 8px 0 0 #2F4F2F,
        8px 8px 0 2px #1C3A1C,
        16px 16px 20px rgba(0,0,0,0.3);
    padding: 0;
    position: relative;
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
    padding: 30px 35px;
    min-height: 400px;
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

/* Improved Diamond decoration */
.diamond-decoration {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    width: 48px;
    height: 48px;
    background: 
        repeating-linear-gradient(
            45deg,
            #00FFFF 0px,
            #00FFFF 6px,
            #87CEEB 6px,
            #87CEEB 12px
        ),
        repeating-linear-gradient(
            -45deg,
            #00FFFF 0px,
            #00FFFF 6px,
            #87CEEB 6px,
            #87CEEB 12px
        );
    background-size: 12px 12px, 12px 12px;
    border: 3px solid #008B8B;
    box-shadow: 
        4px 4px 0 0 #2F4F2F,
        4px 4px 0 3px #1C3A1C;
    position: relative;
    transform: translateX(-50%) rotate(45deg);
}

/* Form styling */
.search-form {
    position: relative;
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
}

.form-input {
    flex: 1;
    padding: 12px 16px;
    font-family: 'Press Start 2P', monospace;
    font-size: 10px;
    background: 
        repeating-linear-gradient(
            0deg,
            #F5F5DC 0px,
            #F5F5DC 4px,
            #FFFACD 4px,
            #FFFACD 8px
        ),
        repeating-linear-gradient(
            90deg,
            #F5F5DC 0px,
            #F5F5DC 4px,
            #FFFACD 4px,
            #FFFACD 8px
        );
    background-size: 8px 8px, 8px 8px;
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
        repeating-linear-gradient(
            0deg,
            #FFFAF0 0px,
            #FFFAF0 4px,
            #FFFFF0 4px,
            #FFFFF0 8px
        ),
        repeating-linear-gradient(
            90deg,
            #FFFAF0 0px,
            #FFFAF0 4px,
            #FFFFF0 4px,
            #FFFFF0 8px
        );
    background-size: 8px 8px, 8px 8px;
}

.form-input::placeholder {
    color: #8B7355;
    opacity: 0.8;
}

/* Button styling */
.minecraft-btn {
    padding: 12px 20px;
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
    text-decoration: none;
    display: inline-block;
    margin-bottom: 10px;
    margin-right: 10px;
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

/* Logout button */
.logout-container {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.logout-btn {
    background: 
        repeating-linear-gradient(
            0deg,
            #FF6347 0px,
            #FF6347 6px,
            #DC143C 6px,
            #DC143C 12px
        ),
        repeating-linear-gradient(
            90deg,
            #FF6347 0px,
            #FF6347 6px,
            #DC143C 6px,
            #DC143C 12px
        );
    background-size: 12px 12px, 12px 12px;
    border-color: #8B0000;
    box-shadow: 
        inset 2px 2px 0 0 #FF7F7F,
        inset -2px -2px 0 0 #8B0000,
        4px 4px 0 0 #2F4F2F;
}

.logout-btn:hover {
    background: 
        repeating-linear-gradient(
            0deg,
            #FF7F7F 0px,
            #FF7F7F 6px,
            #FF6347 6px,
            #FF6347 12px
        ),
        repeating-linear-gradient(
            90deg,
            #FF7F7F 0px,
            #FF7F7F 6px,
            #FF6347 6px,
            #FF6347 12px
        );
    background-size: 12px 12px, 12px 12px;
}

/* Autocomplete styling */
.autocomplete-list {
    position: absolute;
    z-index: 10;
    background: 
        repeating-linear-gradient(
            0deg,
            #F5F5DC 0px,
            #F5F5DC 4px,
            #FFFACD 4px,
            #FFFACD 8px
        );
    background-size: 8px 8px;
    border: 3px solid #8B4513;
    border-radius: 0;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    box-shadow: 
        4px 4px 0 0 #2F4F2F,
        4px 4px 0 3px #1C3A1C;
    top: 50px;
    left: 0;
}

.autocomplete-item {
    padding: 10px;
    cursor: pointer;
    font-family: 'Press Start 2P', monospace;
    font-size: 8px;
    color: #2F4F2F;
    border-bottom: 1px solid #8B4513;
}

.autocomplete-item:hover, .autocomplete-item.active {
    background: 
        repeating-linear-gradient(
            0deg,
            #32CD32 0px,
            #32CD32 4px,
            #228B22 4px,
            #228B22 8px
        );
    background-size: 8px 8px;
    color: white;
    text-shadow: 1px 1px 0 #004d00;
}

/* Results styling */
.results {
    margin-top: 20px;
}

.results h2 {
    font-size: 12px;
    color: #FFD700;
    text-shadow: 
        2px 2px 0 #B8860B,
        4px 4px 0 #8B6914;
    margin-bottom: 15px;
}

.result-card {
    padding: 12px;
    margin-bottom: 10px;
    background: 
        repeating-linear-gradient(
            0deg,
            #F5F5DC 0px,
            #F5F5DC 4px,
            #FFFACD 4px,
            #FFFACD 8px
        ),
        repeating-linear-gradient(
            90deg,
            #F5F5DC 0px,
            #F5F5DC 4px,
            #FFFACD 4px,
            #FFFACD 8px
        );
    background-size: 8px 8px, 8px 8px;
    border: 2px solid #8B4513;
    border-radius: 0;
    box-shadow: 
        2px 2px 0 0 #2F4F2F,
        2px 2px 0 2px #1C3A1C;
}

.result-card a {
    text-decoration: none;
    color: #2F4F2F;
    font-family: 'Press Start 2P', monospace;
    font-size: 8px;
    font-weight: bold;
    transition: color 0.2s ease;
}

.result-card a:hover {
    color: #FFD700;
    text-shadow: 1px 1px 0 #B8860B;
}

/* Responsive design */
@media (max-width: 700px) {
    .search-container {
        margin: 20px 20px 0;
    }
    
    .dirt-section {
        padding: 25px 20px;
    }
    
    .title-text {
        font-size: 14px;
    }
    
    .form-input {
        font-size: 9px;
        padding: 10px 12px;
    }
    
    .minecraft-btn {
        font-size: 9px;
        padding: 10px 15px;
    }
    
    .search-form {
        flex-direction: column;
    }
    
    .search-form .minecraft-btn {
        width: 100%;
        margin-right: 0;
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
    
    <!-- Logout button -->
    <div class="logout-container">
        <form action="nutzer_logout.php" method="post">
            <button type="submit" class="minecraft-btn logout-btn">Abmelden</button>
        </form>
    </div>

    <div class="search-container">
        <!-- Diamond decoration -->
        <div class="diamond-decoration"></div>
        
        <!-- Grass top section -->
        <div class="grass-top"></div>
        
        <!-- Dirt/Earth section with search -->
        <div class="dirt-section">
            <!-- Title -->
            <div class="minecraft-title">
                <div class="title-text">ITEM SUCHE</div>
                <div class="subtitle">Finde deine Schätze</div>
            </div>

            <!-- Navigation buttons -->
            <a href="Inventar_anschauen.php" class="minecraft-btn">Inventar anschauen</a>

            <!-- Search form -->
            <form method="get" action="suche.php" class="search-form" autocomplete="off">
                <input type="text" id="suchbegriff" name="suchbegriff" value="<?= htmlspecialchars($suchbegriff); ?>" placeholder="Suchbegriff eingeben" class="form-input" required>
                <div id="autocomplete-list" class="autocomplete-list" style="display:none;"></div>
                <button type="submit" class="minecraft-btn">Suchen</button>
            </form>

            <!-- Results -->
            <div class="results">
                <h2>Suchergebnisse:</h2>
                <?php if (isset($result)): ?>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="result-card">
                                <a href="item_details.php?id=<?= $row['id']; ?>">
                                    <?= htmlspecialchars($row['name']); ?>
                                </a>
                            </div>  
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color: #FFD700; font-size: 8px; text-shadow: 1px 1px 0 #B8860B;">Keine Ergebnisse gefunden.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    // Autocomplete-Logik
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById('suchbegriff');
        const list = document.getElementById('autocomplete-list');
        let activeIndex = -1;
        let items = [];

        input.addEventListener('input', function() {
            const val = this.value;
            if (val.length === 0) {
                list.style.display = "none";
                return;
            }
            fetch("ajax_suche.php?q=" + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    items = data;
                    if (items.length > 0) {
                        items.forEach((item, idx) => {
                            const div = document.createElement("div");
                            div.className = "autocomplete-item";
                            div.innerHTML = item.name;
                            div.addEventListener('mousedown', function(e) {
                                input.value = item.name;
                                list.style.display = "none";
                            });
                            list.appendChild(div);
                        });
                        list.style.display = "block";
                    } else {
                        list.style.display = "none";
                    }
                });
        });

        // Keyboard navigation
        input.addEventListener('keydown', function(e) {
            let currentItems = list.querySelectorAll('.autocomplete-item');
            if (!currentItems.length) return;

            if (e.key === "ArrowDown") {
                activeIndex = (activeIndex + 1) % currentItems.length;
                setActive(currentItems);
                e.preventDefault();
            } else if (e.key === "ArrowUp") {
                activeIndex = (activeIndex - 1 + currentItems.length) % currentItems.length;
                setActive(currentItems);
                e.preventDefault();
            } else if (e.key === "Enter") {
                if (activeIndex > -1 && currentItems[activeIndex]) {
                    currentItems[activeIndex].dispatchEvent(new Event('mousedown'));
                    e.preventDefault();
                }
            } else {
                activeIndex = -1;
            }
        });

        document.addEventListener('click', function(e) {
            if (!list.contains(e.target) && e.target !== input) {
                list.style.display = "none";
            }
        });

        function setActive(items) {
            items.forEach((el, i) => {
                el.classList.toggle('active', i === activeIndex);
            });
        }
    });
    </script>
</body>
</html>
