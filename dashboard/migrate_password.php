<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion BDD
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

echo "<h2>Mise à jour des mots de passe en hash...</h2>";

// Récupérer les comptes existants
$stmt = $pdo->query("SELECT id, login, password FROM login");
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($accounts as $account) {

    $id       = $account['id'];
    $login    = $account['login'];
    $password = $account['password'];

    // Vérifier si déjà hashé
    if (str_starts_with($password, '$2y$') || str_starts_with($password, '$argon2')) {
        echo "⏩ $login : déjà hashé, ignoré.<br>";
        continue;
    }

    // Hasher l'ancien mot de passe en clair
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Mise à jour BDD
    $update = $pdo->prepare("UPDATE login SET password = :hash WHERE id = :id");
    $update->execute([
        'hash' => $hash,
        'id'   => $id
    ]);

    echo "✔️ $login : mot de passe hashé et mis à jour.<br>";
}

echo "<br><strong>Mise à jour terminée !</strong><br>";
echo "<strong>⚠️ Pense à supprimer ce fichier !</strong>";
?>