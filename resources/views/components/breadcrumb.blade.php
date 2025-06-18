@props([
    'homeText' => 'Accueil',
    'homeUrl' => '/dashboard',
    'links' => []
])

<ol class="breadcrumb m-0">
    <!-- Lien Accueil -->
    <li class="breadcrumb-item">
        <a href="{{ $homeUrl }}">
            <i class="uil-home-alt me-1"></i> {{ $homeText }}
        </a>
    </li>

    <!-- Liens personnalisés -->
    @foreach($links as $link)
        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
            @unless($loop->last)
                <a href="{{ $link['url'] ?? 'javascript:void(0)' }}">
                    @isset($link['icon'])
                        <i class="{{ $link['icon'] }} me-1"></i>
                    @endisset
                    {{ $link['text'] }}
                </a>
            @else
                @isset($link['icon'])
                    <i class="{{ $link['icon'] }} me-1"></i>
                @endisset
                {{ $link['text'] }}
            @endunless
        </li>
    @endforeach
</ol>
