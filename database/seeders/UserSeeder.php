<?php

namespace Database\Seeders;

use App\Domains\Users\Actions\CreateUser;
use App\Domains\Users\Enums\UserTypesEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user for dev
        CreateUser::run([
            'name' => 'Dev Account',
            'email' => 'dev@timanity.org',
            'email_verified_at' => now(),
            'password' => 12345678,
            'remember_token' => Str::random(10),
            'type' => UserTypesEnum::ADMIN
        ]);

        for ($i = 0; $i < rand(10, 20); $i++) { 
            CreateUser::run((new UserFactory())->definition());
        }
    }
}
