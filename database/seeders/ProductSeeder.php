<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();


        for ($i = 0; $i < 20; $i++) {
           
            Product::create([
                'user_id' => 2,
                'category_id' => 1,
                'quantity' => '5000',
                'opening_quantity' => '5000',
                'reward_points' =>   '200',
                'name' => $faker->regexify('[A-Za-z0-9]{8}'),
                'gst' => '12',
                'mrp' => '600',
                'discount' => '10',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
    



          }




        Product::create([
            'user_id' => 2,
            'category_id' => 1,
            'quantity' => '5000',
            'opening_quantity' => '5000',
            'reward_points' =>   '200',
            'name' => 'mirror',
            'gst' => '12',
            'mrp' => '600',
            'discount' => '10',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);


        Product::create([
            'user_id' => 2,
            'category_id' => 1,
            'quantity' => '5000',
            'opening_quantity' => '5000',
            'reward_points' =>   '200',
            'name' => 'side mirror',
            'gst' => '12',
            'mrp' => '600',
            'discount' => '10',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        Product::create([
            'user_id' => 2,
            'category_id' => 1,
            'quantity' => '5000',
            'opening_quantity' => '5000',
            'reward_points' =>   '200',
            'name' => 'paint',
            'gst' => '12',
            'mrp' => '600',
            'discount' => '10',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        Product::create([
            'user_id' => 2,
            'category_id' => 1,
            'quantity' => '1000',
            'opening_quantity' => '1000',
            'reward_points' =>   '100',
            'name' => 'tyre',
            'gst' => '5',
            'mrp' => '700',
            'discount' => '20',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        Product::create([
            'user_id' => 2,
            'category_id' => 3,
            'quantity' => '2000',
            'opening_quantity' => '2000',
            'gst' => '5',
            'mrp' => '500',
            'reward_points' =>   '100',
            'name' => 'car wash',
            'discount' => '5',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }
}
