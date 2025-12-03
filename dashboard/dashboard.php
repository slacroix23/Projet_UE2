<?php
// -----------------------
//  CONNEXION BDD
// -----------------------
$pdo = new PDO(
    "mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4",
    "root",
    ""
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// -----------------------
//  AJOUTER
// -----------------------
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

// -----------------------
//  MODIFIER
// -----------------------
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
        $_POST['id']
    ]);
}

// -----------------------
//  SUPPRIMER
// -----------------------
if (isset($_POST['delete']) && !empty($_POST['id'])) {
    $sql = "DELETE FROM nous WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['id']]);
}

// -----------------------
//  RECUPERATION DES DONNÉES
// -----------------------
$stmt = $pdo->query("SELECT * FROM nous ORDER BY id DESC");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dstyle.css">
</head>

<body>

    <h1>Dashboard</h1>

    <!-- ------------------- -->
    <!--   FORMULAIRE AJOUT  -->
    <!-- ------------------- -->
    <h2>Ajouter une personne</h2>
    <form method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="number" name="Age" placeholder="Âge">
        <input type="text" name="Qualities" placeholder="Qualities">
        <input type="text" name="Skills" placeholder="Skills">
        <input type="text" name="Experience" placeholder="Experience">
        <input type="text" name="Passions" placeholder="Passions">
        <button type="submit" name="add">Ajouter</button>
    </form>

    <!-- ------------------- -->
    <!--   LISTE DES PERSONNES -->
    <!-- ------------------- -->
    <h2>Liste des personnes</h2>

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

                <td>
                    <!-- FORMULAIRE DE MODIFICATION -->
                    <form method="POST" style="display:inline-block;">
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

                    <!-- FORMULAIRE DE SUPPRESSION -->
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <button type="submit" name="delete" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>
