<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers le CSS externe -->
    <link rel="stylesheet" href="Fstyle.css">
    <title>Contact us</title>
</head>

<body>
    <!-- Bouton retour -->
    <div>
        <a href="http://localhost/projet_ue2/main/index.html">
            <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
        </a>
    </div>

    <!-- Titre de la page -->
    <h1>Contact us</h1>

    <?php
    // ===== Connexion à la BDD =====
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        '',
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mode erreurs exceptions

    // ===== Récupération de la liste des contacts =====
    $sql = "SELECT id, nom, email FROM nous ORDER BY nom";
    $stmt = $pdo->query($sql);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ===== Initialisation variables =====
    $erreurs = [];    // Tableau pour stocker les erreurs
    $succes = false;  // Booléen pour succès formulaire

    // ===== Traitement du formulaire =====
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Nettoyage des données envoyées
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $entreprise = trim($_POST['entreprise'] ?? '');
        $contact = $_POST['contact'] ?? '';
        $message = trim($_POST['message'] ?? '');

        // ===== Validation des champs =====
        if (empty($nom)) {
            $erreurs[] = "Le nom est obligatoire";
        } elseif (strlen($nom) < 2) {
            $erreurs[] = "Le nom est trop court";
        }

        if (empty($email)) {
            $erreurs[] = "L'email est obligatoire";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'email n'est pas valide";
        }

        if (empty($entreprise)) {
            $erreurs[] = "Le nom de l'entreprise est obligatoire";
        }

        if (empty($contact)) {
            $erreurs[] = "Veuillez sélectionner qui contacter";
        }

        // ===== Si aucune erreur, succès =====
        if (empty($erreurs)) {
            $succes = true;
        }
    }

    // ===== Affichage des erreurs =====
    if (!empty($erreurs)) {
        echo '<div class="erreur">';
        echo '<h3>Erreurs :</h3>';
        echo '<ul>';
        foreach ($erreurs as $erreur) {
            echo "<li>$erreur</li>"; // Affiche chaque erreur dans une liste
        }
        echo '</ul>';
        echo '</div>';
    }

    // ===== Affichage du succès =====
    if ($succes) {
        echo '<div class="resultat">';
        echo '<h2>✓ Message sent !</h2>';
        echo "<p><strong>Name :</strong> " . htmlspecialchars($nom) . "</p>";
        echo "<p><strong>Email :</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Company :</strong> " . htmlspecialchars($entreprise) . "</p>";
        echo "<p><strong>You have sent to :</strong> " . htmlspecialchars($contact) . "</p>";
        if (!empty($message)) {
            echo "<p><strong>Message :</strong> " . nl2br(htmlspecialchars($message)) . "</p>";
        }
        echo '</div>';
    }
    ?>

    <!-- ===== Formulaire de contact ===== -->
    <form method="POST" action="">
        <!-- Champ Nom -->
        <label for="nom">Full name * :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom ?? ''); ?>" required>

        <!-- Champ Email -->
        <label for="email">Email * :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? ''); ?>" required>

        <!-- Champ Entreprise -->
        <label for="entreprise">Company *:</label>
        <input type="text" id="entreprise" name="entreprise" value="<?= htmlspecialchars($entreprise ?? ''); ?>" required>

        <!-- Champ Sélection du contact -->
        <label for="contact">Who ? * :</label>
        <select id="contact" name="contact" required>
            <option value="">-- Select --</option>
            <?php foreach ($contacts as $c): ?>
                <option
                    value="<?= htmlspecialchars($c['id']) ?>"
                    <?= isset($contact) && $contact == $c['id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($c['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Champ Message -->
        <label for="message">Message * :</label>
        <textarea id="message" name="message" rows="4"><?= htmlspecialchars($message ?? ''); ?></textarea>

        <!-- Bouton d'envoi -->
        <button type="submit">Send a message</button>
    </form>

    <!-- Note sur les champs obligatoires -->
    <p><small>* required field</small></p>

</body>
</html>