<?php

namespace Database\Seeders;

use App\Domains\Accounts\Account;
use App\Domains\Donors\Donor;
use App\Domains\Tags\Tag;
use App\Domains\Transactions\Actions\CreateTransaction;
use App\Domains\Users\Enums\UserTypesEnum;
use App\Domains\Users\User;
use App\Domains\Vendors\Vendor;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereType(UserTypesEnum::ORG_MEMBER)->get();
        $tags = Tag::all();
        $entities = [
            Account::class => Account::all(),
            Donor::class => Donor::all(),
            Vendor::class => Vendor::all()
        ];

        $toables = [Account::class, Vendor::class];
        $fromables = [Account::class, Donor::class];

        for ($date = now()->addMonths(-3); $date < now(); $date->addDay()) { 
            $transactions = 0;

            while (rand(0, 9) < 15 - $transactions) {
                $transactions++;
                $toable = fake()->randomElement($toables);
                $fromable = fake()->randomElement($fromables);

                CreateTransaction::run([
                    'date' => $date->toDateString(),
                    'amount' => rand(100, 1000) * 100,
                    'author_id' => fake()->randomElement($users)->id,
                    'fromable_type' => $fromable,
                    'fromable_id' => $this->getSingleEntityId($entities, $fromable),
                    'toable_type' => $toable,
                    'toable_id' => $this->getSingleEntityId($entities, $toable),
                    'note' => rand(0, 9) < 5 ? 'Some transaction' : null,
                    'tags' => $this->getRandomTags($tags),
                ]);
            }
        }
    }

    public function getSingleEntityId(array $entities, string $type): string
    {
        return fake()->randomElement($entities[$type])->id;
    }
    
    public function getRandomTags($tags): array
    {
        $tags_ids = [];
        $tags = fake()->randomElements($tags, rand(0, 3));

        foreach ($tags as $tag)
            $tags_ids[] = $tag->id;

        return $tags_ids;
    }
}
