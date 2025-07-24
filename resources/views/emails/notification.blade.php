@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2>Mes notifications</h2>

    @forelse ($notifications as $notification)
        <div class="alert alert-info">
            <strong>{{ $notification->data['message'] ?? 'Notification' }}</strong><br>
            Stage : {{ $notification->data['sujet'] ?? 'N/A' }}<br>
            <a href="{{ url('/directeur/stages' . ($notification->data['stage_id'] ?? '')) }}">Voir le stage</a>
        </div>
    @empty
        <p>Aucune notification pour le moment.</p>
    @endforelse
</div>
@endsection
