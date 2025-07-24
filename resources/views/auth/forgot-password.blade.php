<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Réinitialisation Mot de Passe - DIPRH</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Plateforme de gestion des ressources humaines" name="description" />
    <meta name="author" content="Votre entreprise" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assets/images/logo.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-color: #3b82f6; /* Bleu */
            --primary-hover: #2563eb;
            --secondary-color: #f8fafc;
            --text-color: #1e293b;
            --light-text: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            color: var(--text-color);
        }

        .login-card { /* Utilisé pour le conteneur principal */
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
        }

        .input-field { /* Nouvelle classe pour les inputs stylisés */
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .input-field:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .btn-primary {
            background: var(--primary-color);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .auth-logo {
            transition: all 0.5s ease;
        }

        .auth-logo:hover {
            transform: scale(1.05);
        }

        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: -1;
        }

        .wave-bg svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }

        .wave-bg .shape-fill {
            fill: #3b82f6;
            opacity: 0.1;
        }
    </style>
</head>

<body class="flex items-center justify-center p-4">
    <div class="wave-bg">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
        </svg>
    </div>

    <div class="w-full max-w-md animate__animated animate__fadeIn">
        <div class="login-card p-8 sm:p-10"> {{-- Remplace le bg-white shadow-xl rounded-2xl --}}
            <div class="text-center mb-8"> {{-- Remplace le px-6 py-8 div --}}
                <div class="auth-logo inline-block mb-4">
                    <a href="/" class="flex items-center justify-center">
                        <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo DIPRH" class="h-20 mx-auto rounded-md shadow-md">
                    </a>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Réinitialisation du mot de passe</h2>
                <p class="text-sm text-gray-500 mt-2">Saisissez votre email pour recevoir un lien sécurisé</p>
            </div>

            <x-auth-session-status class="mb-4 text-sm text-green-600 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6"> {{-- space-y-5 devenu space-y-6 pour consistance --}}
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label> {{-- Ajouté mb-1 --}}
                    <div class="relative"> {{-- mt-1 retiré car input-field gère le padding/margin --}}
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-mail-line text-lg text-gray-400"></i> {{-- Ajouté text-gray-400 pour consistance --}}
                        </div>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="votre@email.com"
                               class="input-field pl-10 w-full px-4 py-3 rounded-lg focus:outline-none" /> {{-- Appliqué input-field --}}
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" /> {{-- mt-1 devenu mt-2 --}}
                </div>

                <div class="flex items-center justify-end mt-4"> {{-- Ajusté le style du bouton pour coller au template --}}
                    <button type="submit"
                            class="btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="ri-send-plane-line mr-2 text-lg"></i> Envoyer le lien de réinitialisation
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500"> {{-- text-gray-600 devenu text-gray-500 pour consistance --}}
                <p>
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500">Retour à la connexion</a> {{-- hover:underline devenu hover:text-blue-500 --}}
                </p>
            </div>

            <div class="mt-8 text-center text-sm text-gray-500"> {{-- Footer déplacé ici pour être consistent avec login page --}}
                <p>© {{ date('Y') }} DIPRH. Tous droits réservés.</p>
                <p class="mt-1">Visitez notre site <a href="https://www.cagecfi.com/" class="text-blue-600 hover:text-blue-800">CAGECFI</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                html: `
                    <ul class="text-left text-sm list-disc pl-5"> {{-- Ajouté list-disc pl-5 --}}
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonColor: '#3b82f6',
                background: '#ffffff',
                backdrop: `
                    rgba(59, 130, 246, 0.1)
                `
            });
        });
    </script>
    @endif
</body>
</html>
