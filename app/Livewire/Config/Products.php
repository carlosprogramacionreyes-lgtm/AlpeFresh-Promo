<?php

namespace App\Livewire\Config;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Productos')]
class Products extends Component
{
    public function render()
    {
        return view('livewire.config.products')->with('pageTitle', 'Productos');
    }
}
