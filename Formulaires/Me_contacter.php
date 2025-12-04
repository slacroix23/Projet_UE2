<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers le CSS externe -->
    <link rel="stylesheet" href="Fstyle.css">
    <title>Contact me</title>
</head>

<body>

<?php
// ===== Connexion à la base de données =====
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mode erreurs exceptions
} catch (PDOException $e) {
    die("Erreur de connexion:" . $e->getMessage());
}

// ===== Récupération de la personne concernée =====
// Si formulaire envoyé, on prend l'id depuis POST, sinon depuis GET (?id=2)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_personne = isset($_POST['id_personne']) ? (int) $_POST['id_personne'] : 0;
} else {
    $id_personne = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

// Préparation et exécution de la requête pour récupérer la personne
$stmt = $pdo->prepare("SELECT * FROM nous WHERE id = :id");
$stmt->execute(['id' => $id_personne]);
$personne = $stmt->fetch(PDO::FETCH_ASSOC);

// Si aucune personne trouvée → arrêter le script
if (!$personne) {
    die("Personne introuvable.");
}

// ===== Variables pour le formulaire =====
$erreurs = [];   // Tableau pour stocker les erreurs
$succes = false; // Booléen pour savoir si le message a été envoyé
$nom = $email = $entreprise = $message = "";

// ===== Traitement du formulaire =====
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $entreprise = trim($_POST['entreprise'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation des champs
    if (empty($nom) || strlen($nom) < 2) {
        $erreurs[] = "Name is required."; // Erreur si nom vide ou trop court
    }

    if (empty($email)) {
        $erreurs[] = "Email is required."; // Erreur si email vide
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Email is not valid."; // Erreur si email invalide
    }

    if (empty($entreprise)) {
        $erreurs[] = "Company name is required."; // Erreur si entreprise vide
    }

    if (empty($message)) {
        $erreurs[] = "Message is required."; // Erreur si message vide
    }

    // Si pas d'erreurs → insertion dans la base de données
    if (empty($erreurs)) {
        $sql = "INSERT INTO messages_contact (id_personne, nom, email, entreprise, message)
                VALUES (:id_personne, :nom, :email, :entreprise, :message)";
        $stmt_insert = $pdo->prepare($sql);

        $stmt_insert->execute([
            'id_personne' => $personne['id'],  // ID de la personne destinataire
            'nom'         => $nom,             // Nom de l'expéditeur
            'email'       => $email,           // Email de l'expéditeur
            'entreprise'  => $entreprise,      // Entreprise de l'expéditeur
            'message'     => $message,         // Message envoyé
        ]);

        $succes = true; // Indiquer que le message a été envoyé
    }
}
?>

<!-- Bouton retour vers l'index -->
<div>
    <a href="http://localhost/projet_ue2/main/index.html">
        <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
    </a>
</div>

<!-- Titre dynamique selon la personne sélectionnée -->
<h1>Contact <?= htmlspecialchars($personne['nom']) ?></h1>

<?php
// ===== Affichage des erreurs =====
if (!empty($erreurs)) {
    echo '<div class="erreur">';
    echo '<h3>Errors :</h3>';
    echo '<ul>';
    foreach ($erreurs as $erreur) {
        echo "<li>$erreur</li>"; // Liste des erreurs
    }
    echo '</ul>';
    echo '</div>';
}

// ===== Affichage du succès =====
if ($succes) {
    echo '<div class="resultat">';
    echo '<h2>✓ Message sent successfully!</h2>';

    // Destinataire du message
    echo "<p><strong>Message sent to :</strong> " 
        . htmlspecialchars($personne['nom']) 
        . " (" . htmlspecialchars($personne['email']) . ")</p>";

    // Expéditeur
    echo "<p><strong>From :</strong> " 
        . htmlspecialchars($nom) 
        . " (" . htmlspecialchars($email) . ")</p>";

    // Entreprise de l'expéditeur
    echo "<p><strong>Company :</strong> " 
        . htmlspecialchars($entreprise) . "</p>";

    // Message envoyé
    echo "<p><strong>Message :</strong><br>" 
        . nl2br(htmlspecialchars($message)) . "</p>";

    echo '</div>';
}
?>

<!-- ===== Formulaire de contact ===== -->
<form method="POST" action="">
    <!-- ID caché de la personne pour le POST -->
    <input type="hidden" name="id_personne" value="<?= htmlspecialchars($personne['id']) ?>">

    <label for="nom">Full name * :</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom); ?>" required>

    <label for="email">Email * :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

    <label for="entreprise">Company * :</label>
    <input type="text" id="entreprise" name="entreprise" value="<?= htmlspecialchars($entreprise); ?>" required>

    <label for="message">Message * :</label>
    <textarea id="message" name="message" rows="4" required><?= htmlspecialchars($message); ?></textarea>

    <!-- Bouton d'envoi -->
    <button type="submit">Send a message</button>
</form>

<!-- Note pour les champs obligatoires -->
<p><small>* required field</small></p>

</body>
</html>

