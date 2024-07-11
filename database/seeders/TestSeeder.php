<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uniqueId = Str::random(6);

        Staff::create([
            'unique_id' => $uniqueId,
            'fname' => 'John',
            'lname' => 'Doe',
            'mo' => '9876543434',
            'address' => '123 Main St',
            'password' => bcrypt('password123'),
            'email' => 'johndoe@example.com',
            'photo' => 'https://example.com/avatar1.jpg',
            'designation_id' => '1',
            'fcm_token' => 'fcmTokenExample123',
        ]);

    }
}
