<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $user = new User();
        $user["first_name"] = "admin";
        $user["last_name"] = "admin";
        $user["email"] = "admin@email.com";
        $user["password"] = bcrypt("admin123");
        $user["is_admin"] = true;
        $user->save();
    }
}
