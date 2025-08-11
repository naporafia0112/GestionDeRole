<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4 p-md-5">
        <div class="preview-header mb-4 pb-3 border-bottom">
            <h4 class="text-muted small">Titre : <h3 class="fw-bold text-primary mb-1">{{ $formulaire->titre }}</h3></h4>
        </div>

        @foreach ($formulaire->champs as $champ)
            <div class="mb-4">
                <label class="form-label fw-bold text-secondary mb-2">{{ $champ['label'] }}</label>
                @switch($champ['type'])
                    @case('text')
                    @case('date')
                    @case('number')
                    @case('file')
                        <input type="{{ $champ['type'] }}" class="form-control form-control-lg bg-light" disabled>
                        @break
                    @case('textarea')
                        <textarea class="form-control form-control-lg bg-light" rows="4" disabled></textarea>
                        @break
                    @case('checkbox')
                        @foreach (explode(',', $champ['options'] ?? '') as $opt)
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" disabled>
                                <label class="form-check-label text-muted">{{ trim($opt) }}</label>
                            </div>
                        @endforeach
                        @break
                    @case('select')
                        <select class="form-select form-select-lg bg-light" disabled>
                            <option>-- Sélectionner --</option>
                            @foreach (explode(',', $champ['options'] ?? '') as $opt)
                                <option>{{ trim($opt) }}</option>
                            @endforeach
                        </select>
                        @break
                @endswitch
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .card.rounded-4 {
        border-radius: 1.5rem !important;
    }

    .preview-header p {
        font-size: 0.9rem;
    }

    .form-control-lg, .form-select-lg {
        border-radius: 0.75rem;
        transition: all 0.2s ease-in-out;
        border: 1px solid #e0e0e0;
        padding: 0.75rem 1.25rem;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .form-label.text-secondary {
        color: #495057 !important;
        font-size: 1.05rem;
    }

    .form-check-label.text-muted {
        color: #6c757d !important;
    }

    .form-check-input {
        border-radius: 0.35rem;
    }
</style>
@endpush
