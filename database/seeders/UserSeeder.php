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
        'name' => 'Office User',
        'company_id'=>1,
        'username' => 'user',
        'role'=>2,
        'email'=>'officeuser@gmail.com',
        'password'=>'$2y$10$IYEDYnBubRcDSCocnlVGB.emLQi318gagURUsmds3AV95tuIRxUY6'
        /*12345678*/
    ]);

    User::create([
    'name' => 'Admin',
    'company_id'=>1,
    'username' => 'admin',
    'role'=>1,
    'email'=>'admin@gmail.com',
    'password'=>'$2y$10$IYEDYnBubRcDSCocnlVGB.emLQi318gagURUsmds3AV95tuIRxUY6'
    /*12345678*/
]);


User::create([

    'name' => 'SUPER ADMIN',
    'company_id'=>1,
    'username' => 'superadmin',
    'role'=>3,
        'gst'=>1,
        'vehicle'=>1,
        'jobcard'=>1,
        'reward'=>1,
    'email'=>'superadmin@gmail.com',
    'password'=>'$2y$10$IYEDYnBubRcDSCocnlVGB.emLQi318gagURUsmds3AV95tuIRxUY6'
    /*12345678*/
]);
    }
}
