<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Ollama - IA Locale</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="mb-4">Test de l'IA locale avec Ollama</h2>

        <div class="mb-3">
            <button class="btn btn-primary me-2" onclick="testConnection()">Tester la connexion</button>
            <button class="btn btn-info me-2" onclick="getModels()">Lister les modèles</button>
            <button class="btn btn-success" onclick="sampleTest()">Tester un exemple de CV</button>
        </div>

        <form id="cvForm" onsubmit="analyzeCV(event)">
            <div class="mb-3">
                <label for="prompt" class="form-label">Contenu à analyser :</label>
                <textarea class="form-control" id="prompt" name="prompt" rows="4" placeholder="Collez ici un extrait de CV..."></textarea>
            </div>
            <button type="submit" class="btn btn-dark">Analyser avec Ollama</button>
        </form>

        <div class="mt-4">
            <h5>Résultat :</h5>
            <pre id="output" class="bg-white p-3 border rounded" style="white-space: pre-wrap;"></pre>
        </div>
    </div>

    <script>
        function analyzeCV(e) {
            e.preventDefault();
            const prompt = document.getElementById('prompt').value;

            fetch('/ollama/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ prompt })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('output').textContent = data.response || JSON.stringify(data);
            });
        }

        function testConnection() {
            fetch('/ollama/connection')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('output').textContent = JSON.stringify(data, null, 2);
                });
        }

        function getModels() {
            fetch('/ollama/models')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('output').textContent = JSON.stringify(data, null, 2);
                });
        }

        function sampleTest() {
            fetch('/ollama/sample-test')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('output').textContent = data.response || JSON.stringify(data);
                });
        }
    </script>
</body>
</html>
