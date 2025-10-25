<?php

namespace App\Livewire\Config;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Configuración · Usuarios')]
class Users extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public string $roleFilter = '';
    public string $statusFilter = '';

    public ?int $editingUserId = null;

    public array $form = [];

    public function mount(): void
    {
        $this->authorize('config-manage');
        $this->resetForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openEditForm(int $userId): void
    {
        $this->authorize('config-manage');

        $user = User::findOrFail($userId);

        $this->editingUserId = $user->id;
        $this->form = [
            'nombre_completo' => $user->nombre_completo,
            'email' => $user->email,
            'username' => $user->username,
            'rol' => $user->role->value,
            'password' => '',
        ];

        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->resetForCreation();
    }

    protected function resetForCreation(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->resetForm();
        $this->search = '';
        $this->roleFilter = '';
        $this->statusFilter = '';
    }

    public function toggleActivation(int $userId): void
    {
        $this->authorize('config-manage');

        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            $this->addError('activation', 'No puedes desactivar tu propia cuenta.');

            return;
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        session()->flash('status', 'Estado de usuario actualizado.');
    }

    public function save(): void
    {
        $this->authorize('config-manage');

        Log::info('Livewire config.users save triggered', [
            'acting_user_id' => auth()->id(),
            'editing_user_id' => $this->editingUserId,
            'payload_preview' => [
                'nombre_completo' => $this->form['nombre_completo'] ?? null,
                'username' => $this->form['username'] ?? null,
                'email' => $this->form['email'] ?? null,
                'rol' => $this->form['rol'] ?? null,
                'has_password' => ! empty($this->form['password']),
            ],
        ]);

        $validated = $this->validate($this->rules(), $this->messages());

        $payload = [
            'full_name' => $validated['form']['nombre_completo'],
            'email' => $validated['form']['email'] ?: null,
            'username' => $validated['form']['username'],
            'role' => $validated['form']['rol'],
        ];

        if (! $this->editingUserId || ! empty($validated['form']['password'])) {
            $payload['password'] = $validated['form']['password'];
        }

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $user->update($payload);

            Log::info('Livewire config.users user updated', [
                'editing_user_id' => $this->editingUserId,
                'updated_by' => auth()->id(),
            ]);

            session()->flash('status', 'Usuario actualizado correctamente.');
        } else {
            $payload['is_active'] = true;

            $created = User::create($payload);

            Log::info('Livewire config.users user created', [
                'created_user_id' => $created->id,
                'created_by' => auth()->id(),
            ]);

            session()->flash('status', 'Usuario creado correctamente.');
        }

        $this->resetForCreation();
        $this->resetPage();
    }

    public function getRoleOptionsProperty(): array
    {
        return collect(UserRole::cases())->map(function (UserRole $role) {
            return [
                'value' => $role->value,
                'label' => $role->label(),
            ];
        })->toArray();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search !== '', function ($query) {
                $term = '%' . mb_strtolower($this->search) . '%';

                $query->where(function ($subQuery) use ($term) {
                    $subQuery->whereRaw('LOWER(full_name) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(username) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$term])
                        ->orWhereRaw('LOWER(phone) LIKE ?', [$term]);
                });
            })
            ->when($this->roleFilter !== '', fn ($query) => $query->where('role', $this->roleFilter))
            ->when($this->statusFilter === 'active', fn ($query) => $query->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.config.users', [
            'users' => $users,
            'roleOptions' => $this->roleOptions,
        ])->with('pageTitle', 'Usuarios');
    }

    protected function rules(): array
    {
        $rules = [
            'form.nombre_completo' => ['required', 'string', 'max:255'],
            'form.email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingUserId)],
            'form.username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($this->editingUserId)],
            'form.rol' => ['required', new EnumRule(UserRole::class)],
        ];

        if ($this->editingUserId) {
            $rules['form.password'] = ['nullable', 'string', 'min:8'];
        } else {
            $rules['form.password'] = ['required', 'string', 'min:8'];
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'form.nombre_completo.required' => 'Ingresa el nombre completo.',
            'form.username.required' => 'Ingresa un nombre de usuario.',
            'form.username.unique' => 'Este nombre de usuario ya está en uso.',
            'form.email.email' => 'Ingresa un correo válido.',
            'form.email.unique' => 'Este correo ya está registrado.',
            'form.rol.required' => 'Selecciona un rol para el usuario.',
            'form.password.required' => 'Define una contraseña temporal.',
            'form.password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }

    protected function resetForm(): void
    {
        $this->form = [
            'nombre_completo' => '',
            'email' => '',
            'username' => '',
            'rol' => UserRole::Promotor->value,
            'password' => '',
        ];
        $this->editingUserId = null;
    }
}
