@extends('layouts.vitrine.vitrine')

@section('content')
<section class="section py-5" style="min-height: 60vh;">
  <div class="container d-flex justify-content-center align-items-center">
    <form action="{{ route('vitrine.consulter') }}" method="GET" class="w-75">
      <div class="input-group">
        <input type="text" name="query" class="form-control form-control-lg" placeholder="Suivez votre candidature...">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-search"></i> Rechercher
        </button>
      </div>
    </form>
  </div>
</section>
@endsection
