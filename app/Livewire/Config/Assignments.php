<?php

namespace App\Livewire\Config;

use App\Enums\UserRole;
use App\Models\Assignment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Asignaciones')]
class Assignments extends Component
{
    public bool $showModal = false;

    public int $modalStep = 1;

    public ?int $selectedPromoterId = null;

    /**
     * @var array<int, int>
     */
    public array $selectedStoreIds = [];

    public string $promoterSearch = '';

    public string $storeSearch = '';

    /**
     * Reset wizard state and open modal without preselection.
     */
    public function openModal(): void
    {
        $this->resetWizard();
        $this->showModal = true;
    }

    /**
     * Open wizard pre-selecting a promoter to edit their assignments.
     */
    public function editPromoterAssignments(int $promoterId): void
    {
        $this->resetWizard();

        $this->selectedPromoterId = $promoterId;
        $this->selectedStoreIds = $this->promoterStoreIds($promoterId);
        $this->modalStep = 2;
        $this->showModal = true;
    }

    /**
     * Close modal and clean state.
     */
    public function closeModal(): void
    {
        $this->resetWizard();
        $this->showModal = false;
    }

    /**
     * Move wizard to next step ensuring requirements are met.
     */
    public function nextStep(): void
    {
        if ($this->modalStep === 1) {
            $this->validatePromoterSelection();

            $this->selectedStoreIds = $this->promoterStoreIds($this->selectedPromoterId);
            $this->modalStep = 2;
        }
    }

    /**
     * Return to a previous step.
     */
    public function goToStep(int $step): void
    {
        $this->modalStep = max(1, min($step, 2));
    }

    /**
     * Select or deselect a store checkbox.
     */
    public function toggleStoreSelection(int $storeId): void
    {
        if (in_array($storeId, $this->selectedStoreIds, true)) {
            $this->selectedStoreIds = array_values(array_diff($this->selectedStoreIds, [$storeId]));
        } else {
            $this->selectedStoreIds[] = $storeId;
        }
    }

    /**
     * Select every available store.
     */
    public function selectAllStores(): void
    {
        $this->selectedStoreIds = $this->storeOptions()->pluck('id')->all();
    }

    /**
     * Remove all store selections.
     */
    public function clearStoreSelection(): void
    {
        $this->selectedStoreIds = [];
    }

    /**
     * Persist assignments for the chosen promoter.
     */
    public function saveAssignments(): void
    {
        $this->validateAssignmentPayload();

        $promoter = User::query()
            ->where('role', UserRole::Promotor)
            ->findOrFail($this->selectedPromoterId);

        $selectedIds = collect($this->selectedStoreIds)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $this->selectedStoreIds = $selectedIds;

        $current = $promoter->stores()->pluck('store_id')->map(fn ($id) => (int) $id)->all();

        $toAttach = array_values(array_diff($selectedIds, $current));
        $toDetach = array_values(array_diff($current, $selectedIds));

        if (! empty($toAttach)) {
            foreach ($toAttach as $storeId) {
                Assignment::create([
                    'user_id' => $promoter->id,
                    'store_id' => $storeId,
                    'assigned_by' => Auth::id(),
                    'assigned_at' => now(),
                ]);
            }
        }

        if (! empty($toDetach)) {
            Assignment::query()
                ->where('user_id', $promoter->id)
                ->whereIn('store_id', $toDetach)
                ->delete();
        }

        $this->dispatch(
            'toast',
            type: 'success',
            title: 'Asignaciones guardadas',
            text: 'Actualizaste la cobertura del promotor.'
        );

        $this->closeModal();
    }

    /**
     * Delete a single assignment from the listing.
     */
    public function removeAssignment(int $assignmentId): void
    {
        Assignment::findOrFail($assignmentId)->delete();

        $this->dispatch(
            'toast',
            type: 'info',
            title: 'Asignación eliminada',
            text: 'El promotor ya no verá la tienda en su app.'
        );
    }

    /**
     * Get promoters filtered by search term.
     */
    public function getPromotersProperty(): Collection
    {
        return User::query()
            ->where('role', UserRole::Promotor)
            ->where('is_active', true)
            ->when($this->promoterSearch !== '', function ($query) {
                $term = '%' . trim($this->promoterSearch) . '%';

                $query->where(function ($inner) use ($term) {
                    $inner->where('full_name', 'like', $term)
                        ->orWhere('username', 'like', $term);
                });
            })
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'username']);
    }

    /**
     * Get stores filtered by search term.
     */
    public function getStoresProperty(): Collection
    {
        return Store::query()
            ->where('is_active', true)
            ->with(['chain:id,name', 'zone:id,name'])
            ->when($this->storeSearch !== '', function ($query) {
                $term = '%' . trim($this->storeSearch) . '%';

                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('city', 'like', $term);
                });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'city', 'chain_id', 'zone_id']);
    }

    /**
     * Build current assignment map for rendering in the list.
     */
    public function getAssignmentGroupsProperty(): Collection
    {
        return User::query()
            ->where('role', UserRole::Promotor)
            ->with(['assignments.store.chain', 'assignments.store.zone'])
            ->orderBy('full_name')
            ->get()
            ->map(function (User $promoter) {
                return [
                    'id' => $promoter->id,
                    'name' => $promoter->full_name,
                    'username' => $promoter->username,
                    'assignments' => $promoter->assignments->sortBy('store.name')->map(function (Assignment $assignment) {
                        return [
                            'id' => $assignment->id,
                            'store_name' => $assignment->store?->name,
                            'chain_name' => $assignment->store?->chain?->name,
                            'zone_name' => $assignment->store?->zone?->name,
                            'assigned_at' => optional($assignment->assigned_at)->format('d/m/Y'),
                        ];
                    })->values()->all(),
                ];
            })->filter(fn (array $group) => ! empty($group['assignments']));
    }

    public function render()
    {
        return view('livewire.config.assignments', [
            'pageTitle' => 'Asignaciones',
            'assignments' => $this->assignmentGroups,
            'promoters' => $this->promoters,
            'stores' => $this->storeOptions(),
        ]);
    }

    /**
     * Returns store options without Livewire attribute casting that may lose relation data.
     */
    protected function storeOptions(): Collection
    {
        return $this->stores->map(function (Store $store) {
            return [
                'id' => $store->id,
                'name' => $store->name,
                'city' => $store->city,
                'chain' => $store->chain?->name,
                'zone' => $store->zone?->name,
            ];
        });
    }

    /**
     * Fetch store ids currently assigned to a promoter.
     *
     * @return array<int, int>
     */
    protected function promoterStoreIds(?int $promoterId): array
    {
        if (! $promoterId) {
            return [];
        }

        return Assignment::query()
            ->where('user_id', $promoterId)
            ->pluck('store_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    protected function resetWizard(): void
    {
        $this->reset([
            'modalStep',
            'selectedPromoterId',
            'selectedStoreIds',
            'promoterSearch',
            'storeSearch',
        ]);

        $this->modalStep = 1;
        $this->selectedStoreIds = [];
    }

    protected function validatePromoterSelection(): void
    {
        $this->validate([
            'selectedPromoterId' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Promotor->value)),
            ],
        ], [
            'selectedPromoterId.required' => 'Selecciona un promotor.',
            'selectedPromoterId.exists' => 'El promotor seleccionado no es válido.',
        ]);
    }

    protected function validateAssignmentPayload(): void
    {
        $this->validate([
            'selectedPromoterId' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Promotor->value)),
            ],
            'selectedStoreIds' => ['required', 'array', 'min:1'],
            'selectedStoreIds.*' => ['integer', 'distinct', Rule::exists('stores', 'id')],
        ], [
            'selectedPromoterId.required' => 'Selecciona un promotor.',
            'selectedStoreIds.required' => 'Selecciona al menos una tienda.',
            'selectedStoreIds.min' => 'Selecciona al menos una tienda.',
        ]);
    }
}
