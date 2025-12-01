<?php
// Connexion à la BDD
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(" Erreur de connexion : " . $e->getMessage());
}

$erreurs = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = trim($_POST['user'] ?? '');
    $pwd = trim($_POST['pwd'] ?? '');

    // Requête SQL : chercher l'utilisateur dans la table "login"
    $stmt = $pdo->prepare("SELECT * FROM login WHERE login = :login");
    $stmt->execute(['login' => $user]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($account) {
        // Vérifier le mot de passe (si non hashé)
        if ($pwd === $account['password']) {
            // Identifiants corrects → redirection
            header("Location: admin.php");
            exit;
        } else {
            $erreurs[] = "Mot de passe incorrect.";
        }
    } else {
        $erreurs[] = "Nom d'utilisateur introuvable.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Formulaires/Fstyle.css">
    <title>Login to dashboard</title>
</head>

<body>
    <div>
        <a href="http://localhost/projet_ue2/main/index.html">
            <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
        </a>
    </div>
    <h1>Login to Dashboard</h1>
    <form method="POST" action="">
        <label for="user">Username * :</label>
        <input type="text" id="user" name="user" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>

        <label for="pwd">Password * :</label>
        <input type="password" id="pwd" name="pwd" value="<?php echo htmlspecialchars($password ?? ''); ?>" required>

        <button type="submit">Connexion</button>

        <p><small>* required field</small></p>
</body>

</html>