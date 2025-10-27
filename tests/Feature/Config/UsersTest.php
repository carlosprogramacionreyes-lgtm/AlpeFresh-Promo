<?php

namespace Tests\Feature\Config;

use App\Enums\UserRole;
use App\Livewire\Config\Users as UsersComponent;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_users_via_livewire_component(): void
    {
        $admin = User::create([
            'full_name' => 'Admin Example',
            'username' => 'admin-example',
            'email' => 'admin@example.com',
            'role' => UserRole::Admin,
            'is_active' => true,
            'password' => 'secret-admin',
        ]);

        $this->actingAs($admin);

        Livewire::test(UsersComponent::class)
            ->set('form.nombre_completo', 'Nuevo Usuario')
            ->set('form.email', 'nuevo@example.com')
            ->set('form.username', 'nuevo-usuario')
            ->set('form.rol', UserRole::Promotor->value)
            ->set('form.password', 'temporal123')
            ->call('save')
            ->assertHasNoErrors();

        $created = User::where('username', 'nuevo-usuario')->first();

        $this->assertNotNull($created, 'Se esperaba que el usuario fuera creado en la base de datos.');

        $this->assertSame('Nuevo Usuario', $created->full_name);
        $this->assertSame('nuevo@example.com', $created->email);
        $this->assertTrue($created->is_active);
        $this->assertSame(UserRole::Promotor, $created->role);
        $this->assertTrue(Hash::check('temporal123', $created->password));
    }

    public function test_admin_can_delete_users_via_livewire_component(): void
    {
        $admin = User::create([
            'full_name' => 'Admin Eliminador',
            'username' => 'admin-delete',
            'email' => 'admin-delete@example.com',
            'role' => UserRole::Admin,
            'is_active' => true,
            'password' => 'secret-admin',
        ]);

        $userToDelete = User::create([
            'full_name' => 'Usuario A Eliminar',
            'username' => 'usuario-eliminar',
            'email' => 'usuario-eliminar@example.com',
            'role' => UserRole::Promotor,
            'is_active' => true,
            'password' => 'usuario-eliminar',
        ]);

        $this->actingAs($admin);

        Livewire::test(UsersComponent::class)
            ->call('delete', $userToDelete->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    public function test_supervisor_cannot_delete_users(): void
    {
        $supervisor = User::create([
            'full_name' => 'Supervisor Ejemplo',
            'username' => 'supervisor-ejemplo',
            'email' => 'supervisor@example.com',
            'role' => UserRole::Supervisor,
            'is_active' => true,
            'password' => 'secret-supervisor',
        ]);

        $userToDelete = User::create([
            'full_name' => 'Usuario Protegido',
            'username' => 'usuario-protegido',
            'email' => 'usuario-protegido@example.com',
            'role' => UserRole::Promotor,
            'is_active' => true,
            'password' => 'usuario-protegido',
        ]);

        $this->actingAs($supervisor);

        try {
            Livewire::test(UsersComponent::class)->call('delete', $userToDelete->id);
            $this->fail('Se esperaba una excepción de autorización para supervisores al eliminar usuarios.');
        } catch (AuthorizationException $exception) {
            $this->assertDatabaseHas('users', ['id' => $userToDelete->id]);
        }
    }

    public function test_supervisor_cannot_edit_admin_profiles(): void
    {
        $admin = User::create([
            'full_name' => 'Admin Protegido',
            'username' => 'admin-protegido',
            'email' => 'admin-protegido@example.com',
            'role' => UserRole::Admin,
            'is_active' => true,
            'password' => 'secret-admin',
        ]);

        $supervisor = User::create([
            'full_name' => 'Supervisor Intento',
            'username' => 'supervisor-intento',
            'email' => 'supervisor-intento@example.com',
            'role' => UserRole::Supervisor,
            'is_active' => true,
            'password' => 'secret-supervisor',
        ]);

        $this->actingAs($supervisor);

        Livewire::test(UsersComponent::class)
            ->call('openEditForm', $admin->id)
            ->assertHasErrors(['authorization'])
            ->assertSee('No tienes permiso para editar este usuario.')
            ->assertSet('editingUserId', null);

        Livewire::test(UsersComponent::class)
            ->set('editingUserId', $admin->id)
            ->call('save')
            ->assertHasErrors(['authorization'])
            ->assertSee('No tienes permiso para editar este usuario.');
    }

    public function test_admin_can_toggle_user_activation(): void
    {
        $admin = User::create([
            'full_name' => 'Admin Activador',
            'username' => 'admin-activador',
            'email' => 'admin-activador@example.com',
            'role' => UserRole::Admin,
            'is_active' => true,
            'password' => 'secret-admin',
        ]);

        $user = User::create([
            'full_name' => 'Supervisor Objetivo',
            'username' => 'supervisor-objetivo',
            'email' => 'supervisor-objetivo@example.com',
            'role' => UserRole::Supervisor,
            'is_active' => true,
            'password' => 'secret-supervisor',
        ]);

        $this->actingAs($admin);

        Livewire::test(UsersComponent::class)
            ->call('toggleActivation', $user->id);

        $this->assertFalse($user->fresh()->is_active);

        Livewire::test(UsersComponent::class)
            ->call('toggleActivation', $user->id);

        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_supervisor_cannot_toggle_user_activation(): void
    {
        $supervisor = User::create([
            'full_name' => 'Supervisor Sin Permiso',
            'username' => 'supervisor-sin-permiso',
            'email' => 'supervisor-sin-permiso@example.com',
            'role' => UserRole::Supervisor,
            'is_active' => true,
            'password' => 'secret-supervisor',
        ]);

        $promotor = User::create([
            'full_name' => 'Promotor Activo',
            'username' => 'promotor-activo',
            'email' => 'promotor-activo@example.com',
            'role' => UserRole::Promotor,
            'is_active' => true,
            'password' => 'secret-promotor',
        ]);

        $this->actingAs($supervisor);

        Livewire::test(UsersComponent::class)
            ->call('toggleActivation', $promotor->id)
            ->assertHasErrors(['activation'])
            ->assertSee('No tienes permiso para cambiar el estado de este usuario.');

        $this->assertTrue($promotor->fresh()->is_active);
    }
}
