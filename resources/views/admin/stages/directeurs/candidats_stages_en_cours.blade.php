@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
    <div class="header-section mb-4">
        <div class="d-flex align-items-center mb-3 flex-wrap">
            <div class="icon-wrapper me-3 mb-2">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <h2 class="main-title mb-1">Candidats en Stage</h2>
                <p class="subtitle mb-0">Suivi des stagiaires actuellement en stage</p>
            </div>
        </div>
        <div class="stats-bar">
            <span class="badge bg-primary">{{ $candidats->count() }} candidat(s)</span>
        </div>
    </div>

    @if ($candidats->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h4>Aucun candidat en stage</h4>
            <p class="text-muted">Il n'y a actuellement aucun candidat en cours de stage.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach ($candidats as $candidat)
                <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="candidate-card">
                            <div class="card-gradient-bg"></div>
                            <div class="card-content">
                                <div class="candidate-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="candidate-info">
                                    <h5 class="candidate-name">{{ $candidat->prenom }} {{ $candidat->nom }}</h5>
                                    <div class="candidate-details">
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <span class="detail-text">{{ $candidat->email }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-icon">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                            <span class="detail-text">{{ $candidat->telephone }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-status">
                                    <a href="{{ route('candidats.details.directeur', $candidat->id) }}" class="btn btn-primary rounded-pill px-4">
                                            <i class="fe-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    :root {
        --primary-color: #00ACC1;
        --primary-light: #10b981;
        --secondary-color: #10b981;
        --accent-color: #00ACC1;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --bg-light: #f8fafc;
        --border-color: #e5e7eb;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Header */
    .header-section {
        background: linear-gradient(135deg, var(--bg-light), #ffffff);
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        position: relative;
    }

    .header-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }

    .icon-wrapper i {
        font-size: 18px;
        color: white;
    }

    .main-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .subtitle {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .stats-bar .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.75rem;
        border-radius: 30px;
        font-weight: 600;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light)) !important;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: var(--bg-light);
        border-radius: 12px;
        border: 2px dashed var(--border-color);
    }

    .empty-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--text-secondary), #9ca3af);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        opacity: 0.7;
    }

    .empty-icon i {
        font-size: 24px;
        color: white;
    }

    /* Candidate Card */
    .candidate-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        position: relative;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        animation: fadeInUp 0.4s ease-out;
    }

    .candidate-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-color);
    }

    .card-gradient-bg {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .candidate-card:hover .card-gradient-bg {
        opacity: 1;
    }

    .card-content {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .candidate-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s ease;
    }

    .candidate-card:hover .candidate-avatar {
        transform: scale(1.05);
    }

    .candidate-avatar i {
        font-size: 18px;
        color: white;
    }

    .candidate-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .candidate-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-icon {
        width: 28px;
        height: 28px;
        background: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        flex-shrink: 0;
    }

    .detail-icon i {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .detail-text {
        font-size: 0.8rem;
        color: var(--text-secondary);
        word-break: break-word;
    }

    .card-status {
        margin-top: 0.75rem;
        border-top: 1px solid var(--border-color);
        padding-top: 0.75rem;
    }

    .status-badge {
        background: linear-gradient(135deg, var(--secondary-color), #059669);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 40px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        box-shadow: var(--shadow-sm);
    }

    /* Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .main-title { font-size: 1.1rem; }
        .icon-wrapper { width: 40px; height: 40px; }
        .candidate-avatar { width: 40px; height: 40px; }
        .candidate-name { font-size: 0.95rem; }
    }

    @media (max-width: 576px) {
        .header-section .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .icon-wrapper {
            margin-bottom: 1rem;
            margin-right: 0 !important;
        }
    }

</style>
@endsection
