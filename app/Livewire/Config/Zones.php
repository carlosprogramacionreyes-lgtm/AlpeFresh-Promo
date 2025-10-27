<?php

namespace App\Livewire\Config;

use App\Models\Chain;
use App\Models\Zone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Zonas')]
class Zones extends Component
{
    #[Locked]
    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public ?int $chainId = null;

    /**
     * @return array<string, array<int, string>|string>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'chainId' => ['required', 'integer', 'exists:chains,id'],
        ];
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        $this->ensureChainColumnExists();

        if ($this->editingId) {
            $zone = Zone::findOrFail($this->editingId);
            $zone->fill([
                'name' => $this->name,
                'description' => $this->description,
                'chain_id' => $this->chainId,
            ]);
            $zone->slug = $this->uniqueSlug($this->name, $zone->id);
            $zone->save();

            $this->dispatch('toast', type: 'success', title: 'Zona actualizada', text: 'Se guardaron los cambios correctamente.');
        } else {
            Zone::create([
                'name' => $this->name,
                'slug' => $this->uniqueSlug($this->name),
                'description' => $this->description,
                'chain_id' => $this->chainId,
            ]);

            $this->dispatch('toast', type: 'success', title: 'Zona creada', text: 'Tu nueva zona se registró con éxito.');
        }

        $this->resetForm();
    }

    public function edit(int $zoneId): void
    {
        $zone = Zone::findOrFail($zoneId);

        $this->editingId = $zone->id;
        $this->name = $zone->name;
        $this->description = (string) $zone->description;
        $this->chainId = $zone->chain_id;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function delete(int $zoneId): void
    {
        $zone = Zone::findOrFail($zoneId);
        $zone->delete();

        $this->dispatch('toast', type: 'info', title: 'Zona eliminada', text: 'La zona se eliminó del directorio.');

        if ($this->editingId === $zoneId) {
            $this->resetForm();
        }
    }

    public function getZonesProperty(): Collection
    {
        return Zone::with('chain')->latest()->get();
    }

    public function getChainOptionsProperty(): Collection
    {
        return Chain::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.config.zones', [
            'zones' => $this->zones,
            'chains' => $this->chainOptions,
            'pageTitle' => 'Zonas',
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'description']);
        $this->chainId = null;
        $this->editingId = null;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'zona';
        }

        $slug = $baseSlug;
        $suffix = 1;

        while (
            Zone::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function ensureChainColumnExists(): void
    {
        if (! Schema::hasColumn('zones', 'chain_id')) {
            throw ValidationException::withMessages([
                'chainId' => 'La tabla de zonas aún no cuenta con la columna chain_id. Ejecuta las migraciones pendientes antes de crear zonas nuevas.',
            ]);
        }
    }
}
