<?php

namespace Database\Seeders;

use App\Domains\Donors\Actions\CreateDonor;
use Database\Factories\DonorFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < rand(10, 100); $i++) { 
            CreateDonor::run((new DonorFactory())->definition());
        }
    }
}
