<div>
    <h5 class="mb-3">Titre : {{ $formulaire->titre }}</h5>

    @foreach ($formulaire->champs as $champ)
        <div class="mb-3">
            <label class="form-label">{{ $champ['label'] }}</label>

            @switch($champ['type'])
                @case('text')
                @case('date')
                @case('number')
                @case('file')
                    <input type="{{ $champ['type'] }}" class="form-control" disabled>
                    @break

                @case('textarea')
                    <textarea class="form-control" disabled></textarea>
                    @break

                @case('checkbox')
                    @foreach (explode(',', $champ['options'] ?? '') as $opt)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" disabled>
                            <label class="form-check-label">{{ trim($opt) }}</label>
                        </div>
                    @endforeach
                    @break

                @case('select')
                    <select class="form-select" disabled>
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
