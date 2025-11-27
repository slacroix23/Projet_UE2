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
    <header class="bandeau">
        <div> <!-- retourner sur la page d'acceuil -->
            <a href="http://localhost/projet_ue2/main/index.html">
                <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
            </a>
        </div>
        <h1><?= $personne["nom"] ?>'s Portfolio</h1><!-- portfolio d'Alex -->
    </header>



    <div class="containeer"><!-- photo du portfolio -->
        <img src="../image/alex.jpg" alt="Photo" width="500">
        <div class="info-containeer"><!-- box où il y a les infos d'alex-->

            <div class="info-box">
                <h3>Name</h3>
                <p><?=$personne["nom"]?></p>
            </div>

            <div class="info-box">
                <h3>Age</h3>
                <p><?= $personne["Age"]?> years old</p>
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
        © <?php echo date("d/m/Y") ?> - My Portfolio <!-- copyright -->
        <footer>
            <a href="http://localhost/projet_ue2/formulaires/ME_contacter.php"><!-- lien pour contacter Alex -->
                <button class="boutonn">Contact me</button><!-- bouton pour y acceder -->
            </a>
        </footer>
</body>

</html>