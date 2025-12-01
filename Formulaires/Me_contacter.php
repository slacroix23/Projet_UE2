<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Fstyle.css">
    <title>Contact me</title>
</head>

<body>
<?php
// ===== Connexion BDD =====
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion:" . $e->getMessage());
}

// ===== Récupération de la personne concernée =====

// Si on est en POST (formulaire envoyé), on récupère l'id depuis un champ hidden.
// Sinon (1er affichage), on le récupère depuis l'URL (?id=2, etc.)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_personne = isset($_POST['id_personne']) ? (int) $_POST['id_personne'] : 0;
} else {
    $id_personne = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

$stmt = $pdo->prepare("SELECT * FROM nous WHERE id = :id");
$stmt->execute(['id' => $id_personne]);
$personne = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$personne) {
    die("Personne introuvable.");
}

// ===== Variables pour le formulaire =====
$erreurs = [];
$succes = false;
$nom = $email = $entreprise = $message = "";

// ===== Traitement du formulaire =====
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et nettoyer les données
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $entreprise = trim($_POST['entreprise'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($nom) || strlen($nom) < 2) {
        $erreurs[] = "Name is required.";
    }

    if (empty($email)) {
        $erreurs[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Email is not valid.";
    }

    if (empty($entreprise)) {
        $erreurs[] = "Company name is required.";
    }

    if (empty($message)) {
        $erreurs[] = "Message is required.";
    }

    // Si pas d'erreurs : on ENREGISTRE dans la base
    if (empty($erreurs)) {
        $sql = "INSERT INTO messages_contact (id_personne, nom, email, entreprise, message)
                VALUES (:id_personne, :nom, :email, :entreprise, :message)";
        $stmt_insert = $pdo->prepare($sql);

        $stmt_insert->execute([
            'id_personne' => $personne['id'],  // celui du portfolio
            'nom'         => $nom,
            'email'       => $email,
            'entreprise'  => $entreprise,
            'message'     => $message,
        ]);

        $succes = true;

        // Option : vider les champs du formulaire après succès
        $nom = $email = $entreprise = $message = "";
    }
}

?>

<div>
    <a href="http://localhost/projet_ue2/main/index.html">
        <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
    </a>
</div>

<h1>Contact <?= htmlspecialchars($personne['nom']) ?></h1>

<?php
// Afficher les erreurs
if (!empty($erreurs)) {
    echo '<div class="erreur">';
    echo '<h3>Errors :</h3>';
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
    echo "<p><strong>To :</strong> " . htmlspecialchars($personne['nom']) . " (" . htmlspecialchars($personne['email']) . ")</p>";
    echo '</div>';
}
?>

<form method="POST" action="">
    <!-- ID de la personne pour le POST -->
    <input type="hidden" name="id_personne" value="<?= htmlspecialchars($personne['id']) ?>">

    <label for="nom">Full name * :</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom); ?>" required>

    <label for="email">Email * :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

    <label for="entreprise">Company * :</label>
    <input type="text" id="entreprise" name="entreprise" value="<?= htmlspecialchars($entreprise); ?>" required>

    <label for="message">Message * :</label>
    <textarea id="message" name="message" rows="4" required><?= htmlspecialchars($message); ?></textarea>

    <button type="submit">Send a message</button>
</form>

<p><small>* required field</small></p>

</body>
</html>
