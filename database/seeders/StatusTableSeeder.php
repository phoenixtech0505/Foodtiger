<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['Just created', 'Accepted by admin', 'Accepted by restaurant', 'Assigned to driver', 'Prepared', 'Picked up', 'Delivered', 'Rejected by admin', 'Rejected by restaurant', 'Updated', 'Closed', 'Rejected by driver', 'Accepted by driver'];
        foreach ($statuses as $key => $status) {
            DB::table('status')->insert([
                'name' => $status,
                'alias' =>  str_replace(' ', '_', strtolower($status)),
            ]);
        }
    }
}
