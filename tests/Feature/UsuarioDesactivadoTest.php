<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_admin_puede_crear_un_usuario()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post('/usuarios', [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => 'password123',
                'estado' => 'activo',
            ])
            ->assertRedirect('/usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
            'estado' => 'activo',
        ]);
    }

    /** @test */
    public function un_usuario_invitado_no_puede_crear_un_usuario()
    {
        $this->post('/usuarios', [
            'name' => 'Invitado',
            'email' => 'invitado@example.com',
            'password' => 'password123',
            'estado' => 'activo',
        ])->assertRedirect('/login'); // porque no está autenticado
    }

    /** @test */
    public function un_admin_puede_editar_un_usuario()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->put("/usuarios/{$user->id}", [
                'name' => 'Nuevo Nombre',
                'estado' => 'inactivo',
            ])
            ->assertRedirect('/usuarios');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nuevo Nombre',
            'estado' => 'inactivo',
        ]);
    }

    /** @test */
    public function un_admin_puede_eliminar_un_usuario()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->delete("/usuarios/{$user->id}")
            ->assertRedirect('/usuarios');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
