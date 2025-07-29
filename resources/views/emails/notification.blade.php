@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Mes notifications</h2>
    <div class="col-auto">
<a href="{{ url()->previous() }}" class="btn btn-sm btn-link">
        <i class="mdi mdi-keyboard-backspace"></i> Retour
    </a>

    </div>
    <div class="list-group">
        @forelse ($notifications as $notification)
            <a href="{{ isset($notification['data']['stage_id']) ? route('directeur.reponses.details', $notification['data']['stage_id']) : '#' }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" tyle="cursor: pointer;">

                <div class="me-3" style="flex: 1 1 auto; min-width: 0;">
                    <h6 class="mb-1 text-truncate" style="max-width: 400px;">
                        {{ $notification['title'] ?? 'Notification' }}
                    </h6>

                </div>
                 <small class="text-muted ms-auto" style="white-space: nowrap;">
                    {{ isset($notification['created_at']) ? \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() : '' }}
                </small>
            </a>
        @empty
            <p class="text-muted">Aucune notification pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection
