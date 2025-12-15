<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers le CSS principal -->
    <link rel="stylesheet" href="../main/style.css">
    <title>Portfolio</title>

    <?php
    // ===== Connexion à la base de données =====
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
            'root',
            '' // mot de passe vide en local
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Récupération des données de la personne avec id=3
        $stmt = $pdo->query('SELECT * FROM nous WHERE id=3');
        $personne = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur de connexion:" . $e->getMessage());
    }

    ?>
</head>

<body>
    <!-- Bandeau d'en-tête -->
    <header class="bandeau">
        <!-- Bouton de retour à la page d'accueil-->
        <div>
            <a href="http://localhost/projet_ue2/main/index.html">
                <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
            </a>
        </div>
        <!-- Titre du portfolio avec le nom de la personne -->
        <h1><?= $personne["nom"] ?>'s Portfolio</h1>
    </header>

    <!-- Section principale : photo + infos -->
    <div class="containeer">
        <!-- Photo de Simon L -->
        <img src="../image/SimonL.jpg" alt="Photo" width="500">

        <!-- Chaque bloc info est une carte avec titre et valeur -->
        <div class="info-containeer">

            <!-- Bloc info : Nom -->
            <div class="info-box">
                <h3>Name</h3>
                <p><?= $personne["nom"] ?></p>
            </div>
            <!-- Bloc info : Âge -->
            <div class="info-box">
                <h3>Age</h3>
                <p><?= $personne["Age"] ?> years old</p>
            </div>

            <!-- Bloc info : Qualités -->
            <div class="info-box">
                <h3>Qualities</h3>
                <p><?= $personne["Qualities"] ?></p>
            </div>

            <!-- Bloc info : Compétences -->
            <div class="info-box">
                <h3>SKILLS</h3>
                <p><?= $personne["Skills"] ?></p>
            </div>

            <!-- Bloc info : Expériences / Intérêts professionnels -->
            <div class="info-box">
                <h3>Experience / Professional interests</h3>
                <p><?= $personne["Experience"] ?></p>
            </div>

            <!-- Bloc info : Passions -->
            <div class="info-box">
                <h3>Passions</h3>
                <p><?= $personne["Passions"] ?></p>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer>
        <!-- Copyright dynamique -->
        © <?php echo date("d/m/Y") ?> - My Portfolio
        </footer>
        <footer>
            <!-- Bouton pour contacter la personne-->
            <a href="http://localhost/projet_ue2/formulaires/ME_contacter.php?id=<?= $personne['id'] ?>">
                <!-- Bouton “Contact me” -->
                <button class="boutonn">Contact me</button>
            </a>
        </footer>
</body>

</html>