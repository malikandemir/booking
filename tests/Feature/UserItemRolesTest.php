<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Resource;
use App\Models\Role;
use App\Models\CarBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserResourceRolesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Resource $resource;
    private Role $bookerRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->user = User::factory()->create();
        $this->resource = Resource::factory()->create([
            'company_id' => $this->user->company_id
        ]);
        
        // Create or get the booker role
        $this->bookerRole = Role::firstOrCreate(
            ['slug' => 'booker'],
            ['name' => 'Booker']
        );
    }

    /** @test */
    public function user_can_get_their_resource_roles()
    {
        // Assign the booker role to user for the resource
        \DB::table('user_resource_roles')->insert([
            'user_id' => $this->user->id,
            'resource_id' => $this->resource->id,
            'role_id' => $this->bookerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Test resourceRoles relationship
        $resourceRoles = $this->user->resourceRoles;
        
        $this->assertCount(1, $resourceRoles);
        $this->assertEquals($this->bookerRole->id, $resourceRoles->first()->id);
        $this->assertEquals($this->resource->id, $resourceRoles->first()->pivot->resource_id);
    }

    /** @test */
    public function user_can_book_resource_if_they_have_booker_role()
    {
        // Initially user cannot book the resource
        $this->assertFalse($this->user->canBook($this->resource));

        // Assign booker role to user for the resource
        \DB::table('user_resource_roles')->insert([
            'user_id' => $this->user->id,
            'resource_id' => $this->resource->id,
            'role_id' => $this->bookerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Now user should be able to book the resource
        $this->assertTrue($this->user->canBook($this->resource));

        // Verify user can create a booking
        $booking = CarBooking::create([
            'user_id' => $this->user->id,
            'resource_id' => $this->resource->id,
            'start_time' => now(),
            'end_time' => now()->addHour(),
            'purpose' => 'Test booking'
        ]);

        $this->assertDatabaseHas('car_bookings', [
            'id' => $booking->id,
            'user_id' => $this->user->id,
            'resource_id' => $this->resource->id
        ]);
    }

    /** @test */
    public function user_cannot_book_resource_without_booker_role()
    {
        // Create a different role
        $viewerRole = Role::create([
            'name' => 'Viewer',
            'slug' => 'viewer'
        ]);

        // Assign viewer role to user for the resource
        \DB::table('user_resource_roles')->insert([
            'user_id' => $this->user->id,
            'resource_id' => $this->resource->id,
            'role_id' => $viewerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // User should not be able to book with viewer role
        $this->assertFalse($this->user->canBook($this->resource));
    }

    /** @test */
    public function user_cannot_book_different_resource()
    {
        // Assign booker role to user for the first resource
        \DB::table('user_resource_roles')->insert([
            'user_id' => $this->user->id,
            'resource_id' => $this->resource->id,
            'role_id' => $this->bookerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create another resource
        $anotherResource = Resource::factory()->create([
            'company_id' => $this->user->company_id
        ]);

        // User should not be able to book the other resource
        $this->assertFalse($this->user->canBook($anotherResource));
    }
}
