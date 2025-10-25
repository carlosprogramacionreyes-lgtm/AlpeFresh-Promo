<?php

namespace App\Livewire\Reports;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Reportes')]
class Dashboard extends Component
{
    use AuthorizesRequests;

    public function mount(): void
    {
        $this->authorize('view-reports');
    }

    public function render()
    {
        return view('livewire.reports.dashboard')->with('pageTitle', 'Reportes');
    }
}
