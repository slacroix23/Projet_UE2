<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Formulaires/Fstyle.css">
    <title>Login to dashboard</title>
</head>

<body>
    <?php
    // Connexion à la BDD
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $erreurs = [];
    $succes = false;

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Récupération des valeurs envoyées
        $user = trim($_POST['user'] ?? '');
        $pwd = trim($_POST['pwd'] ?? '');

        // Vérification SQL : requête préparée
        $stmt = $pdo->prepare("SELECT * FROM login WHERE login = :login");
        $stmt->execute(['login' => $user]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account) {
            // Si tes mots de passe ne sont PAS hashés :
            if ($pwd === $account['password']) {
                $succes = true;
                header("dashboard.php");
                exit;
            } else {
                $erreurs[] = "Mot de passe incorrect.";
            }

            // à mettre si on hash le mdp :
            // if (password_verify($pwd, $account['password'])) { ... }
        } else {
            $erreurs[] = "Nom d'utilisateur introuvable.";
        }
    }
    ?>

    <div>
        <a href="http://localhost/projet_ue2/main/index.html">
            <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
        </a>
    </div>
    <?php
    $erreurs = [];
    $succes = false;
    // Traiter le formulaire s'il est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer et nettoyer les données
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
    }
    ?>

    <h1>Login to Dashboard</h1>


    <form method="POST" action="">
        <label for="user">Username * :</label>
        <input type="text" id="user" name="username" value="<?php echo htmlspecialchars($userrname ?? ''); ?>" required>

        <label for="pwd">Password * :</label>
        <input type="text" id="pwd" name="pwd" value="<?php echo htmlspecialchars($password ?? ''); ?>" required>

        <button type="submit">Connexion</button>

        <p><small>* required field</small></p>
</body>

</html>