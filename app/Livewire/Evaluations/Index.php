<?php

namespace App\Livewire\Evaluations;

use App\Models\Evaluation;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Mis visitas')]
class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public array $filters = [
        'store' => '',
        'status' => '',
        'date_from' => '',
        'date_to' => '',
    ];

    public string $searchTerm = '';
    public int $perPage = 10;

    public array $statusOptions = [
        'submitted' => 'Enviada',
        'draft' => 'Borrador',
    ];

    public array $incidentOptions = [
        'area_exhibicion' => 'Área y exhibición',
        'producto' => 'Producto',
        'stock_precio' => 'Stock / Precio',
        'limpieza' => 'Limpieza',
        'sin_incidencias' => '✅ Sin incidencias',
    ];

    public bool $showDetail = false;
    public array $selectedEvaluation = [];

    protected $queryString = [
        'filters' => ['except' => ['store' => '', 'status' => '', 'date_from' => '', 'date_to' => '']],
        'searchTerm' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingFilters(): void
    {
        $this->resetPage();
    }

    public function updatingSearchTerm(): void
    {
        $this->resetPage();
    }

    public function selectEvaluation(int $evaluationId): void
    {
        $evaluation = Evaluation::query()
            ->with(['store.chain', 'store.zone', 'photos'])
            ->where('user_id', Auth::id())
            ->findOrFail($evaluationId);

        $this->selectedEvaluation = [
            'id' => $evaluation->id,
            'code' => $evaluation->code,
            'status' => $evaluation->status,
            'visited_at' => optional($evaluation->visited_at)->format('Y-m-d H:i'),
            'quality_rating' => $evaluation->quality_rating,
            'price_observed' => $evaluation->price_observed,
            'price_regular' => $evaluation->price_regular,
            'price_discount' => $evaluation->price_discount,
            'has_promotion' => $evaluation->has_promotion,
            'availability' => $evaluation->availability ?? [],
            'incidents' => $evaluation->incidents ?? [],
            'incident_comments' => $evaluation->incident_comments,
            'review_notes' => $evaluation->review_notes,
            'geofence_valid' => $evaluation->geofence_valid,
            'latitude' => $evaluation->latitude,
            'longitude' => $evaluation->longitude,
            'store' => [
                'name' => $evaluation->store?->name,
                'chain' => $evaluation->store?->chain?->name,
                'zone' => $evaluation->store?->zone?->name,
                'address' => $evaluation->store?->address_line1,
            ],
            'photos' => $evaluation->photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'step' => $photo->step,
                    'label' => $photo->label,
                    'url' => $photo->file_path ? Storage::disk('public')->url($photo->file_path) : null,
                ];
            })->toArray(),
        ];

        $this->showDetail = true;
    }

    public function closeEvaluationDetail(): void
    {
        $this->selectedEvaluation = [];
        $this->showDetail = false;
    }

    public function render()
    {
        $userId = Auth::id();
        $baseQuery = Evaluation::query()
            ->with(['store.chain', 'store.zone'])
            ->where('user_id', $userId);

        $metrics = [
            'total' => (clone $baseQuery)->count(),
            'current_month' => (clone $baseQuery)
                ->whereRaw('DATE_FORMAT(COALESCE(visited_at, created_at), "%Y-%m") = ?', [now()->format('Y-m')])
                ->count(),
            'pending' => (clone $baseQuery)->where('status', '!=', 'submitted')->count(),
            'incidents' => (clone $baseQuery)
                ->where(function ($query) {
                    $query->whereJsonLength('incidents->categories', '>', 0)
                        ->orWhereNotNull('incident_comments');
                })
                ->count(),
        ];

        $query = (clone $baseQuery)
            ->orderByDesc('visited_at')
            ->orderByDesc('created_at');

        if (! empty($this->filters['store'])) {
            $query->where('store_id', $this->filters['store']);
        }

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (! empty($this->filters['date_from'])) {
            $query->whereRaw('DATE(COALESCE(visited_at, created_at)) >= ?', [$this->filters['date_from']]);
        }

        if (! empty($this->filters['date_to'])) {
            $query->whereRaw('DATE(COALESCE(visited_at, created_at)) <= ?', [$this->filters['date_to']]);
        }

        if ($this->searchTerm) {
            $term = '%' . mb_strtolower($this->searchTerm) . '%';
            $query->where(function ($subQuery) use ($term) {
                $subQuery->whereRaw('LOWER(code) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(review_notes) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(incident_comments) LIKE ?', [$term])
                    ->orWhere('availability', 'like', $term)
                    ->orWhereHas('store', function ($storeQuery) use ($term) {
                        $storeQuery->whereRaw('LOWER(name) LIKE ?', [$term]);
                    });
            });
        }

        $evaluations = $query->paginate($this->perPage);

        $storeOptions = Store::query()
            ->whereHas('assignments', fn ($assignment) => $assignment->where('user_id', $userId)->active())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.evaluations.index', [
            'evaluations' => $evaluations,
            'metrics' => $metrics,
            'stores' => $storeOptions,
        ])->with('pageTitle', 'Mis visitas');
    }
}
