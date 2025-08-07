<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attestation;
use App\Models\Stage;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class AttestationController extends Controller
{
    /**
     * Affiche la liste des attestations.
     */
    public function index()
    {
        $attestations = Attestation::with('stage.candidature.candidat')->latest()->get();
        return view('admin.attestations.liste', compact('attestations'));
    }

    /**
     * Affiche le formulaire de création d'attestation pour un stage donné.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'stage_id' => 'required|exists:stages,id',
        ]);

        $stages = Stage::with('candidature.candidat')->get();
        $selectedStage = Stage::with('candidature.candidat')->find($data['stage_id']);

        return view('admin.attestations.creation', compact('stages', 'selectedStage'));
    }

    /**
     * Enregistre une nouvelle attestation.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'type' => 'required|in:academique,professionnel',
            'service' => 'required|string',
            'debut' => 'required|date',
            'fin' => 'required|date',
        ]);

        $data['date_generation'] = Carbon::now();

        $attestation = Attestation::create($data);

        return redirect()->route('attestations.liste', $attestation);
    }

    /**
     * Affiche les détails d'une attestation.
     */
    public function show(Attestation $attestation)
    {
        $attestation->load('stage.candidature.candidat');
        return view('admin.attestations.detail', compact('attestation'));
    }

    /**
     * Exporte l'attestation en PDF.
     */


    public function exportPDF(Attestation $attestation, Request $request)
    {
        // Charger les relations nécessaires
        $attestation->load('stage.candidature.candidat');

        // Récupérer les paramètres
        $forme = $request->query('forme', 'standard');
        $action = $request->query('action', 'download'); // 'download' ou 'view'

        // Choisir la vue selon la forme
        switch ($forme) {
            case 'detaillée':
                $view = 'admin.attestations.pdf_detaillée';
                $filename = 'attestation_detaillée.pdf';
                break;
            case 'avec_logo':
                $view = 'admin.attestations.pdf_avec_logo';
                $filename = 'attestation_avec_logo.pdf';
                break;
            default:
                $view = 'admin.attestations.pdf';
                $filename = 'attestation.pdf';
                break;
        }

        // Générer le PDF
        $pdf = PDF::loadView($view, compact('attestation', 'forme'));

        // Si aperçu demandé, on affiche dans l'iframe (Content-Type: application/pdf)
        if ($action === 'view') {
            return $pdf->stream($filename); // <- Affiche dans <iframe>
        }

        // Sinon, téléchargement
        return $pdf->download($filename);
    }



    /**
     * Exporte l'attestation en document Word.
     */
   public function exportWord(Attestation $attestation)
    {
        $attestation->load('stage.candidature.candidat');
        $candidat = $attestation->stage->candidature->candidat;

        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop'    => 600,
            'marginBottom' => 600,
            'marginLeft'   => 800,
            'marginRight'  => 800,
        ]);

        // En-tête version
        $section->addText(
            'RHU-ENR-08-CTG-19 – Version 1 - 20/08/2019',
            ['italic' => true, 'size' => 12],
            ['alignment' => 'center']
        );

        // Titre
        $section->addTextBreak(1);
        $section->addText(
            'ATTESTATION DE STAGE',
            ['bold' => true, 'size' => 16],
            ['alignment' => 'center']
        );
        $section->addTextBreak(1);

        // Corps du texte
        $debut = Carbon::parse($attestation->debut)->format('d/m/Y');
        $fin = Carbon::parse($attestation->fin)->format('d/m/Y');
        $dateGen = Carbon::parse($attestation->date_generation)->translatedFormat('d F Y');

        $texte = "Nous, soussignée société CAGECFI SA, 03 BP 31041, Téléphone : 22 26 84 61, Lomé – Togo, attestons que Mr/Mme "
            . "{$candidat->nom} {$candidat->prenoms} a effectué un stage {$attestation->type} "
            . "du {$debut} au {$fin} à la (Direction/Service) de notre société. "
            . "Au cours de son stage, il a fait preuve d’assiduité et de dynamisme à son poste. "
            . "En foi de quoi, nous lui délivrons cette présente attestation pour servir et valoir ce que de droit.";

        $section->addText($texte, ['size' => 12], ['alignment' => 'both']);
        $section->addTextBreak(2);

        // Signature (à droite)
        $signatureTexte = "Fait à Lomé, le {$dateGen}\n\nLe Président – Directeur Général,\n\n\nHOUNDJAGO Kodjo Amèvo";
        $section->addText($signatureTexte, ['size' => 12], ['alignment' => 'right']);

        // Export
        $fileName = 'attestation_' . $candidat->nom . '_' . now()->format('Ymd_His') . '.docx';
        $path = storage_path("app/public/{$fileName}");

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

}
