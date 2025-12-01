<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to dashboard</title>
    <?php
    // Connexion à la BDD (à adapter)
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        '',
    );
    $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);

    // On récupère les contacts
    $sql = "SELECT login, password, FROM login ORDER BY nom";
    $stmt = $pdo->query($sql);
    $login = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
</head>

<body>
    <div>
        <a href="http://localhost/projet_ue2/main/index.html">
            <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
        </a>
    </div>

    <h1>Login to Dashboard</h1>

    <?php
        $erreurs = [];
        $succes = false;
    // Traiter le formulaire s'il est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer et nettoyer les données
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ??'');        
    }
    ?>
</body>
</html>