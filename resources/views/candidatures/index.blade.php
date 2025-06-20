@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Liste des candidatures</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Candidat</th>
                <th>Offre</th>
                <th>Statut</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidatures as $candidature)
                <tr>
                    <td>{{ $candidature->id }}</td>
                    <td>{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</td>
                    <td>{{ $candidature->offre->titre ?? '-' }}</td>
                    <td>{{ $candidature->statut }}</td>
                    <td>{{ $candidature->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-info btn-sm">Voir</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $candidatures->links() }}
</div>
@endsection
