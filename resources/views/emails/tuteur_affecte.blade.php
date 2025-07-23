<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Affectation de stage</title>
</head>
<body>
    <h2>Bonjour {{ $tuteur->nom }},</h2>

    <p>Vous avez été désigné comme tuteur pour le stage de :</p>
    <ul>
        <li><strong>Nom du stagiaire :</strong> {{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</li>
        <li><strong>Email :</strong> {{ $candidature->candidat->email }}</li>
        <li><strong>Téléphone :</strong> {{ $candidature->candidat->telephone }}</li>
        <li><strong>Type de stage :</strong> {{ $candidature->candidat->type_depot }}</li>
    </ul>

    <p>Merci de bien vouloir prendre contact avec le stagiaire.</p>


    <p>Cordialement,<br>L’équipe RH</p>
</body>
</html>
