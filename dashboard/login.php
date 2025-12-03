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
<html lang="fr"> <!-- Déclaration du document HTML, en français -->

<head>
    <meta charset="UTF-8"> <!-- Encodage des caractères (UTF-8 pour les accents, etc.) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Adaptation aux écrans mobiles -->
    <link rel="stylesheet" href="../Formulaires/Fstyle.css"> <!-- Lien vers la feuille de style externe -->
    <title>Login to dashboard</title> <!-- Titre de la page -->
</head>

<body>
    <!-- Bouton de retour vers la page principale -->
    <div>
        <a href="http://localhost/projet_ue2/main/index.html">
            <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
        </a>
    </div>

    <h1>Login to Dashboard</h1> <!-- Titre principal de la page -->

    <!-- Formulaire de connexion -->
    <form method="POST" action=""> <!-- Envoi des données en POST (vers la même page ici) -->
        <!-- Champ du nom d'utilisateur -->
        <label for="user">Username * :</label>
        <input type="text" id="user" name="user"
               value="<?php echo htmlspecialchars($user ?? ''); ?>" required>
        <!-- Le champ est pré-rempli si une valeur existe, et "required" le rend obligatoire -->

        <!-- Champ du mot de passe -->
        <label for="pwd">Password * :</label>
        <input type="password" id="pwd" name="pwd" required>
        <!-- Le type "password" masque le texte saisi -->

        <button type="submit">Connexion</button> <!-- Bouton pour envoyer le formulaire -->

        <!-- Affichage des erreurs si elles existent -->
        <?php if (!empty($erreurs)): ?>
            <div style="color:red; margin-top:10px;">
                <?php foreach ($erreurs as $err): ?>
                    <p><?php echo $err; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><small>* required field</small></p> <!-- Petit texte d'information -->
    </form>
</body>
</html>
