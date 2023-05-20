<?php

namespace Database\Seeders;

use App\Models\Garage;
use App\Models\Hotel;
use App\Models\ParkingSpace;
use App\Models\Room;
use App\Models\RoomImage;
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
        User::factory()->count(1)->create(['type' => 'manager', 'email' => 'hotel@gmail.com'])
            ->each(function (User $user) {
                $hotel = Hotel::factory()->count(1)->create(['manager_id' => $user->id])->first();
                Room::factory()->count(40)->sequence(
                    ['type' => 'single'],
                    ['type' => 'double'],
                    ['type' => 'classic'],
                    ['type' => 'family'],
                    ['type' => 'junior'],
                )->create(['hotel_id' => $hotel->id])
                    ->each(function (Room $room) {
                        $image = RoomImage::factory()->count(4)->create([
                            'room_id' => $room->id,
                            'file_name' => '_image1684596155.jpg'
                        ]);
                    });
                $garage = Garage::factory()->count(1)->create(['hotel_id' => $hotel->id])->first();
                ParkingSpace::factory()->count(4)->create(['garage_id' => $garage->id]);
            });
    }
}
