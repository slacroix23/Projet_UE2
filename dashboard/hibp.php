<?php
// Fonction qui calcule le hash SHA-1 d'une chaîne, et le renvoie en lettres majuscules
function sha1_hex_upper($str){
    return strtoupper(sha1($str));
}

// Fonction qui vérifie si un mot de passe a déjà été compromis (volé) à l’aide de l’API Have I Been Pwned
function isPwnedPasswordPHP($password){
    // On calcule le hash SHA-1 du mot de passe
    $hash = sha1_hex_upper($password);

    // On prend les 5 premiers caractères du hash (préfixe)
    $prefix = substr($hash, 0, 5);

    // On garde le reste du hash (suffixe)
    $suffix = substr($hash, 5);

    // On interroge l’API avec les 5 premiers caractères du hash
    // L’API renverra une liste de suffixes correspondants aux mots de passe connus
    $ch = curl_init("https://api.pwnedpasswords.com/range/$prefix");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // On veut récupérer la réponse sous forme de texte
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Add-Padding: true']); // Ajout d’un en-tête pour plus de confidentialité

    // On exécute la requête
    $resp = curl_exec($ch);

    // Si la requête a échoué, on arrête et on signale une erreur
    if ($resp === false) {
        curl_close($ch);
        throw new Exception('Erreur lors de la requête HIBP');
    }
    curl_close($ch);

    // On parcourt chaque ligne de la réponse
    // Chaque ligne contient un suffixe de hash et un nombre (combien de fois ce mot de passe est apparu)
    foreach (explode("\n", $resp) as $line) {
        $line = trim($line); // On enlève les espaces et retours à la ligne inutiles
        if ($line === '') continue; // On ignore les lignes vides

        // On sépare le suffixe et le nombre d’occurrences
        [$suf, $count] = explode(':', $line);

        // Si le suffixe reçu correspond au nôtre, le mot de passe a été trouvé dans la base
        if (strtoupper($suf) === strtoupper($suffix)) {
            return ['pwned' => true, 'count' => intval($count ?? 0)];
        }
    }

    // Si aucun match n’a été trouvé, le mot de passe n’est pas connu comme compromis
    return ['pwned' => false, 'count' => 0];
}
