<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la BDD
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

require_once 'hibp.php';

$erreurs = [];
$user    = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = trim($_POST['user'] ?? '');
    $pwd  = trim($_POST['pwd'] ?? '');

    // 1) Vérification HIBP du mot de passe
    try {
        $check = isPwnedPasswordPHP($pwd);
        if ($check['pwned']) {
            $erreurs[] = "⚠️ Ce mot de passe a été trouvé dans des fuites de données ({$check['count']} fois). "
                       . "Merci d'en choisir un plus sûr.";
        }
    } catch (Exception $e) {
        // En cas d'erreur API, tu peux aussi juste logger
        $erreurs[] = "Erreur lors de la vérification du mot de passe : " . htmlspecialchars($e->getMessage());
    }

    // 2) Si pas d'erreur HIBP, on continue la vérification de login
    if (empty($erreurs)) {
        // Requête SQL : chercher l'utilisateur dans la table "login"
        $stmt = $pdo->prepare("SELECT * FROM login WHERE login = :login");
        $stmt->execute(['login' => $user]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account) {
            // Vérifier le mot de passe (non hashé pour l'instant)
            if ($pwd === $account['password']) {
                // Identifiants corrects → redirection
                header("Location: dashboard.php");
                exit;
            } else {
                $erreurs[] = "Mot de passe incorrect.";
            }
        } else {
            $erreurs[] = "Nom d'utilisateur introuvable.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

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
        <input type="text" id="user" name="user"
               value="<?php echo htmlspecialchars($user ?? ''); ?>" required>

        <label for="pwd">Password * :</label>
        <input type="password" id="pwd" name="pwd" required>

        <button type="submit">Connexion</button>

        <?php if (!empty($erreurs)): ?>
            <div style="color:red; margin-top:10px;">
                <?php foreach ($erreurs as $err): ?>
                    <p><?php echo $err; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><small>* required field</small></p>
    </form>
</body>
</html>