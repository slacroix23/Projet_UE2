<?php
/***********************
 *  CONFIGURATIONS GLOBALES
 ***********************/

// Affichage des erreurs pour debug (mettre 0 en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Paramètres sécurisés pour les cookies de session
session_set_cookie_params([
    'httponly' => true,  // Protège contre le vol via JS
    'secure' => true,   //  À mettre true en production HTTPS
    'samesite' => 'Strict' // Limite le CSRF
]);

// Démarrage de la session PHP
session_start();

// Protection HTTPS (commentée pour le dev local)
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    // die("Connexion non sécurisée. Activez HTTPS.");
}

// Connexion à la base de données MySQL via PDO
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur serveur."); // Masque les détails de la BDD
}

// Inclusion de la fonction HIBP (HaveIBeenPwned)
require_once 'hibp.php';


/***********************
 *  VARIABLES INIT
 ***********************/
$erreurs  = []; // tableau pour stocker les erreurs
$messages = []; // tableau pour messages informatifs
$user     = ""; // valeur du champ utilisateur


/***********************
 *  CSRF TOKEN (anti CSRF)
 ***********************/
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // token aléatoire
}


/***********************
 *  RATE LIMIT (anti brute-force)
 ***********************/
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Limite les tentatives à 5
if ($_SESSION['login_attempts'] >= 5) {
    die("⛔ Trop de tentatives. Réessaye dans quelques minutes.");
}


/***********************
 *  TRAITEMENT FORMULAIRE
 ***********************/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*********** CSRF ***********/
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("⛔ Requête non autorisée (CSRF).");
    }

    /*********** NETTOYAGE DES CHAMPS ***********/
    $user = trim($_POST['user'] ?? '');
    $pwd  = trim($_POST['pwd'] ?? '');

    /*********** VALIDATION DES VALEURS ***********/
    if (strlen($user) > 50) {
        $erreurs[] = "Nom d'utilisateur invalide.";
    }
    if (strlen($pwd) > 200) {
        $erreurs[] = "Mot de passe invalide.";
    }

    // Si pas d'erreurs, continuer
    if (empty($erreurs)) {

        /*********** VÉRIFIER UTILISATEUR DANS LA BDD ***********/
        $stmt = $pdo->prepare("SELECT * FROM login WHERE login = :login");
        $stmt->execute(['login' => $user]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        // Réponse générique pour éviter l’énumération d’utilisateurs
        if (!$account) {
            $_SESSION['login_attempts']++;
            $erreurs[] = "Identifiants invalides.";
        } else {

            /*********** Vérifier le mot de passe ***********/
            if (!password_verify($pwd, $account['password'])) {
                $_SESSION['login_attempts']++;
                $erreurs[] = "Identifiants invalides.";
            } else {

                /*********** Vérification HIBP (mot de passe compromis) ***********/
                try {
                    $check = isPwnedPasswordPHP($pwd);

                    if ($check['pwned']) {
                        $erreurs[] =
                            "⚠️ Ce mot de passe apparaît dans des fuites de données ({$check['count']} fois). "
                            . "Merci de le changer.";
                    }
                } catch (Exception $e) {
                    // Message général en cas d'erreur, pas d’info sensible
                    $messages[] = "Impossible de vérifier la sécurité du mot de passe.";
                }

                // Si tout est OK → login réussi
                if (empty($erreurs)) {

                    /*********** LOGIN RÉUSSI ***********/
                    $_SESSION['logged'] = true;
                    $_SESSION['user']   = $account['login'];
                    session_regenerate_id(true); // protection session fixation

                    // Réinitialiser les tentatives
                    $_SESSION['login_attempts'] = 0;

                    // Redirection vers le dashboard
                    header("Location: dashboard.php");
                    exit;
                }
            }
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
    <!-- Bouton retour vers la page principale -->
    <div>
        <a href="http://localhost/projet_ue2/main/index.html">
            <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
        </a>
    </div>

    <h1>Login to Dashboard</h1>

    <!-- Formulaire de connexion -->
    <form method="POST" action="">
        <label for="user">Username * :</label>
        <input
            type="text"
            id="user"
            name="user"
            value="<?= htmlspecialchars($user) ?>"
            required
        >

        <label for="pwd">Password * :</label>
        <input type="password" id="pwd" name="pwd" required>

        <!-- TOKEN CSRF -->
        <input type="hidden"
               name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <button type="submit">Connexion</button>

        <!-- AFFICHAGE DES ERREURS -->
        <?php if (!empty($erreurs)): ?>
            <div style="color:red; margin-top:10px;">
                <?php foreach ($erreurs as $err): ?>
                    <p><?= htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- AFFICHAGE DES MESSAGES INFO -->
        <?php if (!empty($messages)): ?>
            <div style="color:green; margin-top:10px;">
                <?php foreach ($messages as $m): ?>
                    <p><?= htmlspecialchars($m) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><small>* required field</small></p>
    </form>
</body>
</html>
