<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Role;
use App\Models\CarBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserItemRolesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Item $item;
    private Role $bookerRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->user = User::factory()->create();
        $this->item = Item::factory()->create([
            'company_id' => $this->user->company_id
        ]);
        
        // Create or get the booker role
        $this->bookerRole = Role::firstOrCreate(
            ['slug' => 'booker'],
            ['name' => 'Booker']
        );
    }

    /** @test */
    public function user_can_get_their_item_roles()
    {
        // Assign the booker role to user for the item
        \DB::table('user_item_roles')->insert([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'role_id' => $this->bookerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Test itemRoles relationship
        $itemRoles = $this->user->itemRoles;
        
        $this->assertCount(1, $itemRoles);
        $this->assertEquals($this->bookerRole->id, $itemRoles->first()->id);
        $this->assertEquals($this->item->id, $itemRoles->first()->pivot->item_id);
    }

    /** @test */
    public function user_can_book_item_if_they_have_booker_role()
    {
        // Initially user cannot book the item
        $this->assertFalse($this->user->canBook($this->item));

        // Assign booker role to user for the item
        \DB::table('user_item_roles')->insert([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'role_id' => $this->bookerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Now user should be able to book the item
        $this->assertTrue($this->user->canBook($this->item));

        // Verify user can create a booking
        $booking = CarBooking::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'start_time' => now(),
            'end_time' => now()->addHour(),
            'purpose' => 'Test booking'
        ]);

        $this->assertDatabaseHas('car_bookings', [
            'id' => $booking->id,
            'user_id' => $this->user->id,
            'item_id' => $this->item->id
        ]);
    }

    /** @test */
    public function user_cannot_book_item_without_booker_role()
    {
        // Create a different role
        $viewerRole = Role::create([
            'name' => 'Viewer',
            'slug' => 'viewer'
        ]);

        // Assign viewer role to user for the item
        \DB::table('user_item_roles')->insert([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'role_id' => $viewerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User should not be able to book with viewer role
        $this->assertFalse($this->user->canBook($this->item));
    }

    /** @test */
    public function user_cannot_book_different_item()
    {
        // Assign booker role to user for the first item
        \DB::table('user_item_roles')->insert([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'role_id' => $this->bookerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create another item
        $anotherItem = Item::factory()->create([
            'company_id' => $this->user->company_id
        ]);

        // User should not be able to book the other item
        $this->assertFalse($this->user->canBook($anotherItem));
    }
}
