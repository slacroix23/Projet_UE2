<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dstyle.css">
    <?php
    // Connexion à la BDD (à adapter)
    $pdo = new PDO(
        'mysql:host=localhost;dbname=cyberfolio;charset=utf8mb4',
        'root',
        '',
    );
    $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);

    // On récupère les contacts
    $sql = "SELECT * FROM nous ";
    $sql = "SELECT * FROM login ";
    $stmt = $pdo->query($sql);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
</head>

<body>
    <header class="bandeau">
        <h1>Dashboard</h1>
    </header>
</body>

</html>