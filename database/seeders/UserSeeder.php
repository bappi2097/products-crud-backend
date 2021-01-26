<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "John Doe",
            "email" => "john.doe@email.com",
            "password" => bcrypt("admin123"),
        ]);
    }
}
