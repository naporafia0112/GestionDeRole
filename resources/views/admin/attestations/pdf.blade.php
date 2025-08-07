<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Attestation</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14pt;
            margin: 50px;
        }
        h1 {
            text-align: center;
            font-size: 20pt;
            margin-bottom: 40px;
        }
        p {
            text-align: justify;
            line-height: 1.6;
        }
        .text-center {
            text-align: center;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="text-center">
        <p>RHU-ENR-08-CTG-19 – Version 1 - 20/08/2019</p>
    </div>

    <h1>ATTESTATION DE STAGE</h1>

    <p>
        Nous, soussignée société <strong>CAGECFI SA, 03 BP 31041, Téléphone : 22 26 84 61, Lomé – Togo</strong>,
        attestons que Mr/Mme <strong>{{ $attestation->stage->candidature->candidat->nom }} {{ $attestation->stage->candidature->candidat->prenoms }}</strong>
        a effectué un stage <strong>{{ $attestation->type }}</strong>
        du <strong>{{ \Carbon\Carbon::parse($attestation->debut)->format('d/m/Y') }}</strong>
        au <strong>{{ \Carbon\Carbon::parse($attestation->fin)->format('d/m/Y') }}</strong>
        à la (Direction/Service) <strong>{{ $attestation->service }}</strong> de notre société.
    </p>

    <p>
        Au cours de son stage, il a fait preuve d’assiduité et de dynamisme à son poste.
    </p>

    <p>
        En foi de quoi, nous lui délivrons cette présente attestation pour servir et valoir ce que de droit.
    </p>

    <table style="width:100%; margin-top: 50px;">
        <tr>
            <td style="text-align:right;">
                <p>Fait à Lomé, le {{ \Carbon\Carbon::parse($attestation->date_generation)->translatedFormat('d F Y') }}</p>
                <p>Le Président – Directeur Général,</p>
                <br><br>
                <p><strong>HOUNDJAGO Kodjo Amèvo</strong></p>
            </td>
        </tr>
    </table>


</body>
</html>
