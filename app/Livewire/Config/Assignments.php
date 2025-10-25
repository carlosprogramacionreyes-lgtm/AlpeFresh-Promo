<?php

namespace App\Livewire\Config;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Asignaciones')]
class Assignments extends Component
{
    public function render()
    {
        return view('livewire.config.assignments')->with('pageTitle', 'Asignaciones');
    }
}
