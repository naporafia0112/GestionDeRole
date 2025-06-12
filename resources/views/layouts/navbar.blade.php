<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">Laravel Roles</a>
    <div>
      @auth
        <span class="text-light me-2">{{ Auth::user()->name }}</span>
        <a class="btn btn-sm btn-outline-light" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Déconnexion
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
      @endauth
    </div>
  </div>
</nav>
