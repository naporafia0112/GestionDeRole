<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BoutonRetour extends Component
{
    public $retour;

    public function __construct($retour = null)
    {
        $this->retour = $retour;
    }

    public function render()
    {
        return view('components.bouton-retour');
    }

    public function routeRetour()
    {
        return match($this->retour) {
            'retenus' => route('candidatures.retenus'),
            'cours' => url()->previous(), 
            default => route('candidatures.index'),
        };
    }
}
?>
