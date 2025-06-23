@extends('layouts.home')

@section('content')
<div class="container mt-4">
    {{-- Utilisation d'une "Card" Bootstrap pour un meilleur encadrement du contenu --}}
    <div class="card shadow-sm">
        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="">DIPRH</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Liste des utilisateurs</a></li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><strong>Details</strong></h4>
                                </div>
                            </div>
                        </div>
        <div class="card-body">

            {{-- Le div "table-responsive" assure que le tableau ne casse pas le design sur mobile --}}
            <div class="table-responsive">
                <ul>
                    <li>Nom: {{$user->name}}</li>
                    <li>Email: {{$user->email}}</li>
                    @foreach ($roles as $role)
                    <li>{{ $role->name }}</li>
                    @endforeach
                </ul>
            </div>
            <a href="{{ route('user.index') }}" class="btn btn-secondary"><i class="fe-arrow-left"></i></a>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Pour que les icônes fonctionnent, assurez-vous d'inclure Font Awesome dans votre layout principal (layouts/app.blade.php) --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> --}}
@endpush
