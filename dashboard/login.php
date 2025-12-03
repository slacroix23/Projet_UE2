<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// On inclut HIBP
require_once 'hibp.php';

$erreurs  = [];   // tableau d'erreurs
$messages = [];   // messages d'info (ex : mot de passe OK HIBP)
$user     = "";   // pour préremplir le champ

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = trim($_POST['user'] ?? '');
    $pwd  = trim($_POST['pwd'] ?? '');

    // 1) Vérification HIBP du mot de passe
    try {
        $check = isPwnedPasswordPHP($pwd);

        if ($check['pwned']) {
            // Mot de passe trouvé dans des fuites → on bloque
            $erreurs[] = "⚠️ Ce mot de passe a été trouvé dans des fuites de données ({$check['count']} fois). "
                       . "Merci d'en choisir un plus sûr.";
        } else {
            // Mot de passe pas trouvé dans HIBP → simple message d'info
            $messages[] = "✅ Ce mot de passe n'apparaît pas dans la base Have I Been Pwned.";
        }
    } catch (Exception $e) {
        $erreurs[] = "Erreur lors de la vérification du mot de passe : " . htmlspecialchars($e->getMessage());
    }

    // 2) Si pas d'erreur HIBP, on continue la vérif login / mot de passe
    if (empty($erreurs)) {
        // Récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM login WHERE login = :login");
        $stmt->execute(['login' => $user]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account) {
            // Vérifier le mot de passe hashé
            if (password_verify($pwd, $account['password'])) {
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
               value="<?php echo htmlspecialchars($user); ?>" required>

        <label for="pwd">Password * :</label>
        <input type="password" id="pwd" name="pwd" required>

        <button type="submit">Connexion</button>

        <!-- Affichage des erreurs -->
        <?php if (!empty($erreurs)): ?>
            <div style="color:red; margin-top:10px;">
                <?php foreach ($erreurs as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Affichage des messages d'info (mot de passe OK HIBP, etc.) -->
        <?php if (!empty($messages)): ?>
            <div style="color:green; margin-top:10px;">
                <?php foreach ($messages as $msg): ?>
                    <p><?php echo htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><small>* required field</small></p>
    </form>
</body>
</html>
