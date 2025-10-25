<?php

namespace Tests\Feature\Config;

use App\Enums\UserRole;
use App\Livewire\Config\Users as UsersComponent;
use App\Models\User;
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
}
