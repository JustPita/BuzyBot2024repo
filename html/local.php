<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusyBot - Gestion des Locaux</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="image/Busybot.png">
</head>

<body class="font-sans bg-gray-100">

    <header class="bg-gray-800 text-white text-center py-4">
        <a href="index.php" class="text-white flex items-center justify-center">
            <i class="fas fa-home text-lg mr-2"></i>
            <h1 class="text-2xl">Gestion des Locaux</h1>
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

    <main class="max-w-3xl mx-auto mt-8 mb-20 p-4">
        <?php
            $servername = "localhost";
            $username = "ServiceAccount";
            $password = "S€rv!ce4ccount";
            $dbname = "DBBUSYBOT";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("La connexion a échoué : " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleteLocal"])) {
                $localId = $_GET["deleteLocal"];

                $deleteLocalSql = "DELETE FROM local WHERE idlocal=$localId";

                if ($conn->query($deleteLocalSql) === TRUE) {
                    header("Location: local.php");
                    exit();
                } else {
                    echo "<p class='text-red-500'>Erreur lors de la suppression du local : " . $conn->error . "</p>";
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addLocal"])) {
                $idlocal = filter_var($_POST["idlocal"], FILTER_VALIDATE_INT);
                $iplocal = filter_var($_POST["iplocal"], FILTER_VALIDATE_IP);
                $adresse = filter_var($_POST["adresse"], FILTER_SANITIZE_STRING);
            
                $sql = "INSERT INTO local (idlocal, iplocal, adresse) VALUES ('$idlocal', '$iplocal', '$adresse')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p class='text-green-500'>Local ajouté avec succès.</p>";
                } else {
                    echo "<p class='text-red-500'>Erreur lors de l'ajout du local : " . $conn->error . "</p>";
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateLocal"])) {
                $idlocal = filter_var($_POST["idlocal"], FILTER_VALIDATE_INT);
                $iplocal = filter_var($_POST["iplocal"], FILTER_VALIDATE_IP);
                $adresse = filter_var($_POST["adresse"], FILTER_SANITIZE_STRING);
            
                $updateSql = "UPDATE local SET iplocal='$iplocal', adresse='$adresse' WHERE idlocal=$idlocal";
            
                if ($conn->query($updateSql) === TRUE) {
                    echo "<p class='text-green-500'>Local mis à jour avec succès.</p>";
                } else {
                    echo "<p class='text-red-500'>Erreur lors de la mise à jour du local : " . $conn->error . "</p>";
                }
            }

            $result = $conn->query("SELECT * FROM local");

            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>" . $row['iplocal'] . " - " . $row['adresse']  ."  " .  " <a href='javascript:void(0)' class='text-red-500 ml-2' onclick='fillEditForm(" . $row['idlocal'] . ", \"" . $row['iplocal'] . "\", \"" . $row['adresse'] . "\")'>Modifier</a> <a href='javascript:void(0)' class='text-red-500 ml-2' onclick='confirmDelete(" . $row['idlocal'] . ", \"" . $row['iplocal'] . "\", \"" . $row['adresse'] . "\")'>Supprimer</a></li>";
            }
            echo "</ul>";

            $conn->close();
        ?>

        <form action="" method="post" class="flex flex-col items-center mt-4">
            <label for="idlocal" class="mb-2">ID Local:</label>
            <input type="number" id="idlocal" name="idlocal" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full" min="0">

            <label for="iplocal" class="mb-2">Adresse IP Local:</label>
            <input type="text" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" id="iplocal" name="iplocal" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full">

            <label for="adresse" class="mb-2">Adresse:</label>
            <textarea id="adresse" name="adresse" rows="4" required class="px-3 py-2 border border-grey-300 rounded-md mb-4 w-full"></textarea>

            <?php
                if(isset($_POST["updateLocal"])) {
                    echo '<button id="updatelocalBtn" type="submit" name="updateLocal" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:shadow-outline-yellow active:bg-yellow-800 transition duration-150 ease-in-out w-full">Modifier local</button>';
                } else {
                    echo '<button id="updatelocalBtn" type="submit" name="addLocal" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue active:bg-blue-800 transition duration-150 ease-in-out w-full">Ajouter Local</button>';
                }
            ?>
        </form>
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full">
        <p>&copy; BusyBot2024. Tous droits réservés.</p>
    </footer>

    <script>
        function resetUpdateButton() {
            var updatelocalBtn = document.getElementById('updatelocalBtn');
            if (updatelocalBtn) {
                updatelocalBtn.innerHTML = 'Ajouter Local';
                updatelocalBtn.setAttribute('name', 'addLocal');
                updatelocalBtn.classList.remove('bg-yellow-500');
                updatelocalBtn.classList.add('bg-blue-500');
            }
        }

        function fillEditForm(idlocal, iplocal, adresse) {
            document.getElementById('idlocal').value = idlocal;
            document.getElementById('iplocal').value = iplocal;
            document.getElementById('adresse').value = adresse;

            var updatelocalBtn = document.getElementById('updatelocalBtn');
            if (updatelocalBtn) {
                updatelocalBtn.innerHTML = 'Modifier local';
                updatelocalBtn.setAttribute('name', 'updateLocal');
                updatelocalBtn.classList.remove('bg-blue-500');
                updatelocalBtn.classList.add('bg-yellow-500');
            }
        }

        function confirmDelete(idlocal, iplocal, adresse) {
            var confirmation = confirm("Voulez-vous vraiment supprimer ce Local ?");
            if (confirmation) {
                window.location.href = "local.php?deleteLocal=" + idlocal;
                resetUpdateButton();
            }
        }

        window.onload = resetUpdateButton;

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