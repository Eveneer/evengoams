<?php

namespace Database\Seeders;

use App\Domains\Users\Actions\CreateUser;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < rand(10, 20); $i++) { 
            CreateUser::run((new UserFactory())->definition());
        }
    }
}
