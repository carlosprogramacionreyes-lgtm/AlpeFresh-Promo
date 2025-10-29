<?php

namespace App\Livewire\Config;

use App\Models\Evaluation;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Configuración · Productos')]
class Products extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';

    public string $statusFilter = '';

    public string $categoryFilter = '';

    public ?int $editingId = null;

    /**
     * @var array{
     *     name:string,
     *     category:string|null,
     *     presentation:string|null,
     *     short_description:string|null,
     *     is_active:bool
     * }
     */
    public array $form = [];

    /**
     * @var array<int, array{id:int,path:string,position:int}>
     */
    public array $existingImages = [];

    /**
     * @var array<string,\Livewire\Features\SupportFileUploads\TemporaryUploadedFile|null>
     */
    public array $uploads = [];

    /**
     * @var list<string>
     */
    public array $imageInputs = [];

    public function mount(): void
    {
        $this->authorize('config-manage');
        $this->resetForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules(), $this->validationMessages());




$payload = [
    'name' => trim($validated['form']['name']),
    'slug' => Str::slug($validated['form']['name']), // 🔹 genera el slug automáticamente
    'category' => $this->nullableValue($validated['form']['category']),
    'presentation' => $this->nullableValue($validated['form']['presentation']),
    'description' => $this->nullableValue($validated['form']['description'] ?? null),
    'is_active' => $validated['form']['is_active'] ? 1 : 0,
];




        if ($this->editingId) {
            $product = Product::with('images')->findOrFail($this->editingId);
            $product->update($payload);

            $this->addNewImages($product);

            $this->dispatch('toast', type: 'success', title: 'Producto actualizado', text: 'Se guardaron los cambios en el producto.');
        } else {
            $product = Product::create($payload);

            $this->addNewImages($product);

            $this->dispatch('toast', type: 'success', title: 'Producto creado', text: 'El producto se registró correctamente.');
        }

        $this->resetForm();
        $this->resetPage(); // Reiniciar la paginación para asegurar el refresco de la tabla
    }

    public function edit(int $productId): void
    {
        $product = Product::with('images')->findOrFail($productId);

        $this->editingId = $product->id;
        $this->form = [
            'name' => $product->name,
            'category' => $product->category,
            'presentation' => $product->presentation,
            
            'is_active' => $product->is_active,
        ];

        $this->existingImages = $product->images
            ->map(fn (ProductImage $image) => [
                'id' => $image->id,
                'path' => $image->path,
                'position' => $image->position,
            ])
            ->all();

        $this->resetUploads();
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function addImageInput(): void
    {
        $this->imageInputs[] = $this->newUploadKey();
    }

    public function removeImageInput(string $key): void
    {
        $index = array_search($key, $this->imageInputs, true);

        if ($index !== false) {
            unset($this->imageInputs[$index]);
            $this->imageInputs = array_values($this->imageInputs);
        }

        unset($this->uploads[$key]);
    }

    public function removeExistingImage(int $imageId): void
    {
        if (! $this->editingId) {
            return;
        }

        $image = ProductImage::query()
            ->where('product_id', $this->editingId)
            ->whereKey($imageId)
            ->first();

        if (! $image) {
            return;
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        $this->refreshExistingImages();

        $this->dispatch('toast', type: 'info', title: 'Imagen eliminada', text: 'La imagen se eliminó del producto.');
    }

    public function delete(int $productId): void
    {
        $product = Product::with('images')->findOrFail($productId);

        if ($this->productHasEvaluations($productId)) {
            if (! $product->is_active) {
                $this->dispatch('toast', type: 'warning', title: 'Producto protegido', text: 'No es posible eliminar un producto que participa en evaluaciones.');

                return;
            }

            $product->update(['is_active' => false]);

            $this->dispatch('toast', type: 'info', title: 'Producto inactivado', text: 'El producto tiene evaluaciones asociadas, por lo que se marcó como inactivo.');

            if ($this->editingId === $productId) {
                $this->resetForm();
            }

            return;
        }

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        if ($this->editingId === $productId) {
            $this->resetForm();
        }

        $this->dispatch('toast', type: 'info', title: 'Producto eliminado', text: 'El producto se eliminó del catálogo.');
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'max:255'],
            'form.category' => ['nullable', 'string', 'max:255'],
            'form.presentation' => ['nullable', 'string', 'max:255'],
            'form.short_description' => ['nullable', 'string', 'max:1000'],
            'form.is_active' => ['required', 'boolean'],
            'uploads.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationMessages(): array
    {
        return [
            'form.name.required' => 'El nombre del producto es obligatorio.',
            'form.name.max' => 'El nombre del producto no puede superar los 255 caracteres.',
            'form.category.max' => 'La categoría no puede superar los 255 caracteres.',
            'form.presentation.max' => 'La presentación no puede superar los 255 caracteres.',
            'form.short_description.max' => 'La descripción breve no puede superar los 1000 caracteres.',
            'uploads.*.image' => 'Cada archivo debe ser una imagen válida.',
            'uploads.*.mimes' => 'Sólo se permiten imágenes en formato JPG, PNG o WEBP.',
            'uploads.*.max' => 'Cada imagen debe pesar menos de 5MB.',
        ];
    }

    public function render()
    {
        return view('livewire.config.products', [
            'products' => $this->products,
            'categories' => $this->availableCategories,
            'pageTitle' => 'Productos',
        ]);
    }

    /**
     * @return LengthAwarePaginator<Product>
     */
    public function getProductsProperty(): LengthAwarePaginator
    {
        $search = trim($this->search);

        return Product::query()
            ->with(['images' => fn ($query) => $query->orderBy('position')])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('presentation', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%');
                });
            })
            ->when($this->statusFilter === 'active', fn ($query) => $query->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($this->categoryFilter !== '', fn ($query) => $query->where('category', $this->categoryFilter))
            ->orderBy('name')
            ->paginate(10);
    }

    /**
     * @return Collection<int, string>
     */
    public function getAvailableCategoriesProperty(): Collection
    {
        return Product::query()
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    private function addNewImages(Product $product): void
    {
        $position = (int) $product->images()->max('position');

        foreach ($this->uploads as $file) {
            if (! $file) {
                continue;
            }

            $position++;

            $path = $file->store('products', 'public');

            $product->images()->create([
                'path' => $path,
                'position' => $position,
            ]);
        }
    }

    private function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'category' => null,
            'presentation' => null,
            'short_description' => null,
            'is_active' => true,
        ];

        $this->editingId = null;
        $this->existingImages = [];

        $this->resetUploads();
        $this->resetValidation();
    }

    private function resetUploads(): void
    {
        $this->uploads = [];
        $this->imageInputs = [$this->newUploadKey()];
    }

    private function newUploadKey(): string
    {
        return Str::uuid()->toString();
    }

    private function refreshExistingImages(): void
    {
        if (! $this->editingId) {
            $this->existingImages = [];

            return;
        }

        $this->existingImages = ProductImage::query()
            ->where('product_id', $this->editingId)
            ->orderBy('position')
            ->get()
            ->map(fn (ProductImage $image) => [
                'id' => $image->id,
                'path' => $image->path,
                'position' => $image->position,
            ])
            ->all();
    }

    private function productHasEvaluations(int $productId): bool
    {
        return Evaluation::query()
            ->whereJsonContains('availability', ['product_id' => $productId])
            ->exists();
    }

    private function nullableValue(?string $value): ?string
    {
        $trimmed = $value !== null ? trim($value) : null;

        return $trimmed === '' ? null : $trimmed;
    }
}
