<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use TiMacDonald\Log\LogEntry;
use TiMacDonald\Log\LogFake;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $staff;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'nama_lengkap' => 'Administrator',
            'password' => 'secret123',
        ]);
        $this->admin->role = 'admin';
        $this->admin->save();

        $this->staff = User::create([
            'username' => 'staff',
            'nama_lengkap' => 'Staff Toko',
            'password' => 'secret123',
        ]);
        $this->staff->role = 'staff';
        $this->staff->save();

        $this->customer = User::create([
            'username' => 'customer',
            'nama_lengkap' => 'Pelanggan Toko',
            'password' => 'secret123',
        ]);
    }

    public function test_ac_01_admin_can_change_customer_to_staff_and_triggers_observer(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with('user.role.changed', [
                'target_user_id' => $this->customer->id,
                'old_role' => 'customer',
                'new_role' => 'staff',
                'actor_id' => $this->admin->id,
            ]);

        $response = $this->actingAs($this->admin)->patch(
            route('admin.users.role.update', $this->customer),
            ['role' => 'staff']
        );

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Role diperbarui.');
        $this->assertEquals('staff', $this->customer->fresh()->role);
    }

    public function test_ac_02_customer_cannot_patch_role_directly(): void
    {
        Log::shouldReceive('info')->never();

        $response = $this->actingAs($this->customer)->patch(
            route('admin.users.role.update', $this->customer),
            ['role' => 'staff']
        );

        $response->assertStatus(403);
        $this->assertEquals('customer', $this->customer->fresh()->role);
    }

    public function test_ac_03_admin_cannot_change_own_role(): void
    {
        Log::shouldReceive('info')->never();

        $response = $this->actingAs($this->admin)->patch(
            route('admin.users.role.update', $this->admin),
            ['role' => 'staff']
        );

        $response->assertSessionHasErrors('role');
        $this->assertEquals('admin', $this->admin->fresh()->role);
    }

    public function test_ac_04_admin_cannot_send_invalid_role(): void
    {
        Log::shouldReceive('info')->never();

        $response = $this->actingAs($this->admin)->patch(
            route('admin.users.role.update', $this->customer),
            ['role' => 'superadmin']
        );

        $response->assertSessionHasErrors('role');
        $this->assertEquals('customer', $this->customer->fresh()->role);
    }

    public function test_ac_05_admin_sending_same_role_does_not_save_nor_log(): void
    {
        Log::shouldReceive('info')->never();

        $response = $this->actingAs($this->admin)->patch(
            route('admin.users.role.update', $this->customer),
            ['role' => 'customer']
        );

        $response->assertRedirect();
        $this->assertEquals('customer', $this->customer->fresh()->role);
    }

    public function test_ac_06_staff_cannot_call_stateless_cart_add(): void
    {
        $response = $this->actingAs($this->staff)->postJson(route('cart.add'), [
            'cart' => [],
            'product_id' => 1,
        ]);

        $response->assertStatus(403);
    }

    public function test_customer_can_call_stateless_cart_add(): void
    {
        $response = $this->actingAs($this->customer)->postJson(route('cart.add'), [
            'cart' => [],
            'product_id' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['items', 'total']);
    }

    public function test_guest_cannot_call_stateless_cart_add(): void
    {
        $response = $this->postJson(route('cart.add'), [
            'cart' => [],
            'product_id' => 1,
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_cannot_change_another_admin_account(): void
    {
        $secondAdmin = User::create([
            'username' => 'admin2',
            'nama_lengkap' => 'Admin Kedua',
            'password' => 'secret123',
        ]);
        $secondAdmin->role = 'admin';
        $secondAdmin->save();

        Log::shouldReceive('info')->never();

        $response = $this->actingAs($this->admin)->patch(
            route('admin.users.role.update', $secondAdmin),
            ['role' => 'staff']
        );

        $response->assertSessionHasErrors('role');
        $this->assertEquals('admin', $secondAdmin->fresh()->role);
    }

    public function test_new_user_has_customer_role_by_default(): void
    {
        $user = User::create([
            'username' => 'newuser',
            'nama_lengkap' => 'Pengguna Baru',
            'password' => 'secret123',
        ]);

        $this->assertEquals('customer', $user->fresh()->role);
    }

    public function test_bulk_update_bypasses_eloquent_observer(): void
    {
        Log::shouldReceive('info')->never();

        User::whereKey($this->customer->id)->update(['role' => 'staff']);

        $this->assertEquals('staff', $this->customer->fresh()->role);
    }
}
