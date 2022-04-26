<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'user_id' => 2,
            'name' => 'Sale'
        ]);

        Category::create([
            'user_id' => 2,
            'name' => 'Reward'
        ]);

        Category::create([
            'user_id' => 2,
            'name' => 'Service'
        ]);




    }
}
