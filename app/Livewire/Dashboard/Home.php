<?php

namespace App\Livewire\Dashboard;

use App\Enums\UserRole;
use App\Models\Assignment;
use App\Models\Evaluation;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Home extends Component
{
    public function render()
    {
        $user = auth()->user();

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $currentMonth = $today->format('Y-m');
        $previousMonth = $today->copy()->subMonth()->format('Y-m');

        $visitsToday = Evaluation::query()
            ->whereRaw('DATE(COALESCE(visited_at, created_at)) = ?', [$today->toDateString()])
            ->count();

        $visitsYesterday = Evaluation::query()
            ->whereRaw('DATE(COALESCE(visited_at, created_at)) = ?', [$yesterday->toDateString()])
            ->count();

        $visitsThisMonth = Evaluation::query()
            ->whereRaw('DATE_FORMAT(COALESCE(visited_at, created_at), "%Y-%m") = ?', [$currentMonth])
            ->count();

        $visitsPreviousMonth = Evaluation::query()
            ->whereRaw('DATE_FORMAT(COALESCE(visited_at, created_at), "%Y-%m") = ?', [$previousMonth])
            ->count();

        $submittedThisMonth = Evaluation::query()
            ->submitted()
            ->whereRaw('DATE_FORMAT(COALESCE(visited_at, created_at), "%Y-%m") = ?', [$currentMonth])
            ->count();

        $pendingEvaluations = Evaluation::query()
            ->where('status', '!=', 'submitted')
            ->count();

        $incidentEvaluations = Evaluation::query()
            ->whereJsonLength('incidents->categories', '>', 0)
            ->count();

        $averageQuality = Evaluation::query()
            ->whereNotNull('quality_rating')
            ->avg('quality_rating');

        $storesCount = Store::count();
        $lastStore = Store::latest('created_at')->first();
        $lastStoreText = $lastStore?->created_at?->diffForHumans() ?? 'N/D';

        $activePromoters = User::query()
            ->where('role', UserRole::Promotor)
            ->whereHas('assignments', fn ($query) => $query->active())
            ->count();

        $activeAssignments = Assignment::query()->active()->count();

        $metricCards = [
            [
                'label' => 'Visitas Hoy',
                'value' => number_format($visitsToday),
                'change' => $this->formatDelta($visitsToday, $visitsYesterday, 'vs. ayer'),
                'icon' => 'clock-3',
            ],
            [
                'label' => 'Promotores Activos',
                'value' => number_format($activePromoters),
                'change' => $activeAssignments . ' asignaciones',
                'icon' => 'users',
            ],
            [
                'label' => 'Tiendas Registradas',
                'value' => number_format($storesCount),
                'change' => 'Última alta ' . $lastStoreText,
                'icon' => 'store',
            ],
            [
                'label' => 'Visitas del Mes',
                'value' => number_format($visitsThisMonth),
                'change' => $this->formatDelta($visitsThisMonth, $visitsPreviousMonth, 'vs. mes anterior'),
                'icon' => 'calendar-days',
            ],
            [
                'label' => 'Pendientes',
                'value' => number_format($pendingEvaluations),
                'change' => 'Por cerrar',
                'icon' => 'clipboard-list',
            ],
            [
                'label' => 'Evaluaciones (Mes)',
                'value' => number_format($submittedThisMonth),
                'change' => 'Enviadas este mes',
                'icon' => 'file-bar-chart',
            ],
            [
                'label' => 'Incidencias Pendientes',
                'value' => number_format($incidentEvaluations),
                'change' => 'Revisión requerida',
                'icon' => 'alert-octagon',
            ],
            [
                'label' => 'Calidad Promedio',
                'value' => $averageQuality ? number_format(($averageQuality / 5) * 100, 1) . '%' : 'N/D',
                'change' => 'Meta 95%',
                'icon' => 'sparkles',
            ],
        ];

        $quickActions = [
            ['label' => 'Nueva Visita', 'icon' => 'zap', 'route' => 'evaluations.create'],
            ['label' => 'Mis Visitas', 'icon' => 'file-text', 'route' => 'evaluations.index'],
            ['label' => 'Reportes', 'icon' => 'bar-chart-3', 'route' => 'reports.dashboard'],
        ];

        return view('livewire.dashboard.home', [
            'user' => $user,
            'metricCards' => $metricCards,
            'quickActions' => $quickActions,
        ])->with('pageTitle', 'Panel principal');
    }

    private function formatDelta(int|float $current, int|float $previous, string $suffix): string
    {
        if ($previous === 0) {
            return $current > 0
                ? '+100% ' . $suffix
                : '0% ' . $suffix;
        }

        $delta = (($current - $previous) / $previous) * 100;

        return sprintf('%+0.1f%% %s', $delta, $suffix);
    }
}
