<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mascota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MascotaCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar los seeders de usuario y mascota
        $this->seed(\Database\Seeders\UsuarioSeeder::class);
        $this->seed(\Database\Seeders\MascotaSeeder::class);
    }

    public function test_usuario_autenticado_puede_ver_lista_de_mascotas()
    {
        $usuario = User::where('email', 'paco@example.com')->first();
        $response = $this->actingAs($usuario)->get('/mascotas');
        $response->assertStatus(200, 'No se pudo acceder al listado de mascotas.');
        $response->assertSee('Mis mascotas');
    }

    public function test_usuario_autenticado_puede_crear_una_mascota()
    {
        $usuario = User::where('email', 'paco@example.com')->first();
        $datos = [
            'nombre' => 'Firulais',
            'user_id' => $usuario->id,
            'especie' => 'Perro',
            'raza' => 'Labrador',
            'sexo' => 'M',
            'fecha_nacimiento' => '2020-01-01',
            'notas' => 'Muy juguetón',
            'condiciones' => 'on',
        ];
        $response = $this->actingAs($usuario)->post('/mascotas', $datos);
        $response->assertRedirect('/mascotas');
        $this->assertDatabaseHas('mascotas', [
            'nombre' => 'Firulais',
            'user_id' => $usuario->id,
        ], 'La mascota no fue creada correctamente en la base de datos.');
    }

    public function test_usuario_autenticado_puede_ver_una_mascota()
    {
        $usuario = User::where('email', 'paco@example.com')->first();
        $mascota = Mascota::where('user_id', $usuario->id)->first();
        $hashid = $mascota->hashid;
        $response = $this->actingAs($usuario)->get('/mascotas/' . $hashid);
        $response->assertStatus(200, 'No se pudo acceder a la vista de la mascota.');
        $response->assertSee($mascota->nombre);
    }

    public function test_usuario_autenticado_puede_actualizar_una_mascota()
    {
        $usuario = User::where('email', 'paco@example.com')->first();
        $mascota = Mascota::where('user_id', $usuario->id)->first();
        $hashid = $mascota->hashid;
        $nuevosDatos = [
            'nombre' => 'NuevoNombre',
            'especie' => 'Perro',
            'raza' => 'Labrador',
            'sexo' => 'M',
            'fecha_nacimiento' => '2020-01-01',
            'notas' => 'Actualizado',
            'nombre_usuario' => $usuario->name,
        ];
        $response = $this->actingAs($usuario)->put('/mascotas/' . $hashid, $nuevosDatos);
        $response->assertRedirect('/mascotas');
        $this->assertDatabaseHas('mascotas', [
            'nombre' => 'NuevoNombre',
            'user_id' => $usuario->id,
        ], 'La mascota no fue actualizada correctamente en la base de datos.');
    }

    public function test_usuario_autenticado_puede_eliminar_una_mascota()
    {
        $usuario = User::where('email', 'paco@example.com')->first();
        $mascota = Mascota::where('user_id', $usuario->id)->first();
        $hashid = $mascota->hashid;
        $response = $this->actingAs($usuario)->delete('/mascotas/' . $hashid);
        $response->assertRedirect('/mascotas');
        $this->assertDatabaseMissing('mascotas', [
            'id' => $mascota->id,
        ], 'La mascota no fue eliminada correctamente de la base de datos.');
    }
} 