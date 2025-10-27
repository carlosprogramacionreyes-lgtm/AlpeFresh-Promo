<?php

namespace App\Livewire\Config;

use App\Models\Chain;
use App\Models\Store;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Tiendas')]
class Stores extends Component
{
    #[Locked]
    public ?int $editingId = null;

    public ?int $chainId = null;

    public ?int $zoneId = null;

    public string $name = '';

    public ?string $city = null;

    public ?string $address = null;

    public ?string $latitude = null;

    public ?string $longitude = null;

    public int $geofenceRadius = 50;

    public bool $isActive = true;

    /**
     * @return array<string, array<int, mixed>|mixed>
     */
    protected function rules(): array
    {
        return [
            'chainId' => ['required', 'integer', 'exists:chains,id'],
            'zoneId' => [
                'required',
                'integer',
                Rule::exists('zones', 'id')->where(fn ($query) => $query->where('chain_id', $this->chainId ?? 0)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'geofenceRadius' => ['required', 'integer', 'min:10', 'max:1000'],
            'isActive' => ['boolean'],
        ];
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatedChainId(): void
    {
        $this->chainId = $this->chainId ? (int) $this->chainId : null;

        if ($this->editingId === null) {
            $this->zoneId = null;
        } elseif ($this->editingId !== null && $this->zoneId !== null) {
            $zone = Zone::find($this->zoneId);
            if (! $zone || $zone->chain_id !== $this->chainId) {
                $this->zoneId = null;
            }
        }
    }

    public function updatedZoneId(): void
    {
        $this->zoneId = $this->zoneId ? (int) $this->zoneId : null;
    }

    public function save(): void
    {
        $this->validate();

        $this->ensureSchemaRequirements();

        if ($this->editingId) {
            $store = Store::findOrFail($this->editingId);
            $store->fill($this->payload());
            $store->slug = $this->uniqueSlug($this->name, $store->id);
            $store->save();

            $this->dispatch('toast', type: 'success', title: 'Tienda actualizada', text: 'Se actualizaron los datos de la tienda.');
        } else {
            Store::create($this->payload() + [
                'slug' => $this->uniqueSlug($this->name),
            ]);

            $this->dispatch('toast', type: 'success', title: 'Tienda creada', text: 'Registraste una nueva tienda.');
        }

        $this->resetForm();
    }

    public function edit(int $storeId): void
    {
        $store = Store::findOrFail($storeId);

        $this->editingId = $store->id;
        $this->chainId = $store->chain_id;
        $this->zoneId = $store->zone_id;
        $this->name = $store->name;
        $this->city = $store->city;
        $this->address = $store->address;
        $this->latitude = $store->latitude !== null ? (string) $store->latitude : null;
        $this->longitude = $store->longitude !== null ? (string) $store->longitude : null;
        $this->geofenceRadius = $store->geofence_radius ?? 50;
        $this->isActive = (bool) $store->is_active;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function delete(int $storeId): void
    {
        $store = Store::findOrFail($storeId);
        $store->delete();

        $this->dispatch('toast', type: 'info', title: 'Tienda eliminada', text: 'La tienda se eliminó correctamente.');

        if ($this->editingId === $storeId) {
            $this->resetForm();
        }
    }

    public function getChainsProperty(): Collection
    {
        return Chain::orderBy('name')->get();
    }

    public function getZonesProperty(): EloquentCollection
    {
        if (! $this->chainId) {
            return Zone::whereRaw('1 = 0')->get();
        }

        return Zone::where('chain_id', $this->chainId)->orderBy('name')->get();
    }

    public function getStoresProperty(): Collection
    {
        return Store::with(['chain', 'zone'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.config.stores', [
            'chains' => $this->chains,
            'zonesOptions' => $this->zones,
            'stores' => $this->stores,
            'pageTitle' => 'Tiendas',
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'chainId',
            'zoneId',
            'name',
            'city',
            'address',
            'latitude',
            'longitude',
        ]);

        $this->geofenceRadius = 50;
        $this->isActive = true;
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'chain_id' => $this->chainId ? (int) $this->chainId : null,
            'zone_id' => $this->zoneId ? (int) $this->zoneId : null,
            'name' => $this->name,
            'city' => $this->city,
            'address' => $this->address,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'geofence_radius' => $this->geofenceRadius,
            'is_active' => (bool) $this->isActive,
        ];
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'tienda';
        }

        $slug = $baseSlug;
        $suffix = 1;

        while (
            Store::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function ensureSchemaRequirements(): void
    {
        foreach (['chain_id', 'zone_id', 'geofence_radius', 'is_active'] as $column) {
            if (! Schema::hasColumn('stores', $column)) {
                throw ValidationException::withMessages([
                    'name' => "La tabla de tiendas no tiene la columna requerida {$column}. Ejecuta las migraciones pendientes.",
                ]);
            }
        }
    }
}
