<?php

namespace Database\Seeders;

use App\Domains\Vendors\Actions\CreateVendor;
use Database\Factories\VendorFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < rand(10, 20); $i++) { 
            CreateVendor::run((new VendorFactory())->definition());
        }
    }
}
