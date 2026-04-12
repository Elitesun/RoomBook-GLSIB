<?php

namespace Tests\Feature\Admin;

use App\Models\Equipment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCreateCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_room(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.rooms.store'), [
            'name' => 'Salle Test 401',
            'capacity' => 45,
            'building' => 'Bâtiment Test',
            'is_available' => '1',
        ]);

        $response->assertRedirect(route('admin.rooms.index'));

        $this->assertDatabaseHas('rooms', [
            'name' => 'Salle Test 401',
            'capacity' => 45,
            'building' => 'Bâtiment Test',
            'is_available' => 1,
        ]);
    }

    public function test_admin_can_create_equipment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.equipment.store'), [
            'name' => 'Caméra',
            'quantity' => 4,
            'is_available' => '1',
        ]);

        $response->assertRedirect(route('admin.equipment.index'));

        $this->assertDatabaseHas('equipment', [
            'name' => 'Caméra',
            'quantity' => 4,
            'is_available' => 1,
        ]);
    }

    public function test_admin_can_create_user_with_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Nouveau Responsable',
            'email' => 'new.responsable@roombook.tg',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'responsable',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Nouveau Responsable',
            'email' => 'new.responsable@roombook.tg',
            'role' => 'responsable',
        ]);
    }

    public function test_non_admin_cannot_create_user(): void
    {
        $enseignant = User::factory()->create(['role' => 'enseignant']);

        $response = $this->actingAs($enseignant)->post(route('admin.users.store'), [
            'name' => 'No Access',
            'email' => 'noaccess@roombook.tg',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'enseignant',
        ]);

        $response->assertForbidden();
    }

    public function test_invalid_password_does_not_create_user_and_retry_with_same_email_succeeds(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $invalidResponse = $this->from(route('admin.users.create'))
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Validation Flow',
                'email' => 'validation.flow@roombook.tg',
                'password' => 'abcd',
                'password_confirmation' => 'abcd',
                'role' => 'responsable',
            ]);

        $invalidResponse
            ->assertRedirect(route('admin.users.create'))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', [
            'email' => 'validation.flow@roombook.tg',
        ]);

        $validResponse = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Validation Flow',
            'email' => 'validation.flow@roombook.tg',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'responsable',
        ]);

        $validResponse->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Validation Flow',
            'email' => 'validation.flow@roombook.tg',
            'role' => 'responsable',
        ]);
    }
}
