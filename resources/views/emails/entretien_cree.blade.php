<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Entretien planifié</title>
</head>
<body>
    <p>Bonjour {{ $candidat->firstname }},</p>

    <p>Un entretien a été planifié pour vous.</p>

    <p><strong>Détails :</strong></p>
    <ul>
        <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</li>
        <li><strong>Heure :</strong> {{ \Carbon\Carbon::parse($entretien->heure)->format('H:i') }}</li>
        <li><strong>Lieu / Plateforme :</strong> {{ $entretien->lieu ?? 'À préciser' }}</li>
        <li><strong>Type :</strong> {{ $entretien->type }}</li>
    </ul>

    <p>Merci de bien vouloir vous préparer en conséquence.</p>

    <p>Cordialement,<br>La direction</p>
</body>
</html>
