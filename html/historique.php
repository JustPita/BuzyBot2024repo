<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusyBot - Historique Portier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="image/Busybot.png">
</head>

<body class="font-sans bg-gray-100">

    <header class="bg-gray-800 text-white text-center py-4">
        <a href="index.php" class="text-white flex items-center justify-center">
            <i class="fas fa-home text-lg mr-2"></i>
            <h1 class="text-2xl">Historique Portier</h1>
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
        <form method="get">
            <label for="idportier" class="block mb-2">Sélectionnez un portier :</label>
            <select name="idportier" id="idportier" class="border p-2 mb-4">
                <?php
                include 'config.php';
                $sql = "SELECT DISTINCT idportier FROM event";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $selected = isset($_GET['idportier']) && intval($_GET['idportier']) === $row['idportier'] ? 'selected' : '';
                    echo '<option value="' . $row['idportier'] . '" ' . $selected . '>Portier ' . $row['idportier'] . '</option>';
                }

                $conn->close();
                ?>
            </select>

            <label for="tri" class="block mb-2">Trier par :</label>
            <select name="tri" id="tri" class="border p-2 mb-4">
                <option value="date_desc" <?php echo isset($_GET['tri']) && $_GET['tri'] === 'date_desc' ? 'selected' : ''; ?>>Date (Décroissant)</option>
                <option value="date_asc" <?php echo isset($_GET['tri']) && $_GET['tri'] === 'date_asc' ? 'selected' : ''; ?>>Date (Croissant)</option>
                <option value="evenement_desc" <?php echo isset($_GET['tri']) && $_GET['tri'] === 'evenement_desc' ? 'selected' : ''; ?>>Evenement (Décroissant)</option>
                <option value="evenement_asc" <?php echo isset($_GET['tri']) && $_GET['tri'] === 'evenement_asc' ? 'selected' : ''; ?>>Evenement (Croissant)</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Afficher l'historique</button>
        </form>

        <?php
        if (isset($_GET['idportier'])) {
            $portier_id = is_numeric($_GET['idportier']) ? intval($_GET['idportier']) : 0;
            include 'config.php';

            $tri = isset($_GET['tri']) ? $_GET['tri'] : 'date_desc';

            switch ($tri) {
                case 'date_desc':
                    $order = 'date DESC';
                    break;
                case 'date_asc':
                    $order = 'date ASC';
                    break;
                case 'evenement_desc':
                    $order = 'evenement DESC';
                    break;
                case 'evenement_asc':
                    $order = 'evenement ASC';
                    break;
                default:
                    $order = 'date DESC';
            }

            $sql = "SELECT * FROM event WHERE idportier = {$portier_id} ORDER BY {$order}";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="bg-white border p-4 mb-4">';
                    echo '<p>Date: ' . $row['date'] . '</p>';
                    echo '<p>Evenement: ';
                    switch ($row['evenement']) {
                        case 'R':
                            echo 'REPONSE';
                            break;
                        case 'A':
                            echo 'APPEL';
                            break;
                        case 'C':
                            echo 'CONFIG';
                            break;
                        default:
                            echo $row['evenement'];
                            break;
                    }
                    echo '</p>';
                    echo '<p>Message: ' . $row['message'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-gray-600">Aucun événement trouvé pour le portier sélectionné.</p>';
            }
            $conn->close();
        }
        ?>
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
