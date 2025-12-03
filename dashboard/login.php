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

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = trim($_POST['user'] ?? '');
    $pwd  = trim($_POST['pwd'] ?? '');

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
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Nom d'utilisateur introuvable.";
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
