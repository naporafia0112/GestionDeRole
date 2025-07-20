@extends('layouts.vitrine.vitrine')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-gradient rounded-circle mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-person-plus text-white" style="font-size: 2rem;"></i>
                </div>
                <h1 class="display-6 fw-bold text-dark mb-2">
                    {{ isset($offre) ? 'Candidature' : 'Candidature Spontanée' }}
                </h1>
                <p class="lead text-muted">
                    @if(isset($offre))
                        Postulez pour : <span class="text-primary fw-semibold">{{ $offre->titre ?? 'Offre' }}</span>
                    @else
                        Soumettez votre candidature spontanée
                    @endif
                </p>

                <!-- Progress Bar -->
                <div class="progress mx-auto mb-4" style="height: 6px; width: 300px;">
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: 0%" id="formProgress"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row justify-content-center">
        <div class="col-xxl-10 col-xl-11">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-0">
                    <form action="{{ isset($offre) ? route('candidature.store', $offre->id) : route('candidature.spontanee.store') }}"
                          method="POST" enctype="multipart/form-data" id="candidatureForm" novalidate>
                        @csrf

                        <!-- Step 1: Informations personnelles -->
                        <div class="form-step active" id="step0">
                            <div class="step-header bg-light p-4 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="step-icon bg-primary text-white rounded-circle me-3">1</div>
                                    <div>
                                        <h4 class="mb-1 fw-bold">Informations personnelles</h4>
                                        <p class="text-muted mb-0">Veuillez remplir vos coordonnées</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                                                   class="form-control form-control-lg @error('nom') is-invalid @enderror"
                                                   placeholder="Nom" required>
                                            <label for="nom">Nom <span class="text-danger">*</span></label>
                                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms') }}"
                                                   class="form-control form-control-lg @error('prenoms') is-invalid @enderror"
                                                   placeholder="Prénoms" required>
                                            <label for="prenoms">Prénoms <span class="text-danger">*</span></label>
                                            @error('prenoms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                   placeholder="Email" required>
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}"
                                                   class="form-control form-control-lg @error('telephone') is-invalid @enderror"
                                                   placeholder="Téléphone">
                                            <label for="telephone">Téléphone</label>
                                            @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="quartier" name="quartier" value="{{ old('quartier') }}"
                                                   class="form-control form-control-lg @error('quartier') is-invalid @enderror"
                                                   placeholder="Quartier" required>
                                            <label for="quartier">Quartier <span class="text-danger">*</span></label>
                                            @error('quartier') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="ville" name="ville" value="{{ old('ville') }}"
                                                   class="form-control form-control-lg @error('ville') is-invalid @enderror"
                                                   placeholder="Ville" required>
                                            <label for="ville">Ville <span class="text-danger">*</span></label>
                                            @error('ville') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Type de candidature et Message -->
                        <div class="form-step" id="step1">
                            <div class="step-header bg-light p-4 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="step-icon bg-primary text-white rounded-circle me-3">2</div>
                                    <div>
                                        <h4 class="mb-1 fw-bold">Type de candidature</h4>
                                        <p class="text-muted mb-0">Choisissez le type de poste souhaité</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label fs-5 fw-semibold mb-3">Type de dépôt <span class="text-danger">*</span></label>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="type-card">
                                                    <input type="radio" class="btn-check" name="type_depot" id="stage_professionnel" value="stage professionnel" {{ old('type_depot') == 'stage professionnel' ? 'checked' : '' }} required>
                                                    <label class="btn btn-outline-primary btn-lg w-100 p-4" for="stage_professionnel">
                                                        <i class="bi bi-briefcase display-6 mb-2"></i>
                                                        <div class="fw-bold">Stage professionnel</div>
                                                        <small class="text-muted">Formation pratique en entreprise</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="type-card">
                                                    <input type="radio" class="btn-check" name="type_depot" id="stage_academique" value="stage académique" {{ old('type_depot') == 'stage académique' ? 'checked' : '' }} required>
                                                    <label class="btn btn-outline-primary btn-lg w-100 p-4" for="stage_academique">
                                                        <i class="bi bi-mortarboard display-6 mb-2"></i>
                                                        <div class="fw-bold">Stage académique</div>
                                                        <small class="text-muted">Formation théorique et pratique</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="type-card">
                                                    <input type="radio" class="btn-check" name="type_depot" id="stage_preembauche" value="stage de préembauche" {{ old('type_depot') == 'stage de préembauche' ? 'checked' : '' }} required>
                                                    <label class="btn btn-outline-primary btn-lg w-100 p-4" for="stage_preembauche">
                                                        <i class="bi bi-arrow-up-circle display-6 mb-2"></i>
                                                        <div class="fw-bold">Stage de préembauche</div>
                                                        <small class="text-muted">Préparation à l'embauche</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('type_depot') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Message pour candidature spontanée -->
                                    @if(!isset($offre))
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea id="message" name="message" class="form-control" placeholder="Message" style="height: 120px;">{{ old('message') }}</textarea>
                                            <label for="message">Message de motivation <span class="text-muted">(optionnel)</span></label>
                                            <div class="form-text">Pourquoi souhaitez-vous postuler chez nous ?</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Documents -->
                        <div class="form-step" id="step2">
                            <div class="step-header bg-light p-4 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="step-icon bg-primary text-white rounded-circle me-3">3</div>
                                    <div>
                                        <h4 class="mb-1 fw-bold">Documents requis</h4>
                                        <p class="text-muted mb-0">Téléchargez vos documents (format PDF uniquement)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="upload-card">
                                            <label for="cv_fichier" class="form-label fw-bold mb-3">
                                                <i class="bi bi-file-person text-primary me-2"></i>
                                                CV (prénom_nom.pdf) <span class="text-danger">*</span>
                                            </label>
                                            <div class="file-upload-wrapper position-relative">
                                                <input type="file" id="cv_fichier" name="cv_fichier"
                                                       class="form-control form-control-lg @error('cv_fichier') is-invalid @enderror"
                                                       accept=".pdf" required>
                                                <div class="file-upload-text position-absolute top-50 start-50 translate-middle text-muted" style="pointer-events:none;">
                                                    <i class="bi bi-cloud-upload"></i>
                                                    <div class="mt-2">Cliquez pour sélectionner votre CV</div>
                                                </div>
                                            </div>
                                            @error('cv_fichier') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="upload-card">
                                            <label for="lm_fichier" class="form-label fw-bold mb-3">
                                                <i class="bi bi-file-text text-primary me-2"></i>
                                                Lettre de motivation (prénom_nom.pdf) <span class="text-danger">*</span>
                                            </label>
                                            <div class="file-upload-wrapper position-relative">
                                                <input type="file" id="lm_fichier" name="lm_fichier"
                                                       class="form-control form-control-lg @error('lm_fichier') is-invalid @enderror"
                                                       accept=".pdf" required>
                                                <div class="file-upload-text position-absolute top-50 start-50 translate-middle text-muted" style="pointer-events:none;">
                                                    <i class="bi bi-cloud-upload"></i>
                                                    <div class="mt-2">Cliquez pour sélectionner votre lettre</div>
                                                </div>
                                            </div>
                                            @error('lm_fichier') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="upload-card">
                                            <label for="lr_fichier" class="form-label fw-bold mb-3">
                                                <i class="bi bi-file-earmark-check text-primary me-2"></i>
                                                Lettre de recommandation (prénom_nom.pdf) <span class="text-muted">(optionnel)</span>
                                            </label>
                                            <div class="file-upload-wrapper position-relative">
                                                <input type="file" id="lr_fichier" name="lr_fichier"
                                                       class="form-control form-control-lg @error('lr_fichier') is-invalid @enderror"
                                                       accept=".pdf">
                                                <div class="file-upload-text position-absolute top-50 start-50 translate-middle text-muted" style="pointer-events:none;">
                                                    <i class="bi bi-cloud-upload"></i>
                                                    <div class="mt-2">Cliquez pour sélectionner (optionnel)</div>
                                                </div>
                                            </div>
                                            @error('lr_fichier') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="card-footer bg-light p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ isset($offre) ? route('vitrine.show', $offre->id) : route('vitrine.index') }}"
                                       class="btn btn-outline-secondary rounded-pill">
                                        <i class="bi bi-arrow-left me-2"></i>
                                        {{ isset($offre) ? 'Retour à l\'offre' : 'Retour aux offres' }}
                                    </a>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="prevBtn" style="display: none;">
                                        <i class="bi bi-arrow-left me-2"></i>Précédent
                                    </button>
                                    <button type="button" class="btn btn-primary rounded-pill px-4" id="nextBtn">
                                        Suivant<i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitBtn" style="display: none;">
                                        <i class="bi bi-send me-2"></i>Soumettre la candidature
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styles --}}
<style>
    .form-step {
        display: none;
    }
    .form-step.active {
        display: block;
    }
    .step-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    .type-card .btn {
        height: 180px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .type-card .btn:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }
    .type-card .btn-check:checked + .btn {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
    }
    .upload-card {
        border: 2px dashed #e9ecef;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
    }
    .upload-card:hover {
        border-color: #0d6efd;
        background: rgba(13, 110, 253, 0.05);
    }
    .file-upload-wrapper input[type="file"] {
        opacity: 0;
        width: 100%;
        height: 100%;
        position: relative;
        z-index: 10;
        cursor: pointer;
    }
    .file-upload-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        color: #6c757d;
        font-size: 1rem;
    }
    .form-control-lg {
        padding: 1rem;
        font-size: 1.1rem;
    }
    .progress-bar {
        transition: width 0.3s ease;
    }
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        .type-card .btn {
            height: 150px;
        }
        .upload-card {
            padding: 1rem;
        }
    }
</style>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 0;
    const totalSteps = 3;

    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('formProgress');
    const form = document.getElementById('candidatureForm');
    const steps = [
        document.getElementById('step0'),
        document.getElementById('step1'),
        document.getElementById('step2')
    ];

    function showStep(step) {
        // Affiche uniquement l'étape courante
        steps.forEach((s, i) => {
            s.classList.toggle('active', i === step);
        });

        // Met à jour la barre de progression
        progressBar.style.width = ((step + 1) / totalSteps) * 100 + '%';

        // Boutons navigation
        prevBtn.style.display = step === 0 ? 'none' : 'inline-block';
        nextBtn.style.display = step === totalSteps - 1 ? 'none' : 'inline-block';
        submitBtn.style.display = step === totalSteps - 1 ? 'inline-block' : 'none';
    }

    function validateStep(step) {
        const stepElement = steps[step];
        const requiredFields = stepElement.querySelectorAll('[required]');

        for (const field of requiredFields) {
            if (field.type === 'radio') {
                const name = field.name;
                const checked = stepElement.querySelector(`input[name="${name}"]:checked`);
                if (!checked) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Champ requis',
                        text: 'Veuillez sélectionner une option avant de continuer.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            } else if (field.type === 'file') {
                if (field.files.length === 0) {
                    const labelText = field.closest('.upload-card').querySelector('label').textContent.trim();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Champ requis',
                        text: `Le champ "${labelText}" est obligatoire.`,
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            } else if (!field.value.trim()) {
                // Récupère le label lié au champ
                let labelText = '';
                if(field.closest('.form-floating')) {
                    labelText = field.closest('.form-floating').querySelector('label').textContent.trim();
                } else if(field.closest('.form-group')) {
                    labelText = field.closest('.form-group').querySelector('label').textContent.trim();
                } else {
                    labelText = field.name;
                }
                Swal.fire({
                    icon: 'warning',
                    title: 'Champ requis',
                    text: `Le champ "${labelText}" est obligatoire.`,
                    confirmButtonText: 'OK'
                });
                return false;
            }
        }
        return true;
    }

    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if(!validateStep(currentStep)) {
            return;
        }

        // Confirmation avant envoi final
        Swal.fire({
            title: 'Confirmer la soumission',
            text: "Voulez-vous vraiment soumettre votre candidature ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, soumettre',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Mise à jour visuelle du fichier sélectionné
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const wrapper = this.closest('.file-upload-wrapper');
            const textDiv = wrapper.querySelector('.file-upload-text');
            if (this.files.length > 0) {
                textDiv.innerHTML = `
                    <i class="bi bi-check-circle text-success"></i>
                    <div class="mt-2 text-success">${this.files[0].name}</div>
                `;
            } else {
                textDiv.innerHTML = `
                    <i class="bi bi-cloud-upload text-muted"></i>
                    <div class="mt-2">Cliquez pour sélectionner</div>
                `;
            }
        });
    });

    // Gestion des messages de session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    @elseif($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            html: `<ul style="text-align:left;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>`,
            confirmButtonText: 'OK'
        });
    @endif

    // Initialisation
    showStep(currentStep);
});
</script>
@endsection
