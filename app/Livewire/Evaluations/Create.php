<?php

namespace App\Livewire\Evaluations;

use App\Models\Assignment;
use App\Models\Evaluation;
use App\Models\EvaluationPhoto;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Nueva visita')]
class Create extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public int $currentStep = 1;

    /**
     * Step metadata for rendering.
     */
    public array $steps = [
        1 => ['title' => 'Tienda', 'description' => 'Selecciona la tienda y valida tu ubicación.'],
        2 => ['title' => 'Disponibilidad', 'description' => 'Confirma inventario, ubicaciones y evidencia.'],
        3 => ['title' => 'Calidad', 'description' => 'Evalúa frescura, defectos y adjunta foto.'],
        4 => ['title' => 'Precio', 'description' => 'Registra precio observado, promociones y etiqueta.'],
        5 => ['title' => 'Incidencias', 'description' => 'Reporta incidencias o confirma que todo está en orden.'],
        6 => ['title' => 'Revisión', 'description' => 'Verifica la información y envía la evaluación.'],
    ];

    public ?int $storeId = null;
    public ?float $currentLatitude = null;
    public ?float $currentLongitude = null;
    public ?float $geofenceDistance = null;
    public bool $geofenceChecked = false;
    public bool $geofenceValid = false;

    public array $availabilityRows = [];
    public ?int $qualityRating = null;
    public string $qualityObservations = '';
    public $qualityPhoto;

    public ?float $priceObserved = null;
    public ?float $priceRegular = null;
    public ?float $priceDiscount = null;
    public bool $hasPromotion = false;
    public $pricePhoto;

    public array $incidentCategories = [];
    public string $incidentComments = '';
    public array $incidentPhotos = [];

    public string $reviewNotes = '';

    public array $assignmentOptions = [];
    public array $productOptions = [];
    public array $incidentOptions = [
        'area_exhibicion' => 'Área y exhibición',
        'producto' => 'Producto',
        'stock_precio' => 'Stock / Precio',
        'limpieza' => 'Limpieza',
        'sin_incidencias' => '✅ Sin incidencias',
    ];

    /**
     * Mount component loading catalog data.
     */
    public function mount(): void
    {
        $this->authorize('evaluate');

        $user = Auth::user();

        $this->assignmentOptions = Assignment::query()
            ->with(['store.chain'])
            ->active()
            ->where('user_id', $user->id)
            ->orderByDesc('assigned_at')
            ->get()
            ->map(fn (Assignment $assignment) => [
                'assignment_id' => $assignment->id,
                'store_id' => $assignment->store_id,
                'store_name' => $assignment->store->name,
                'chain_name' => $assignment->store->chain->name ?? null,
                'zone_name' => $assignment->store->zone?->name,
                'store' => $assignment->store,
            ])
            ->toArray();

        $this->productOptions = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku'])
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
            ])
            ->toArray();

        if (empty($this->availabilityRows)) {
            $this->availabilityRows = [
                [
                    'product_id' => null,
                    'location' => '',
                    'status' => 'available',
                    'notes' => '',
                    'photo' => null,
                ],
            ];
        }
    }

    #[On('location-captured')]
    public function captureLocation(array $payload): void
    {
        $this->currentLatitude = isset($payload['latitude']) ? round((float) $payload['latitude'], 6) : null;
        $this->currentLongitude = isset($payload['longitude']) ? round((float) $payload['longitude'], 6) : null;
        $this->geofenceChecked = false;
        $this->geofenceValid = false;
        $this->geofenceDistance = null;
    }

    public function updatedIncidentCategories(): void
    {
        if (in_array('sin_incidencias', $this->incidentCategories, true)) {
            $this->incidentCategories = ['sin_incidencias'];
        }
    }

    public function updatedHasPromotion($value): void
    {
        if (! $value) {
            $this->priceRegular = null;
            $this->priceDiscount = null;
        }
    }

    public function updatedStoreId(): void
    {
        $this->geofenceChecked = false;
        $this->geofenceValid = false;
        $this->geofenceDistance = null;
    }

    public function addAvailabilityRow(): void
    {
        $this->availabilityRows[] = [
            'product_id' => null,
            'location' => '',
            'status' => 'available',
            'notes' => '',
            'photo' => null,
        ];
    }

    public function removeAvailabilityRow(int $index): void
    {
        if (count($this->availabilityRows) === 1) {
            return;
        }

        unset($this->availabilityRows[$index]);
        $this->availabilityRows = array_values($this->availabilityRows);
    }

    public function nextStep(): void
    {
        $this->validateCurrentStep();

        if ($this->currentStep < count($this->steps)) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function verifyGeofence(): void
    {
        $store = $this->selectedStore();

        if (! $store) {
            $this->addError('storeId', 'Selecciona una tienda válida antes de validar la geovalla.');
            return;
        }

        if (! $store->latitude || ! $store->longitude) {
            $this->geofenceChecked = true;
            $this->geofenceValid = true;
            $this->geofenceDistance = null;
            return;
        }

        if ($this->currentLatitude === null || $this->currentLongitude === null) {
            $this->addError('currentLatitude', 'Ingresa o captura tu ubicación para validar la geovalla.');
            return;
        }

        $distance = $this->calculateDistance(
            $store->latitude,
            $store->longitude,
            $this->currentLatitude,
            $this->currentLongitude
        );

        $this->geofenceDistance = round($distance, 2);
        $this->geofenceChecked = true;
        $this->geofenceValid = $distance <= ($store->geofence_radius ?? 800);

        if (! $this->geofenceValid) {
            $this->addError('geofence', "Te encuentras fuera del radio permitido ({$store->geofence_radius} m). Distancia: {$this->geofenceDistance} m.");
        } else {
            $this->resetErrorBag('geofence');
        }
    }

    public function submitEvaluation(): void
    {
        $this->validateCurrentStep();

        DB::transaction(function () {
            $store = $this->selectedStore();

            if (! $store) {
                throw new \RuntimeException('La tienda seleccionada no es válida.');
            }

            $assignment = Assignment::query()
                ->where('user_id', Auth::id())
                ->where('store_id', $store->id)
                ->active()
                ->first();

            $availabilityData = [];
            $photoRecords = Collection::make();

            foreach ($this->availabilityRows as $row) {
                $photoPath = null;
                if (! empty($row['photo'])) {
                    $photoPath = $row['photo']->store('evaluations/availability', 'public');
                    $photoRecords->push([
                        'step' => 'availability',
                        'label' => 'Disponibilidad',
                        'file_path' => $photoPath,
                        'notes' => $row['notes'] ?? null,
                    ]);
                }

                $product = $row['product_id']
                    ? Product::find($row['product_id'])
                    : null;

                $availabilityData[] = [
                    'product_id' => $row['product_id'],
                    'product_name' => $product?->name,
                    'sku' => $product?->sku,
                    'location' => $row['location'],
                    'status' => $row['status'],
                    'notes' => $row['notes'],
                    'photo_path' => $photoPath,
                ];
            }

            $qualityPhotoPath = null;
            if ($this->qualityPhoto) {
                $qualityPhotoPath = $this->qualityPhoto->store('evaluations/quality', 'public');
                $photoRecords->push([
                    'step' => 'quality',
                    'label' => 'Calidad',
                    'file_path' => $qualityPhotoPath,
                    'notes' => $this->qualityObservations,
                ]);
            }

            $pricePhotoPath = null;
            if ($this->pricePhoto) {
                $pricePhotoPath = $this->pricePhoto->store('evaluations/prices', 'public');
                $photoRecords->push([
                    'step' => 'price',
                    'label' => 'Precio',
                    'file_path' => $pricePhotoPath,
                    'notes' => $this->hasPromotion ? 'Precio promocional' : 'Precio regular',
                ]);
            }

            $incidentPhotoPaths = [];
            foreach ($this->incidentPhotos as $photo) {
                if ($photo) {
                    $storedPath = $photo->store('evaluations/incidents', 'public');
                    $incidentPhotoPaths[] = $storedPath;
                    $photoRecords->push([
                        'step' => 'incidents',
                        'label' => 'Incidencia',
                        'file_path' => $storedPath,
                        'notes' => $this->incidentComments,
                    ]);
                }
            }

            $evaluation = Evaluation::create([
                'user_id' => Auth::id(),
                'store_id' => $store->id,
                'chain_id' => $store->chain_id,
                'assignment_id' => $assignment?->id,
                'visited_at' => now(),
                'status' => 'submitted',
                'latitude' => $this->currentLatitude,
                'longitude' => $this->currentLongitude,
                'geofence_valid' => $this->geofenceValid,
                'availability' => $availabilityData,
                'quality_rating' => $this->qualityRating,
                'quality_observations' => $this->qualityObservations ?: null,
                'quality_photo_path' => $qualityPhotoPath,
                'price_observed' => $this->priceObserved,
                'price_regular' => $this->priceRegular,
                'price_discount' => $this->priceDiscount,
                'has_promotion' => $this->hasPromotion,
                'price_photo_path' => $pricePhotoPath,
                'incidents' => [
                    'categories' => $this->incidentCategories,
                    'photos' => $incidentPhotoPaths,
                ],
                'incident_comments' => $this->incidentComments ?: null,
                'review_notes' => $this->reviewNotes ?: null,
                'submitted_at' => now(),
                'submitted_by' => Auth::id(),
            ]);

            $photoRecords->each(function (array $photo) use ($evaluation) {
                EvaluationPhoto::create(array_merge($photo, [
                    'evaluation_id' => $evaluation->id,
                ]));
            });
        });

        session()->flash('status', 'Evaluación registrada con éxito.');

        $this->redirectRoute('evaluations.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.evaluations.create', [
            'assignments' => $this->assignmentOptions,
            'products' => $this->productOptions,
            'selectedStore' => $this->selectedStore(),
        ])->with('pageTitle', 'Nueva visita');
    }

    protected function selectedStore(): ?Store
    {
        if (! $this->storeId) {
            return null;
        }

        static $storeCache = [];

        if (! array_key_exists($this->storeId, $storeCache)) {
            $storeCache[$this->storeId] = Store::with(['chain', 'zone'])->find($this->storeId);
        }

        return $storeCache[$this->storeId];
    }

    protected function validateCurrentStep(): void
    {
        $stepRules = match ($this->currentStep) {
            1 => [
                'storeId' => ['required', Rule::exists('stores', 'id')],
                'currentLatitude' => ['nullable', 'numeric', 'between:-90,90'],
                'currentLongitude' => ['nullable', 'numeric', 'between:-180,180'],
            ],
            2 => [
                'availabilityRows' => ['array', 'min:1'],
                'availabilityRows.*.product_id' => ['nullable', Rule::exists('products', 'id')],
                'availabilityRows.*.location' => ['nullable', 'string', 'max:255'],
                'availabilityRows.*.status' => ['required', Rule::in(['available', 'out_of_stock'])],
                'availabilityRows.*.notes' => ['nullable', 'string', 'max:500'],
                'availabilityRows.*.photo' => ['nullable', 'image', 'max:4096'],
            ],
            3 => [
                'qualityRating' => ['required', 'integer', 'min:1', 'max:5'],
                'qualityObservations' => ['nullable', 'string', 'max:1000'],
                'qualityPhoto' => ['nullable', 'image', 'max:4096'],
            ],
            4 => [
                'priceObserved' => ['required', 'numeric', 'min:0'],
                'priceRegular' => ['nullable', 'numeric', 'min:0'],
                'priceDiscount' => ['nullable', 'numeric', 'min:0'],
                'hasPromotion' => ['boolean'],
                'pricePhoto' => ['nullable', 'image', 'max:4096'],
            ],
            5 => [
                'incidentCategories' => ['array'],
                'incidentCategories.*' => ['string', 'max:80'],
                'incidentComments' => ['nullable', 'string', 'max:1200'],
                'incidentPhotos.*' => ['nullable', 'image', 'max:4096'],
            ],
            6 => [
                'reviewNotes' => ['nullable', 'string', 'max:1200'],
            ],
            default => [],
        };

        $this->validate($stepRules, [
            'availabilityRows.*.photo.image' => 'Cada evidencia de disponibilidad debe ser una imagen.',
            'incidentPhotos.*.image' => 'Las fotos de incidencias deben ser imágenes.',
        ]);

        if ($this->currentStep === 1) {
            $store = $this->selectedStore();
            if ($store && $store->latitude && $store->longitude) {
                if (! $this->geofenceChecked) {
                    throw ValidationException::withMessages([
                        'geofence' => 'Valida tu ubicación antes de continuar.',
                    ]);
                }
                if (! $this->geofenceValid) {
                    throw ValidationException::withMessages([
                        'geofence' => 'Debes encontrarte dentro de la geovalla para continuar.',
                    ]);
                }
            }
        }

        if ($this->currentStep === 4 && $this->hasPromotion) {
            $this->validate([
                'priceRegular' => ['required', 'numeric', 'min:0'],
                'priceDiscount' => ['required', 'numeric', 'min:0'],
            ]);
        }
    }

    protected function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $a = sin($dLat / 2) ** 2 + sin($dLon / 2) ** 2 * cos($lat1) * cos($lat2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
