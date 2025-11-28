<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main/style.css">
    <title>Portfolio</title>
    <?php
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
            'root',
            '' // mot de passe vide en local
        );
        $stmt = $pdo->query('SELECT * FROM nous WHERE id=2');
        $personne = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur de connexion:" . $e->getMessage());
    }


    ?>
</head>

<body>
    <!-- Bandeau d'en-tête -->
    <header class="bandeau">
        <!-- Retourner sur la page d'acceuil -->
        <div>
            <a href="http://localhost/projet_ue2/main/index.html">
                <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
            </a>
        </div>
        <!-- portfolio d'Alex -->
        <h1><?= $personne["nom"] ?>'s Portfolio</h1>
    </header>



    <div class="containeer">
        <!-- photo du portfolio -->
        <img src="../image/alex.jpg" alt="Photo" width="500">
        <!-- box d'infos d'Alex-->
        <div class="info-containeer">

            <div class="info-box">
                <h3>Name</h3>
                <p><?= $personne["nom"] ?></p>
            </div>

            <div class="info-box">
                <h3>Age</h3>
                <p><?= $personne["Age"] ?> years old</p>
            </div>

            <div class="info-box">
                <h3>Qualities</h3>
                <p><?= $personne["Qualities"] ?></p>
            </div>

            <div class="info-box">
                <h3>SKILLS</h3>
                <p><?= $personne["Skills"] ?></p>
            </div>

            <div class="info-box">
                <h3>Experience / Professional interests</h3>
                <p><?= $personne["Experience"] ?></p>
            </div>

            <div class="info-box">
                <h3>Passions</h3>
                <p><?= $personne["Passions"] ?></p>
            </div>
        </div>
    </div>

    <footer>
        <!-- Copyright -->
        © <?php echo date("d/m/Y") ?> - My Portfolio
        <footer>
            <!-- lien pour contacter Alex -->
            <a href="http://localhost/projet_ue2/formulaires/ME_contacter.php">
                <!-- Bouton nous contacter -->
                <button class="boutonn">Contact me</button>
            </a>
            <a class="cadenas" href="http://localhost/projet_ue2/dashboard/login.php">
                <img src="../image/cadenas.png" alt="bouton de login" width="40">
            </a>
        </footer>
</body>

</html>