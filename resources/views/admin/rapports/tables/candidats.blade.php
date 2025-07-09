<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Date d'inscription</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($candidats as $candidat)
            <tr>
                <td>{{ $candidat->nom }}</td>
                <td>{{ $candidat->email }}</td>
                <td>{{ $candidat->created_at->format('d/m/Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
