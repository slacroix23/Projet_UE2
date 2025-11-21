<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <title>Me contacter</title>
</head>

<body>
    <h1>Me contacter</h1>

    <?php
    $erreurs = [];
    $succes = false;

    // Traiter le formulaire s'il est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer et nettoyer les données
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $entreprise = trim(string: $_POST['entreprise'] ?? '');
        $contact = $_POST['Qui contacter ?'] ?? '';
        $message = trim($_POST['message'] ?? '');

        // Validation
        if (empty($nom)) {
            $erreurs[] = "Le nom est obligatoire";
        } elseif (strlen($nom) < 2) {
            $erreurs[] = "Le nom ";
        }

        if (empty($email)) {
            $erreurs[] = "L'email est obligatoire";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'email n'est pas valide";
        }

        if (empty($entreprise)) {
            $erreurs[] = "Le nom de l'entreprise est obligatoire";
        } elseif (strlen(string: $entreprise) < 0) {
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
        echo "<p><strong>Who would you like to contact ? :</strong> $contact</p>";
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
        <input type="number" id="entreprise" name="entreprise"
            value="<?php echo htmlspecialchars($entreprise ?? ''); ?>" required>

        <label for="contact">Pays * :</label>
        <select id="contact" name="contact" required>
            <option value="">-- Sélectionnez --</option>
            <option value="Simon O" <?php echo (isset($contact) && $contact == 'Simon O') ? 'selected' : ''; ?>>Simon O
            </option>
            <option value="Alexandre" <?php echo (isset($contact) && $contact == 'Alexandre') ? 'selected' : ''; ?>>
                Alexandre
            <option value="Simon L" <?php echo (isset($contact) && $contact == 'Simon L') ? 'selected' : ''; ?>>Simon L
            </option>
        </select>


        <label for="message">Message * :</label>
        <textarea id="message" name="message" rows="4"
            requiredw><?php echo htmlspecialchars($message ?? ''); ?></textarea>

        <button type="submit">Envoyer le message</button>
    </form>

    <p><small>* Champs obligatoires</small></p>

</body>

</html>