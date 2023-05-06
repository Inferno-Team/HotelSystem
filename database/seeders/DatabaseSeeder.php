<?php

namespace Database\Seeders;

use App\Models\Garage;
use App\Models\Hotel;
use App\Models\ParkingSpace;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create(['type' => 'manager'])
            ->each(function (User $user) {
                $hotel = Hotel::factory()->count(1)->create(['manager_id' => $user->id])->first();
                Room::factory()->count(4)->sequence(
                    ['type' => 'single'],
                    ['type' => 'double'],
                )->create(['hotel_id' => $hotel->id]);
                $garage = Garage::factory()->count(1)->create(['hotel_id' => $hotel->id])->first();
                ParkingSpace::factory()->count(4)->create(['garage_id' => $garage->id]);
            });
    }
}
