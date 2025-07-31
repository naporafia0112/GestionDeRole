@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href=#>Liste des entretiens</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">Liste des entretients</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        {{-- Onglets Bootstrap --}}
                        <ul class="nav nav-tabs" id="entretienTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="prevu-tab" data-bs-toggle="tab" data-bs-target="#prevu" type="button" role="tab" aria-controls="prevu" aria-selected="true">
                                    Prévu ({{ $entretiensPrevus->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="annule-tab" data-bs-toggle="tab" data-bs-target="#annule" type="button" role="tab" aria-controls="annule" aria-selected="false">
                                    Annulé ({{ $entretiensAnnules->count() }})
                                </button>
                            </li>
                           
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="effectuee-tab" data-bs-toggle="tab" data-bs-target="#effectuee" type="button" role="tab" aria-controls="effectuee" aria-selected="false">
                                    Effectuée ({{ $entretiensEffectues->count() }})
                                </button>
                            </li>
                            
                        </ul>

                        {{-- Contenu des onglets --}}
                        <div class="tab-content mt-3" id="entretienTabsContent">

                            <div class="tab-pane fade show active" id="prevu" role="tabpanel" aria-labelledby="prevu-tab">
                                @include('admin.entretiens.partials', ['entretiens' => $entretiensPrevus])
                            </div>

                            <div class="tab-pane fade" id="annule" role="tabpanel" aria-labelledby="annule-tab">
                                @include('admin.entretiens.partials', ['entretiens' => $entretiensAnnules])
                            </div>

                           
                            <div class="tab-pane fade" id="effectuee" role="tabpanel" aria-labelledby="effectuee-tab">
                                @include('admin.entretiens.partials', ['entretiens' => $entretiensEffectues])
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
