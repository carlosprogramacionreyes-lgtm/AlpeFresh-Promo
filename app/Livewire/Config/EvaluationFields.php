<?php

namespace App\Livewire\Config;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Campos de evaluación')]
class EvaluationFields extends Component
{
    public function render()
    {
        return view('livewire.config.evaluation-fields')->with('pageTitle', 'Campos de evaluación');
    }
}
