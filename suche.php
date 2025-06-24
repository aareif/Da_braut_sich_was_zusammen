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
    <title>Suche</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #edf2f7;
            color: #4a5568;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px #0000001a;
        }
        h1 {
            margin-bottom: 20px;
        }
        .inventar-btn, .logout-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 20px;
            background: #3182ce;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            font-size: 1em;
            cursor: pointer;
            margin-right: 10px;
        }
        .inventar-btn:hover, .logout-btn:hover {
            background: #2c5282;
        }
        form.logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        form.logout button {
            background: #e53e3e;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
            font-weight: bold;
        }
        form.logout button:hover {
            background: #a60000;
        }
        .search-form {
            position: relative;
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1em;
            flex: 1;
        }
        .search-form button {
            padding: 10px 20px;
            background: #3182ce;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
        }
        .search-form button:hover {
            background: #2c5282;
        }
        .autocomplete-list {
            position: absolute;
            z-index: 10;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            top: 40px;
            left: 0;
        }
        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-item:hover, .autocomplete-item.active {
            background: #3182ce;
            color: #fff;
        }
        .results {
            margin-top: 10px;
            text-align: left;
        }
        .result-card {
            padding: 12px;
            margin-bottom: 10px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .result-card strong {
            color: #2d3748;
        }
    </style>
</head>
<body>
    <form action="nutzer_logout.php" method="post" class="logout">
        <button type="submit">Abmelden</button>
    </form>
    <div class="container">
        <a href="Inventar_anschauen.php" class="inventar-btn">Inventar anschauen</a>
        <h1>Suche</h1>
        <form method="get" action="suche.php" class="search-form" autocomplete="off" id="search-form">
            <input type="text" id="suchbegriff" name="suchbegriff" value="<?= htmlspecialchars($suchbegriff); ?>" placeholder="Suchbegriff eingeben" required>
            <div id="autocomplete-list" class="autocomplete-list" style="display:none;"></div>
            <button type="submit">Suchen</button>
        </form>
        <div class="results">
            <h2>Suchergebnisse:</h2>
            <?php if (isset($result)): ?>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                      <div class="result-card">
    <a href="item_details.php?id=<?= $row['id']; ?>" style="text-decoration:none; color:#3182ce; font-weight:bold;">
        <?= htmlspecialchars($row['name']); ?>
    </a>
</div>  
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Keine Ergebnisse gefunden.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
    // Autocomplete-Logik
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById('suchbegriff');
        const list = document.getElementById('autocomplete-list');
        const form = document.getElementById('search-form');
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
    form.submit(); // Formular absenden!
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
