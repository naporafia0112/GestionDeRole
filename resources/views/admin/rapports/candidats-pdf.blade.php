<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Candidats</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #999;
            padding: 4px;
            text-align: left;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>
    <h2>Liste complète des candidats</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénoms</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Type dépôt</th>
                <th>Date création</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidats as $candidat)
            <tr>
                <td>{{ $candidat->id }}</td>
                <td>{{ $candidat->nom }}</td>
                <td>{{ $candidat->prenoms }}</td>
                <td>{{ $candidat->email }}</td>
                <td>{{ $candidat->telephone }}</td>
                <td>{{ $candidat->type_depot }}</td>
                <td>{{ $candidat->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
