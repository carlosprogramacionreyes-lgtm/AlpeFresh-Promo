<?php

namespace App\Livewire\Config;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Permisos')]
class Permissions extends Component
{
    public function render()
    {
        return view('livewire.config.permissions')->with('pageTitle', 'Permisos');
    }
}
