<?php

namespace App\Livewire\Config;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Tiendas')]
class Stores extends Component
{
    public function render()
    {
        return view('livewire.config.stores')->with('pageTitle', 'Tiendas');
    }
}
