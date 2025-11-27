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
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


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
        <h1>Simon Lacroix's Portfolio</h1>
    </header>

    <!-- Conteneur principal de la photo et des infos -->
    <div class="containeer">
        <img src="../image/SimonL.jpg" alt="Photo" width="500">
        <!-- Infos de Simon S-->
        <div class="info-containeer">

            <div class="info-box">
                <h3>Name</h3>
                <p>Simon Lacroix</p>
            </div>

            <div class="info-box">
                <h3>Age</h3>
                <p>18 years old</p>
            </div>

            <div class="info-box">
                <h3>Qualities</h3>
                <p>Visionary, Strategic, Ambitious, Solution-oriented</p>
            </div>

            <div class="info-box">
                <h3>SKILLS</h3>
                <p>Leadership, Strategic decision-making, Fundraising, Market analysis</p>
            </div>

            <div class="info-box">
                <h3>Experience / Professional interests</h3>
                <p>Tech entrepreneurship, Business management, Startup investment, AI innovation</p>
            </div>

            <div class="info-box">
                <h3>Passions</h3>
                <p>Fashion, gastronomy, Japanese culture, video games, music</p>
            </div>
        </div>
    </div>

    <footer>
        <!-- Copyright-->
        © <?php echo date("d/m/Y") ?> - My Portfolio
        <footer>
            <!-- Bouton pour nous contacter -->
            <a href="http://localhost/projet_ue2/formulaires/ME_contacter.php">
                <button class="boutonn">Contact me</button>
            </a>
        </footer>
</body>

</html>