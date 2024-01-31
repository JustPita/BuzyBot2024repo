<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BusyBot - Gestion des Utilisateurs</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="icon" type="image/png" href="image/Busybot.png">
</head>

<body class="font-sans bg-gray-100">

<header class="bg-gray-800 text-white text-center py-4">
    <a href="index.php" class="text-white flex items-center justify-center">
        <i class="fas fa-home text-lg mr-2"></i>
        <h1 class="text-2xl">Gestion des Utilisateurs</h1>
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

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addUser"])) {

            $iduser = filter_var($_POST["iduser"], FILTER_VALIDATE_INT);
            $nom = filter_var($_POST["nom"], FILTER_SANITIZE_STRING);
            $prenom = filter_var($_POST["prenom"], FILTER_SANITIZE_STRING);
            $tel = filter_var($_POST["tel"], FILTER_SANITIZE_STRING);
            $mail = filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL);

            $sql = "INSERT INTO user (iduser, nom, prenom, tel, mail) VALUES ('$iduser','$nom', '$prenom', '$tel', '$mail')";

            if ($conn->query($sql) === TRUE) {
                echo "<p class='text-green-500'>Utilisateur ajouté avec succès.</p>";
            } else {
                echo "<p class='text-red-500'>Erreur lors de l'ajout de l'utilisateur : " . $conn->error . "</p>";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleteUser"])) {
            $userId = $_GET["deleteUser"];

            $deleteAffecterSql = "DELETE FROM Affecter WHERE iduser=$userId";
        
            if ($conn->query($deleteAffecterSql) === TRUE) {
                $deleteUserSql = "DELETE FROM user WHERE iduser=$userId";
        
                if ($conn->query($deleteUserSql) === TRUE) {
                    header("Location: user.php");
                    exit();
                } else {
                    echo "<p class='text-red-500'>Erreur lors de la suppression de l'utilisateur : " . $conn->error . "</p>";
                }
            } else {
                echo "<p class='text-red-500'>Erreur lors de la suppression des liaisons : " . $conn->error . "</p>";
            }
        }
        

        $result = $conn->query("SELECT * FROM user");

        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['nom'] . " " . $row['prenom'] . " <a href='javascript:void(0)' class='text-red-500 ml-2' onclick='confirmDelete(" . $row['iduser'] . ")'>Supprimer</a></li>";
        }
        echo "</ul>";

        $conn->close();
    ?>

<form action="" method="post" class="flex flex-col items-center mt-4">
        <label for="iduser" class="mb-2">Iduser:</label>
        <input type="number" id="iduser" name="iduser" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full" min="0">

        <label for="nom" class="mb-2">Nom:</label>
        <input type="text" id="nom" name="nom" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full">
        
        <label for="prenom" class="mb-2">Prénom:</label>
        <input type="text" id="prenom" name="prenom" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full">

        <label for="tel" class="mb-2">Téléphone:</label>
        <input type="text" id="tel" name="tel" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full">

        <label for="mail" class="mb-2">E-mail:</label>
        <input type="text" id="mail" name="mail" required class="px-3 py-2 border border-gray-300 rounded-md mb-4 w-full">
        
        <button type="submit" name="addUser" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue active:bg-blue-800 transition duration-150 ease-in-out w-full">Ajouter utilisateur</button>
    </form>
</main>

<footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full">
    <p>&copy; BusyBot2024. Tous droits réservés.</p>
</footer>

<script>
        function confirmDelete(userId) {
    var confirmation = confirm("Voulez-vous vraiment supprimer cet utilisateur ?");
    if (confirmation) {
        window.location.href = "user.php?deleteUser=" + userId;
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
