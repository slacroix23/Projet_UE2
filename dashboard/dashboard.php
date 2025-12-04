<?php
// ===== CONNEXION À LA BASE DE DONNÉES =====
$pdo = new PDO(
    "mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4", // hôte et base
    "root",   // utilisateur
    ""        // mot de passe
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gestion des erreurs en exception

// ===== AJOUTER UNE PERSONNE =====
if (isset($_POST['add'])) {
    $sql = "INSERT INTO nous (nom, email, Age, Qualities, Skills, Experience, Passions) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nom'] ?? null,
        $_POST['email'] ?? null,
        $_POST['Age'] ?? null,
        $_POST['Qualities'] ?? null,
        $_POST['Skills'] ?? null,
        $_POST['Experience'] ?? null,
        $_POST['Passions'] ?? null
    ]);
}

// ===== MODIFIER UNE PERSONNE =====
if (isset($_POST['update']) && !empty($_POST['id'])) {
    $sql = "UPDATE nous SET nom=?, email=?, Age=?, Qualities=?, Skills=?, Experience=?, Passions=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nom'] ?? null,
        $_POST['email'] ?? null,
        $_POST['Age'] ?? null,
        $_POST['Qualities'] ?? null,
        $_POST['Skills'] ?? null,
        $_POST['Experience'] ?? null,
        $_POST['Passions'] ?? null,
        $_POST['id'] // ID pour WHERE
    ]);
}

// ===== SUPPRIMER UNE PERSONNE =====
if (isset($_POST['delete']) && !empty($_POST['id'])) {
    $sql = "DELETE FROM nous WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['id']]);
}

// ===== RÉCUPÉRER TOUTES LES PERSONNES =====
$stmt = $pdo->query("SELECT * FROM nous ORDER BY id DESC"); // Tri décroissant par ID
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC); // Tableau associatif
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <!-- Important pour l’affichage responsive sur téléphone -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Lien vers le CSS du dashboard -->
    <link rel="stylesheet" href="dstyle.css">
</head>

<body>
<!-- ===== BARRE DU HAUT ===== -->
<div class="top-bar">
    <a href="http://localhost/projet_ue2/main/index.html" class="back-link">
        <img src="../image/flèche_retour.png" alt="bouton_retour" width="30">
    </a>
    <h1>Dashboard</h1>
</div>

<!-- ===== FORMULAIRE AJOUT ===== -->
<h2>Ajouter une personne</h2>
<form method="POST" class="form-ajout">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="number" name="Age" placeholder="Âge">
    <input type="text" name="Qualities" placeholder="Qualities">
    <input type="text" name="Skills" placeholder="Skills">
    <input type="text" name="Experience" placeholder="Experience">
    <input type="text" name="Passions" placeholder="Passions">
    <button type="submit" name="add">Ajouter</button>
</form>

<!-- ===== LISTE DES PERSONNES ===== -->
<h2>Liste des personnes</h2>

<!-- Wrapper pour rendre le tableau scrollable sur petit écran -->
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

        <!-- Boucle pour afficher chaque personne -->
        <?php foreach ($contacts as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['id']) ?></td>
                <td><?= htmlspecialchars($c['nom']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= htmlspecialchars($c['Age']) ?></td>
                <td><?= htmlspecialchars($c['Qualities']) ?></td>
                <td><?= htmlspecialchars($c['Skills']) ?></td>
                <td><?= htmlspecialchars($c['Experience']) ?></td>
                <td><?= htmlspecialchars($c['Passions']) ?></td>

                <!-- ===== ACTIONS (Modifier / Supprimer) ===== -->
                <td class="actions-cell">

                    <!-- Formulaire de modification inline -->
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <input type="text" name="nom" value="<?= htmlspecialchars($c['nom']) ?>">
                        <input type="email" name="email" value="<?= htmlspecialchars($c['email']) ?>">
                        <input type="number" name="Age" value="<?= htmlspecialchars($c['Age']) ?>">
                        <input type="text" name="Qualities" value="<?= htmlspecialchars($c['Qualities']) ?>">
                        <input type="text" name="Skills" value="<?= htmlspecialchars($c['Skills']) ?>">
                        <input type="text" name="Experience" value="<?= htmlspecialchars($c['Experience']) ?>">
                        <input type="text" name="Passions" value="<?= htmlspecialchars($c['Passions']) ?>">
                        <button type="submit" name="update">Modifier</button>
                    </form>

                    <!-- Formulaire de suppression inline -->
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <button type="submit" name="delete" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>

                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>