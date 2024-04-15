<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusyBot - Gestion des Portiers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/png" href="image/Busybot.png">
</head>

<body class="font-sans bg-gray-100">

    <header class="bg-gray-800 text-white text-center py-4">
        <a href="index.php" class="text-white flex items-center justify-center">
            <i class="fas fa-home text-lg mr-2"></i>
            <h1 class="text-2xl">Gestion des Portiers</h1>
        </a>
    </header>

    <nav class="bg-gray-700 text-white py-2">
        <div class="flex justify-end mx-4">
            <div class="relative group">
                <button id="menuBtn" class="flex items-center text-white focus:outline-none">
                    <span class="mr-2">Menu</span>
                    <i class="fas fa-caret-down"></i>
                </button>
                <div id="menuDropdown"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-md opacity-0 invisible focus:opacity-100 focus:visible transition duration-300">
                    <a href="historique.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300"
                        onmouseenter="showMenu()">Gestion des historiques</a>
                    <a href="user.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300"
                        onmouseenter="showMenu()">Gestion des utilisateurs</a>
                    <a href="local.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300"
                        onmouseenter="showMenu()">Gestion des locaux</a>
                    <a href="portier.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-300"
                        onmouseenter="showMenu()">Gestion des portiers</a>
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

            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deletePortier"])) {
                $portierId = $_GET["deletePortier"];

                $deletePortierSql = "DELETE FROM portier WHERE idportier=$portierId";

                if ($conn->query($deletePortierSql) === TRUE) {
                    header("Location: portier.php");
                    exit();
                } else {
                    echo "<p class='text-red-500'>Erreur lors de la suppression du portier : " . $conn->error . "</p>";
                }
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addPortier"])) {
                $idportier = filter_var($_POST["idportier"], FILTER_VALIDATE_INT);
                $ipportier = filter_var($_POST["ipportier"], FILTER_VALIDATE_IP);
                $cam = isset($_POST["cam"]) ? 1 : 0;
                $idlocal = filter_var($_POST["idlocal"], FILTER_VALIDATE_INT);

                $sql = "INSERT INTO portier (idportier, ipportier, cam, idlocal) VALUES ('$idportier', '$ipportier', '$cam', '$idlocal')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p class='text-green-500'>Portier ajouté avec succès.</p>";
                } else {
                    echo "<p class='text-red-500'>Erreur lors de l'ajout du portier : " . $conn->error . "</p>";
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["updatePortier"])) {
                    $idportierToUpdate = filter_var($_POST["updatePortierId"], FILTER_VALIDATE_INT);
                    $ipportier = filter_var($_POST["ipportier"], FILTER_VALIDATE_IP);
                    $cam = isset($_POST["cam"]) ? 1 : 0;
                    $idlocal = filter_var($_POST["idlocal"], FILTER_VALIDATE_INT);

                    $updateSql = "UPDATE portier SET ipportier='$ipportier', cam='$cam', idlocal='$idlocal' WHERE idportier=$idportierToUpdate";

                    if ($conn->query($updateSql) === TRUE) {
                        echo "<p class='text-green-500'>Portier mis à jour avec succès.</p>";
                    } else {
                        echo "<p class='text-red-500'>Erreur lors de la mise à jour du portier : " . $conn->error . "</p>";
                    }
                }
            }

            $result = $conn->query("SELECT * FROM portier");

            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                $cameraStatus = ($row['cam'] == 1) ? "Oui" : "Non";
                echo "<li>" . $row['ipportier'] . " - Emplacement : " . $row['idlocal'] . " - Camera : " . $cameraStatus . " "
                    . "<a href='javascript:void(0)' class='text-blue-500 ml-2' onclick='fillEditForm(" . $row['idportier'] . ", \"" . $row['ipportier'] . "\", " . $row['cam'] . ", " . $row['idlocal'] . ")'>Modifier</a>"
                    . "<a href='javascript:void(0)' class='text-red-500 ml-2' onclick='confirmDelete(" . $row['idportier'] . ")'>Supprimer</a></li>";
            }
            echo "</ul>";

            $conn->close();
        ?>

        <form action="" method="post" class="flex flex-col items-center mt-4">
            <input type="hidden" id="updatePortierId" name="updatePortierId">
            <label for="idportier" class="mb-2">ID Portier:</label>
            <input type="number" id="idportier" name="idportier" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full" min="0">

            <label for="ipportier" class="mb-2">Adresse IP Portier:</label>
            <input type="text" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" id="ipportier"name="ipportier" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full">

            <label for="cam" class="mb-2">Camera:</label>
            <input type="checkbox" id="cam" name="cam" class="mb-4">

            <label for="idlocal" class="mb-2">ID Local:</label>
            <select id="idlocal" name="idlocal" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full">
                <?php
                $servername = "localhost";
                $username = "ServiceAccount";
                $password = "S€rv!ce4ccount";
                $dbname = "DBBUSYBOT";
                
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("La connexion a échoué : " . $conn->connect_error);
                }
                    
                $result = $conn->query("SELECT idlocal, adresse FROM local WHERE idlocal NOT IN (SELECT DISTINCT idlocal FROM portier)");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['idlocal'] . "'>" . $row['adresse'] . "</option>";
                }
                if (isset($_POST["updatePortier"])) {
                    $idportier = $_POST["idportier"];
                    $stmt = $conn->prepare("SELECT local.idlocal, local.adresse FROM local JOIN portier ON local.idlocal = portier.idlocal WHERE portier.idportier = ?");
                    $stmt->bind_param("i", $idportier);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['idlocal'] . "'>" . $row['adresse'] . "</option>";
                    }
                    if ($conn->query($deletePortierSql) === TRUE) {
                        header("Location: portier.php");
                        exit();
                    } else {
                        echo "<p class='text-red-500'>Erreur lors de la suppression du portier : " . $conn->error . "</p>";
                    }
                }
                $conn->close();
                ?>
                
            </select>

            <?php
            if (isset($_POST["updatePortier"])) {
                echo '<button id="updatePortierBtn" type="submit" name="updatePortier" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:shadow-outline-yellow active:bg-yellow-800 transition duration-150 ease-in-out w-full">Modifier Portier</button>';
            } else {
                echo '<button id="updatePortierBtn" type="submit" name="addPortier" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue active:bg-blue-800 transition duration-150 ease-in-out w-full">Ajouter Portier</button>';
            }
            ?>
        </form>
        <p>
            Si vous n'avez pas de locaux, <a href="local.php" class="mt-2 text-blue-500 underline">cliquez ici</a>.
        </p>
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full">
        <p>&copy; BusyBot2024. Tous droits réservés.</p>
    </footer>

    <script>
        function confirmDelete(portierId) {
            var confirmation = confirm("Voulez-vous vraiment supprimer ce portier ?");
            if (confirmation) {
                window.location.href = "portier.php?deletePortier=" + portierId;
            }
        }
    function resetUpdatePortierButton() {
        var updatePortierBtn = document.getElementById('updatePortierBtn');
        if (updatePortierBtn) {
            updatePortierBtn.innerHTML = 'Ajouter Portier';
            updatePortierBtn.setAttribute('name', 'addPortier');
            updatePortierBtn.classList.remove('bg-yellow-500');
            updatePortierBtn.classList.add('bg-blue-500');
        }
    }

    function fillEditForm(portierId, ipportier, cam, idlocal) {
        document.getElementById('updatePortierId').value = portierId;
        document.getElementById('idportier').value = portierId;
        document.getElementById('ipportier').value = ipportier;
        document.getElementById('cam').checked = cam;
        document.getElementById('idlocal').value = idlocal;
        window.location.href = "portier.php?updatePortier=" + portierId;


        var updatePortierBtn = document.getElementById('updatePortierBtn');
        if (updatePortierBtn) {
            updatePortierBtn.innerHTML = 'Modifier Portier';
            updatePortierBtn.setAttribute('name', 'updatePortier');
            updatePortierBtn.classList.remove('bg-blue-500');
            updatePortierBtn.classList.add('bg-yellow-500');
        }
    }

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
