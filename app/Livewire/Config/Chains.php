<?php

namespace App\Livewire\Config;

use App\Models\Chain;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Configuración · Cadenas')]
class Chains extends Component
{
    #[Locked]
    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    /**
     * @return array<string, array<int, string>|string>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            $chain = Chain::findOrFail($this->editingId);
            $chain->fill([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $chain->slug = $this->uniqueSlug($this->name, $chain->id);
            $chain->save();

            $this->dispatch('toast', type: 'success', title: 'Cadena actualizada', text: 'Se guardaron los cambios correctamente.');
        } else {
            Chain::create([
                'name' => $this->name,
                'slug' => $this->uniqueSlug($this->name),
                'description' => $this->description,
            ]);

            $this->dispatch('toast', type: 'success', title: 'Cadena creada', text: 'Tu nueva cadena se registró con éxito.');
        }

        $this->resetForm();
    }

    public function edit(int $chainId): void
    {
        $chain = Chain::findOrFail($chainId);

        $this->editingId = $chain->id;
        $this->name = $chain->name;
        $this->description = (string) $chain->description;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function delete(int $chainId): void
    {
        $chain = Chain::findOrFail($chainId);
        $chain->delete();

        $this->dispatch('toast', type: 'info', title: 'Cadena eliminada', text: 'La cadena se eliminó del directorio.');

        // If we deleted the one currently being edited, reset form.
        if ($this->editingId === $chainId) {
            $this->resetForm();
        }
    }

    public function getChainsProperty(): Collection
    {
        return Chain::latest()->get();
    }

    public function render()
    {
        return view('livewire.config.chains', [
            'chains' => $this->chains,
            'pageTitle' => 'Cadenas',
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'description']);
        $this->editingId = null;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'cadena';
        }

        $slug = $baseSlug;
        $suffix = 1;

        while (
            Chain::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
