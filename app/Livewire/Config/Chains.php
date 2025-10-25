<?php

namespace App\Livewire\Config;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Cadenas')]
class Chains extends Component
{
    public function render()
    {
        return view('livewire.config.chains')->with('pageTitle', 'Cadenas');
    }
}
