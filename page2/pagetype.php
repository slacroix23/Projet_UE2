<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
</head>

<body>
    <header>
        <h1>Portfolio d'Alexandre</h1>
    </header>

    <footer>
        © <?= date("d/m/Y") ?> - Mon Portfolio
    </footer>
    <?php
    $compteur = "visites.txt";

    if (!file_exists($compteur)) {
        file_put_contents($compteur, 0);
    }

    $visites = file_get_contents($compteur);
    $visites++;
    file_put_contents($compteur, $visites);

    echo "Nombre de visites : $visites";
    ?>
</body>

</html>