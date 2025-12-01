<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Fstyle.css">
    <title>Contact us</title>
</head>

<body>
    <div>
        <a href="http://localhost/projet_ue2/main/index.html">
            <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
        </a>
    </div>

    <h1>Contact us</h1>


    <?php
    // Connexion à la BDD
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        '',
    );
    $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);

    //récupère les contacts
    $sql = "SELECT id, nom, email FROM nous ORDER BY nom";
    $stmt = $pdo->query($sql);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php
    $erreurs = [];
    $succes = false;

    // Traiter le formulaire s'il est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer et nettoyer les données
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $entreprise = trim(string: $_POST['entreprise'] ?? '');
        $contact = $_POST['contact'] ?? '';
        $message = trim($_POST['message'] ?? '');

        // Validation
        if (empty($nom)) {
            $erreurs[] = "Le nom est obligatoire";
        } elseif (strlen($nom) < 2) {
            $erreurs[] = "Le nom est obligatoire";
        }

        if (empty($email)) {
            $erreurs[] = "L'email est obligatoire";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'email n'est pas valide";
        }

        if (empty($entreprise)) {
            $erreurs[] = "Le nom de l'entreprise est obligatoire";
        } elseif (strlen(string: $entreprise) < 1) {
            $erreurs[] = "Le nom de l'entreprise doit être insérer";
        }

        if (empty($contact)) {
            $erreurs[] = "Veuillez sélectionner qui contacter";
        }

        // Si pas d'erreurs, afficher le succès
        if (empty($erreurs)) {
            $succes = true;
        }
    }

    // Afficher les erreurs
    if (!empty($erreurs)) {
        echo '<div class="erreur">';
        echo '<h3>Erreurs :</h3>';
        echo '<ul>';
        foreach ($erreurs as $erreur) {
            echo "<li>$erreur</li>";
        }
        echo '</ul>';
        echo '</div>';
    }

    // Afficher le succès
    if ($succes) {
        echo '<div class="resultat">';
        echo '<h2>✓ Message sent !</h2>';
        echo "<p><strong>Name :</strong> " . htmlspecialchars($nom) . "</p>";
        echo "<p><strong>Email :</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Company :</strong> $entreprise </p>";
        echo "<p><strong>You have sent to :</strong> $contact</p>";
        if (!empty($message)) {
            echo "<p><strong>Message :</strong> " . nl2br(htmlspecialchars($message)) . "</p>";
        }
        echo '</div>';
    }
    ?>

    <form method="POST" action="">
        <label for="nom">Full name * :</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom ?? ''); ?>" required>

        <label for="email">Email * :</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

        <label for="age">Company *:</label>
        <input type="text" id="entreprise" name="entreprise" value="<?php echo htmlspecialchars($entreprise ?? ''); ?>"
            required>
        
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


        <label for="message">Message * :</label>
        <textarea id="message" name="message" rows="4"
            requiredw><?php echo htmlspecialchars($message ?? ''); ?></textarea>

        <button type="submit">Send a message</button>
    </form>

    <p><small>* required field</small></p>

</body>

</html>