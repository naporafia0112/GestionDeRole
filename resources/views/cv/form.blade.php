@extends('layouts.home')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Analyse de CV avec IA locale (PDF)</h2>

    <form id="analyzeForm" enctype="multipart/form-data" method="POST" action="{{ route('cv.analyze') }}">
        @csrf
        <div class="mb-3">
            <label for="cv_file" class="form-label">Fichier CV (PDF)</label>
            <input type="file" name="cv_file" id="cv_file" class="form-control" accept=".pdf" required>
        </div>

        <div class="mb-3">
            <label for="job_description" class="form-label">Description du poste</label>
            <textarea name="job_description" id="job_description" class="form-control" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Analyser</button>
    </form>

    <div class="mt-4" id="resultSection" style="display:none;">
        <h4>Résultat :</h4>
        <div class="alert alert-info" id="scoreBox"></div>
        <div class="alert alert-secondary" id="commentBox"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('analyzeForm');
    const submitBtn = document.getElementById('submitBtn');
    const resultSection = document.getElementById('resultSection');
    const scoreBox = document.getElementById('scoreBox');
    const commentBox = document.getElementById('commentBox');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Désactiver le bouton pour éviter les doubles clics
        submitBtn.disabled = true;
        submitBtn.textContent = "Analyse en cours...";

        resultSection.style.display = 'none';
        scoreBox.textContent = '';
        commentBox.textContent = '';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (!response.ok) {
                let errMsg = 'Erreur serveur inconnue';
                try {
                    const errData = await response.json();
                    errMsg = errData.error || errMsg;
                } catch {
                    // ignore parse error
                }
                alert(errMsg);
                submitBtn.disabled = false;
                submitBtn.textContent = "Analyser";
                return;
            }

            const data = await response.json();

            if (data.error) {
                alert(data.error);
                submitBtn.disabled = false;
                submitBtn.textContent = "Analyser";
                return;
            }

            // Afficher le résultat
            scoreBox.textContent = "Score de pertinence : " + data.score + " / 100";
            commentBox.textContent = "Commentaire : " + data.commentaire;
            resultSection.style.display = 'block';

        } catch (error) {
            alert("Erreur réseau ou serveur : " + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = "Analyser";
        }
    });
});
</script>
@endsection
