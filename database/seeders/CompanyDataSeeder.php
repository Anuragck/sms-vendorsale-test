<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'THE COMPANY Pvt Ltd',
            'logo' => 'test.png',
            'address_1'=>'12/37 Wayanad Road , Calicut ',
            'address_2'=>'ADD2 Address 02 ,Calicut 2 ,Kerala',
            'pincode'=>'673571',
            'district'=>'Calicut',
            'state'=>'Kerala',
            'gst_no'=>'AD2545CV786A',
            'mobile_1'=>'9966996699',
            'mobile_2'=>'8855885588'
        ]);
    }
}
