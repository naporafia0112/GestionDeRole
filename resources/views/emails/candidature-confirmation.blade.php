<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de candidature</title>
</head>
<body class="bg-red-100">
  <div class="container">
    <img class="my-6 w-16" src="{{ asset('assets/images/logo.jpg') }}" />
    <div class="space-y-4 mb-6">
      <h1 class="text-4xl fw-800">Confirmation de candidature</h1>
      <p>Bonjour {{ $candidature->candidat->prenoms }} {{ $candidature->candidat->nom }},</p>
    </div>
    <div class="card rounded-3xl px-4 py-8 p-lg-10 mb-6">
      <h3 class="text-center">Votre candidature a bien été reçue.</h3>
      <p class="text-center text-muted">Vous pouvez suivre l'évolution de votre candidature avec ce lien unique :</p>
      <a class="btn btn-red-500 rounded-full px-6 w-full w-lg-48" href="{{ route('candidatures.suivi', $candidature->uuid) }}">Suivi de ma candidature</a>
       <p>Merci et bonne chance !</p>
      <hr class="my-6">
      <p>Merci et bonne chance ! <a href="http://127.0.0.1:8000">Consultez</a>.</p>
    </div>
  </div>
</body>
</html>


