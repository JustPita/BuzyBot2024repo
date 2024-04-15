<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusyBot - Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="image/Busybot.png">
</head>
<body class="font-sans bg-gray-100">

    <header class="bg-gray-800 text-white text-center py-4">
        <a href="index.php" class="text-white flex items-center justify-center">
            <i class="fas fa-home text-lg mr-2"></i>
            <h1 class="text-2xl">Bienvenue sur BusyBot</h1>
        </a>
    </header>

    <nav class="bg-gray-700 text-white py-2">
        <div class="flex justify-end mx-4">
            <div class="relative group">
                <button id="menuBtn" class="flex items-center text-white focus:outline-none">
                    <span class="mr-2">Menu</span>
                    <i class="fas fa-caret-down"></i>
                </button>
                <div id="menuDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-md opacity-0 invisible focus:opacity-100 focus:visible transition duration-300">
                    <a href="historique.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" onmouseenter="showMenu()">Gestion des historiques</a>
                    <a href="user.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" onmouseenter="showMenu()">Gestion des utilisateurs</a>
                    <a href="local.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" onmouseenter="showMenu()">Gestion des locaux</a>
                    <a href="portier.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300" onmouseenter="showMenu()">Gestion des portiers</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto mt-8 mb-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-8">
            <?php
                include 'config.php';
                $sql = "SELECT idportier, NPD, (SELECT message FROM event WHERE event.idportier = portier.idportier ORDER BY date DESC LIMIT 1) AS last_message
                FROM portier";
                $result = $conn->query($sql);
                $portiers = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $portiers[] = $row;
                    }
                }
                $conn->close();
            ?>
            <?php foreach ($portiers as $portier):
        $images = glob("/var/www/html/portierIMG/portier" . $portier['idportier'] . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        usort($images, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        $photo = isset($images[0]) ? basename($images[0]) : null;
    ?>
    <div class="bg-white shadow-md rounded-lg p-4 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-semibold text-gray-800"><?php echo "Portier: " . $portier['idportier']; ?></h2>
            <i class="fas fa-user-circle text-gray-400 text-3xl"></i>
            <?php if ($portier['NPD'] == 1): ?>
                <i class="fas fa-circle text-red-500 text-xl" title="Mode ne pas déranger activé"></i>
            <?php elseif ($portier['NPD'] == 0): ?>
                <i class="fas fa-circle text-green-500 text-xl" title="Mode ne pas déranger désactivé"></i>
            <?php endif; ?>
        </div>
        <?php if ($photo): ?>
            <a href="/portierIMG/<?php echo $photo; ?>" target="_blank">
        <img src="/portierIMG/<?php echo $photo; ?>" alt="Photo du portier <?php echo $portier['idportier']; ?>" class="mb-2 w-full h-64 object-cover">
    </a>
        <?php endif; ?>
        <div>
            <a href="historique.php?idportier=<?php echo $portier['idportier']; ?>" class="text-blue-500 underline">Historique</a>
            <p class="text-gray-600"><?php echo "Dernier message: " . ($portier['last_message'] ?? 'Aucun message'); ?></p>
        </div>
    </div>
    <?php endforeach; ?>
        </div>
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full">
        <p>&copy; BusyBot2024. Tous droits réservés.</p>
    </footer>

    <script>
        var menuBtn = document.getElementById('menuBtn');
        var menuDropdown = document.getElementById('menuDropdown');

        function showMenu() {
            menuDropdown.classList.remove('invisible');
            menuDropdown.classList.add('opacity-100');
        }
        function hideMenu() {
            menuDropdown.classList.remove('opacity-100');
            menuDropdown.classList.add('invisible');
        }
        menuBtn.addEventListener('mouseenter', showMenu);
        menuBtn.addEventListener('mouseleave', hideMenu);
        menuDropdown.addEventListener('mouseenter', showMenu);

        async function fetchPortiers() {
            const response = await fetch('getPortiers.php');
            const portiers = await response.json();

            const grid = document.querySelector('.grid');

            portiers.forEach(portier => {
                const box = document.createElement('div');
                box.classList.add('bg-white', 'border', 'p-4');
                box.innerHTML = `
                    <p>Portier: ${portier.idportier}</p>
                    <p>Dernier message: ${portier.last_message ?? 'Aucun message'}</p>
                `;
                grid.appendChild(box);
            });
        }

        fetchPortiers();
    </script>

</body>
</html>