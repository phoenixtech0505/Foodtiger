<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            ['name'=>'Bronx', 'alias'=>'bx', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/177686e2-8597-46e0-bf70-daa8d5ff0085_large.jpg'],
            ['name'=>'Brooklyn', 'alias'=>'br', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/6d001b6c-2ba1-499e-9f57-09b7dc46ace3_large.jpg'],
            ['name'=>'Queens', 'alias'=>'qe', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/492b78df-1756-4c30-910a-d3923b66aa97_large.jpg'],
            ['name'=>'Manhattn', 'alias'=>'mh', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/01f41f56-1acf-44f0-8e8d-fedceb5267cf_large.jpg'],
            ['name'=>'Richmond', 'alias'=>'ri', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/fe6c500b-3410-41ff-9734-94c58351ba60_large.jpg'],
            ['name'=>'Buffalo', 'alias'=>'bf', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/0c3c8fb0-c252-4758-9699-6851b15796ef_large.jpg'],
            ['name'=>'Rochester', 'alias'=>'rh', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/c7f21267-7149-4311-9fd9-4a08904f67a3_large.jpg'],
            ['name'=>'Yonkers', 'alias'=>'yo', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/8c65269a-3bbc-4899-be13-75d1bc35e9cd_large.jpg'],
            ['name'=>'Syracuse', 'alias'=>'sy', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/1e5fcde5-5dc0-4c29-8f49-314658836fb8_large.jpg'],
            ['name'=>'Albany', 'alias'=>'al', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/b521e77f-1d3e-4695-b871-bac8262c85dc_large.jpg'],
            ['name'=>'New Rochelle', 'alias'=>'nr', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/8b3ebb83-2e2e-43dd-8747-4f9c6f451199_large.jpg'],
            ['name'=>'Mount Vernon', 'alias'=>'mv', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/daecfb93-677f-43a9-9e7e-4cf109194399_large.jpg'],
            ['name'=>'Schenectady', 'alias'=>'sc', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/177686e2-8597-46e0-bf70-daa8d5ff0085_large.jpg'],
            ['name'=>'Utica', 'alias'=>'ut', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/507847c5-1896-4ecf-bfe8-9c5f198ce41e_large.jpg'],
            ['name'=>'White Plains', 'alias'=>'wp', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/a8e96a74-dc3a-403c-8fd0-b4528838e98c_large.jpg'],
            ['name'=>'Niagara Falls', 'alias'=>'nf', 'image'=>'https://foodtiger.mobidonia.com/uploads/settings/99d5b4c3-0bb4-428a-96cf-0e510bc3ab57_large.jpg'],
        ];

        foreach ($cities as $key => $city) {
            DB::table('cities')->insert([
                'name'=>$city['name'],
                'alias'=>$city['alias'],
                'created_at' => now(),
                'updated_at' => now(),
                'image'=>$city['image'],
                'header_title'=>'Food Delivery from<br /><strong>'.$city['name'].'</strong>’s Best Restaurants',
                'header_subtitle'=>'The meals you love, delivered with care',
            ]);
        }
    }
}
