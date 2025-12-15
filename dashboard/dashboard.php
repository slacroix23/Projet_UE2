<?php
/** ============================================================
 *  INITIALISATION SÉCURISÉE
 * ============================================================ */

// Démarrage de session sécurisée
session_start();

// Génération d’un token CSRF aléatoire si inexistant
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Connexion sécurisée à la base de données via PDO
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4",
        "root",
        ""
    );
    // Mode exception pour gérer les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Masque les détails techniques pour la sécurité
    die("Erreur interne."); 
}


/** ============================================================
 *  FONCTIONS DE SÉCURITÉ
 * ============================================================ */

/**
 * Vérifie que le token CSRF envoyé correspond à celui de session
 */
function valid_csrf() {
    return isset($_POST['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

/**
 * Nettoie une chaîne pour éviter XSS
 */
function clean($str) {
    return trim(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}

/**
 * Valide l'adresse email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


/** ============================================================
 *  TRAITEMENT DES FORMULAIRES
 * ============================================================ */

// ===== AJOUT D'UNE PERSONNE =====
if (isset($_POST['add'])) {

    // Vérification CSRF
    if (!valid_csrf()) {
        die("CSRF detection.");
    }

    // Nettoyage des champs
    $nom = clean($_POST['nom']);
    $email = clean($_POST['email']);

    // Validation email
    if (!validate_email($email)) {
        die("Email invalide.");
    }

    // Préparation et exécution de l'insertion
    $stmt = $pdo->prepare("
        INSERT INTO nous (nom, email, Age, Qualities, Skills, Experience, Passions)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $nom,
        $email,
        clean($_POST['Age'] ?? ''),
        clean($_POST['Qualities'] ?? ''),
        clean($_POST['Skills'] ?? ''),
        clean($_POST['Experience'] ?? ''),
        clean($_POST['Passions'] ?? '')
    ]);
}


// ===== MODIFICATION D'UNE PERSONNE =====
if (isset($_POST['update']) && !empty($_POST['id'])) {

    // Vérification CSRF
    if (!valid_csrf()) {
        die("CSRF detection.");
    }

    // Nettoyage et protection contre injection
    $id = intval($_POST['id']);
    $nom = clean($_POST['nom']);
    $email = clean($_POST['email']);

    // Validation email
    if (!validate_email($email)) {
        die("Email invalide.");
    }

    // Mise à jour sécurisée
    $stmt = $pdo->prepare("
        UPDATE nous SET nom=?, email=?, Age=?, Qualities=?, Skills=?, Experience=?, Passions=?
        WHERE id=?
    ");
    $stmt->execute([
        $nom,
        $email,
        clean($_POST['Age']),
        clean($_POST['Qualities']),
        clean($_POST['Skills']),
        clean($_POST['Experience']),
        clean($_POST['Passions']),
        $id
    ]);
}


// ===== SUPPRESSION D'UNE PERSONNE =====
if (isset($_POST['delete']) && !empty($_POST['id'])) {

    // Vérification CSRF
    if (!valid_csrf()) {
        die("CSRF detection.");
    }

    // Protection contre injection
    $id = intval($_POST['id']);

    // Suppression sécurisée
    $stmt = $pdo->prepare("DELETE FROM nous WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
}


// ===== LECTURE DES ENREGISTREMENTS =====
$stmt = $pdo->query("SELECT * FROM nous ORDER BY id DESC");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Adaptation mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Lien CSS -->
    <link rel="stylesheet" href="dstyle.css">
</head>

<body>

<!-- TOP BAR avec bouton retour -->
<div class="top-bar">
    <a href="http://localhost/projet_ue2/main/index.html" class="back-link">
        <img src="../image/flèche_retour.png" alt="Retour" width="30">
    </a>
    <h1>Dashboard</h1>
</div>

<!-- FORMULAIRE D'AJOUT -->
<h2>Ajouter une personne</h2>
<form method="POST" class="form-ajout">
    <!-- Token CSRF -->
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

    <!-- Champs -->
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="number" name="Age" placeholder="Âge">
    <input type="text" name="Qualities" placeholder="Qualities">
    <input type="text" name="Skills" placeholder="Skills">
    <input type="text" name="Experience" placeholder="Experience">
    <input type="text" name="Passions" placeholder="Passions">

    <button type="submit" name="add">Ajouter</button>
</form>

<!-- LISTE DES PERSONNES -->
<h2>Liste des personnes</h2>

<div class="table-wrapper">
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Age</th>
            <th>Qualities</th>
            <th>Skills</th>
            <th>Experience</th>
            <th>Passions</th>
            <th>Actions</th>
        </tr>

        <!-- Boucle d'affichage -->
        <?php foreach ($contacts as $c): ?>
            <tr>
                <td><?= clean($c['id']) ?></td>
                <td><?= clean($c['nom']) ?></td>
                <td><?= clean($c['email']) ?></td>
                <td><?= clean($c['Age']) ?></td>
                <td><?= clean($c['Qualities']) ?></td>
                <td><?= clean($c['Skills']) ?></td>
                <td><?= clean($c['Experience']) ?></td>
                <td><?= clean($c['Passions']) ?></td>

                <td class="actions-cell">

                    <!-- FORMULAIRE DE MODIFICATION -->
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="id" value="<?= clean($c['id']) ?>">

                        <input type="text" name="nom" value="<?= clean($c['nom']) ?>">
                        <input type="email" name="email" value="<?= clean($c['email']) ?>">
                        <input type="number" name="Age" value="<?= clean($c['Age']) ?>">
                        <input type="text" name="Qualities" value="<?= clean($c['Qualities']) ?>">
                        <input type="text" name="Skills" value="<?= clean($c['Skills']) ?>">
                        <input type="text" name="Experience" value="<?= clean($c['Experience']) ?>">
                        <input type="text" name="Passions" value="<?= clean($c['Passions']) ?>">

                        <button type="submit" name="update">Modifier</button>
                    </form>

                    <!-- FORMULAIRE DE SUPPRESSION -->
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                        <input type="hidden" name="id" value="<?= clean($c['id']) ?>">

                        <button type="submit" name="delete"
                                onclick="return confirm('Supprimer cette entrée ?')">
                                Supprimer
                        </button>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>

    </table>
</div>

</body>
</html>
