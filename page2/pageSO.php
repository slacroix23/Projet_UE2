<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main/style.css">
    <title>Portfolio</title>
</head>

<body>
    <header class="bandeau">
        <div><!-- retourner sur la page d'acceuil -->
            <a href="http://localhost/projet_ue2/main/index.html">
                <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
            </a>    
        </div>
        <h1>Simon Orti's Portfolio</h1><!-- portfolio de Simon O-->
    </header>
    <div class="containeer"><!-- photo du portfolio -->
        <img src="../image/SimonO.jpg" alt="Photo" width="500">
        <div class="info-containeer"><!-- box où il y a les infos de Simon O-->

            <div class="info-box">
                <h3>Name</h3>
                <p>Simon Orti</p>
            </div>

            <div class="info-box">
                <h3>Age</h3>
                <p>18 years old</p>
            </div>

            <div class="info-box">
                <h3>Qualities</h3>
                <p>Rigorous, Visionary, Analytical, Collaborative</p>
            </div>

            <div class="info-box">
                <h3>SKILLS</h3>
                <p>Machine learning, Tech leadership, Model optimization, Product strategy, Speaks 4 languages (French, English, Spanish, Chinese)</p>
            </div>

            <div class="info-box">
                <h3>Experience / Professional interests</h3>
                <p>AI, Applied research, Team management, Model development</p>
            </div>

            <div class="info-box">
                <h3>Passions</h3>
                <p>Handball, video games, culinary arts, music, sports in general, chatting with AI</p>
            </div>
        </div>
    </div>

    <footer>
        © <?php echo date("d/m/Y") ?> - My Portfolio <!-- copyright -->
        <footer>
            <a href="http://localhost/projet_ue2/formulaires/ME_contacter.php"><!-- lien pour contacter Simon O -->
                <button class="boutonn">Contact me</button><!-- bouton pour y acceder -->
            </a>
    </footer>
</body>

</html>