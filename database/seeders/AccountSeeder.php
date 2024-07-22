<?php

namespace Database\Seeders;

use App\Domains\Accounts\Actions\CreateAccount;
use App\Domains\Accounts\Enums\AccountTypesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Main',
                'balance' => rand(100000, 1000000),
                'type' => AccountTypesEnum::BANK,
                'details' => []
            ],
            [
                'name' => 'Collection Boxes',
                'balance' => rand(100000, 1000000),
                'type' => AccountTypesEnum::CASH,
                'details' => []
            ],
            [
                'name' => 'Iftar Fund',
                'balance' => rand(100000, 1000000),
                'type' => AccountTypesEnum::BANK,
                'details' => []
            ],
            [
                'name' => 'Construction Fund',
                'balance' => rand(100000, 1000000),
                'type' => AccountTypesEnum::BANK,
                'details' => []
            ],
            [
                'name' => 'Cash in hand',
                'balance' => rand(100000, 1000000),
                'type' => AccountTypesEnum::CASH,
                'details' => []
            ],
            [
                'name' => 'Maintenance Fund',
                'balance' => rand(100000, 1000000),
                'type' => AccountTypesEnum::BANK,
                'details' => []
            ],
            [
                'name' => 'bKash',
                'balance' => rand(100000, 1000000),
                'type' => AccountTypesEnum::MOBILE,
                'details' => []
            ],
        ];

        foreach ($accounts as $account) {
            CreateAccount::run($account);
        }
    }
}
