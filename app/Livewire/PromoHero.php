<?php

namespace App\Livewire;

use Livewire\Component;

class PromoHero extends Component
{
    public array $metrics = [
        [
            'label' => 'Leads generados',
            'value' => '3,482',
            'trend' => '+28%',
        ],
        [
            'label' => 'Ticket medio',
            'value' => '$189',
            'trend' => '+12%',
        ],
        [
            'label' => 'Retención',
            'value' => '92%',
            'trend' => '+6%',
        ],
    ];

    public array $ctaSteps = [
        'Define tu objetivo y conecta la fuente de datos.',
        'Configura la secuencia de Livewire + Jobs.',
        'Personaliza la experiencia con Tailwind y componentes SPA.',
    ];

    public function render()
    {
        return view('livewire.promo-hero', [
            'metrics' => $this->metrics,
            'ctaSteps' => $this->ctaSteps,
        ]);
    }
}
