<?php

namespace App\Livewire\Dashboard;

use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RecentActivity extends Component
{
    public function render()
    {
        $activities = Evaluation::query()
            ->with(['store', 'user'])
            ->latest('visited_at')
            ->latest('created_at')
            ->take(6)
            ->get()
            ->map(function (Evaluation $evaluation) {
                $incidents = collect($evaluation->incidents['categories'] ?? [])->filter()->count();

                return [
                    'id' => $evaluation->id,
                    'title' => $evaluation->store?->name ? $evaluation->store->name : $evaluation->code,
                    'subtitle' => $evaluation->code,
                    'description' => $evaluation->status === 'submitted'
                        ? 'Enviada por ' . ($evaluation->user?->first_name ?? 'Promotor')
                        : 'En progreso · ' . ($evaluation->user?->first_name ?? 'Promotor'),
                    'icon' => $evaluation->status === 'submitted' ? 'check-circle-2' : 'loader-2',
                    'timestamp' => $evaluation->visited_at ?? $evaluation->created_at,
                    'quality' => $evaluation->quality_rating,
                    'incidents' => $incidents,
                ];
            });

        return view('livewire.dashboard.recent-activity', [
            'activities' => $activities,
        ]);
    }
}
