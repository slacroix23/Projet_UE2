<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
</head>

<body>
    <header>
        <h1>Portfolio d'Alexandre</h1>
    </header>

    <main>
        <form method="post">
            <label>Email :</label><br>
            <input type="email" name="email" required><br><br>

            <label>Message :</label><br>
            <textarea name="message" rows="5" required></textarea><br><br>

            <button type="submit" name="envoyer">Envoyer</button>
        </form>


        <?php

        if (isset($_POST['envoyer'])) {
            $to = "abertrand@guardiaschool.fr";
            $subject = "Message depuis le portfolio";
            $message = $_POST['message'];
            $headers = "From: " . $_POST['email'];

            if (mail($to, $subject, $message, $headers)) {
                echo "<p>Merci, votre message a été envoyé !</p>";
            } else {
                echo "<p>Erreur lors de l'envoi.</p>";
            }
        }

        ?>

</main>
</body>

</html>
