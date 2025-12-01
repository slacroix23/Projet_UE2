<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to dashboard</title>
    <?php
    // Connexion à la BDD (à adapter)
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        '',
    );
    $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);

    // On récupère les contacts
    $sql = "SELECT login, password, FROM login ORDER BY nom";
    $stmt = $pdo->query($sql);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
</head>
<body>
    
</body>
</html>