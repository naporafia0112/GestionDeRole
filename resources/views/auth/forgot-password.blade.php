<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-tr from-blue-100 via-blue-200 to-blue-300 py-12 px-4">
        <div class="w-full max-w-md bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="px-6 py-8">
                <div class="text-center mb-6">
                    <a href="/" class="inline-block mb-4">
                        <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" class="h-20 mx-auto rounded-md shadow-md">
                    </a>
                    <h2 class="text-2xl font-bold text-gray-800">Réinitialisation du mot de passe</h2>
                    <p class="text-sm text-gray-500 mt-2">Saisissez votre email pour recevoir un lien sécurisé</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-sm text-green-600 text-center" :status="session('status')" />

                <!-- Formulaire -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Adresse Email</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="ri-mail-line text-lg"></i>
                            </span>
                            <x-text-input id="email"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                placeholder="votre@email.com"
                                class="pl-10 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"/>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
                    </div>

                    <!-- Bouton -->
                    <div>
                        <x-primary-button class="w-full flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md shadow-md transition duration-200">
                            <i class="ri-send-plane-line me-2 text-lg"></i> Envoyer le lien de réinitialisation
                        </x-primary-button>
                    </div>
                </form>

                <!-- Lien retour -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">← Retour à la connexion</a>
                    </p>
                </div>
            </div>

            <div class="bg-gray-100 px-6 py-4 text-center text-sm text-gray-500">
                Pas reçu d’email ?
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline font-medium ml-1">Renvoyer le lien</a>
            </div>
        </div>
    </div>

    <!-- Remix Icon + SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                html: `
                    <ul class="text-left text-sm">
                        @foreach ($errors->all() as $message)
                            <li>• {{ $message }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonColor: '#3b82f6'
            });
        });
    </script>
    @endif
</x-guest-layout>
