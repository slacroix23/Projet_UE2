<?php
// 🔧 Affichage des erreurs (utile en développement)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🟦 1) Démarrer la session (nécessaire pour stocker le token CSRF)
session_start();

// 🟦 Connexion PDO sécurisée
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

// 🟦 Inclusion de la fonction Have I Been Pwned
require_once 'hibp.php';

$erreurs  = [];   // Stockage des erreurs
$messages = [];   // Stockage des messages d'information
$user     = "";   // Sera utilisé pour préremplir la zone "Username"

// 🟦 2) Générer un token CSRF si inexistant
if (empty($_SESSION['csrf_token'])) {
    // random_bytes → source cryptographiquement sûre
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 🟦 Traitement du formulaire POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 🟦 3) Vérification CSRF AVANT tout traitement !
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        // hash_equals évite les attaques par timing
        die("⛔ Requête non autorisée (protection CSRF).");
    }

    // Variables sécurisées
    $user = trim($_POST['user'] ?? '');
    $pwd  = trim($_POST['pwd'] ?? '');

    // 🟦 1) Vérification du mot de passe via HIBP
    try {
        $check = isPwnedPasswordPHP($pwd);

        if ($check['pwned']) {
            // Mot de passe présent dans une fuite → refus immédiat
            $erreurs[] =
                "⚠️ Ce mot de passe a été trouvé dans des fuites de données ({$check['count']} fois). "
                . "Merci d'en choisir un plus sûr.";
        } else {
            // Mot de passe non trouvé → message informatif
            $messages[] = "✅ Ce mot de passe n'apparaît pas dans la base Have I Been Pwned.";
        }
    } catch (Exception $e) {
        // En cas d’erreur API ou autre
        $erreurs[] = "Erreur lors de la vérification du mot de passe : " 
                   . htmlspecialchars($e->getMessage());
    }

    // 🟦 2) Si le mot de passe passe HIBP → validation l'utilisateur
    if (empty($erreurs)) {

        // Récupération de l'utilisateur via requête préparée
        $stmt = $pdo->prepare("SELECT * FROM login WHERE login = :login");
        $stmt->execute(['login' => $user]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($account) {
            // Comparaison du mot de passe avec son hash
            if (password_verify($pwd, $account['password'])) {

                // 🟩 Connexion OK → redirection vers dashboard
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
        <!-- Préremplissage sécurisé -->
        <input type="text" id="user" name="user"
               value="<?php echo htmlspecialchars($user); ?>" required>

        <label for="pwd">Password * :</label>
        <input type="password" id="pwd" name="pwd" required>

        <!-- 🟦 4) Envoi du token CSRF -->
        <input type="hidden" name="csrf_token"
               value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <button type="submit">Connexion</button>

        <!-- Affichage des erreurs -->
        <?php if (!empty($erreurs)): ?>
            <div style="color:red; margin-top:10px;">
                <?php foreach ($erreurs as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Affichage des messages d'information -->
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
