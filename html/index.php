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
    </script>

</body>

</html>
