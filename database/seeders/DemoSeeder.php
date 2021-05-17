<?php

namespace Database\Seeders;

use Database\Seeders\CitiesTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Restorant
         * Categories
         * Menu Items
         * Drivers
         * Clients
         * Orders
         * Cities / since 1_5
         * Item versions / since 1_5
         * Reviews / since 1_5.
         */

        //Cities
        $this->call(CitiesTableSeeder::class);

        //Restorants
        $this->call(RestorantsTableSeeder::class);

        if (config('app.isqrsaas')) {
            //QRSAAS
            $this->call(PlansSeeder::class);

            //Orders
            $demoOrders = \App\Order::factory()->count(500)->create();

            $demoOrdersLast3Days = \App\Order::factory()->count(100)->recent()->create();
        } else {

            //Driver
            DB::table('users')->insert([
                'name' => 'Demo Driver',
                'email' =>  'driver@example.com',
                'password' => Hash::make('secret'),
                'api_token' => Str::random(80),
                'email_verified_at' => now(),
                'phone' =>  '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //Assign driver role
            DB::table('model_has_roles')->insert([
                'role_id' => 3,
                'model_type' =>  \App\User::class,
                'model_id'=> 3,
            ]);

            //Client 1
            DB::table('users')->insert([
                'name' => 'Demo Client 1',
                'email' =>  'client@example.com',
                'password' => Hash::make('secret'),
                'api_token' => Str::random(80),
                'email_verified_at' => now(),
                'phone' =>  '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //Assign client role
            DB::table('model_has_roles')->insert([
                'role_id' => 4,
                'model_type' =>  \App\User::class,
                'model_id'=> 4,
            ]);

            //Client 2
            DB::table('users')->insert([
                'name' => 'Demo Client 2',
                'email' =>  'client2@example.com',
                'password' => Hash::make('secret'),
                'api_token' => Str::random(80),
                'email_verified_at' => now(),
                'phone' =>  '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //Assign client role
            DB::table('model_has_roles')->insert([
                'role_id' => 4,
                'model_type' =>  \App\User::class,
                'model_id'=> 5,
            ]);

            //Driver 2
            DB::table('users')->insert([
                'name' => 'Demo Driver 2',
                'email' =>  'driver2@example.com',
                'password' => Hash::make('secret'),
                'api_token' => Str::random(80),
                'email_verified_at' => now(),
                'phone' =>  '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            //Assign driver role
            DB::table('model_has_roles')->insert([
                'role_id' => 3,
                'model_type' =>  \App\User::class,
                'model_id'=> 6,
            ]);

            //Addresses
            $clientAddress = \App\Address::factory()->count(10)->create();

            //Orders
            $demoOrders = \App\Order::factory()->count(500)->create();

            $demoOrdersLast3Days = \App\Order::factory()->count(100)->recent()->create();

            //Order has status initial
            foreach ($demoOrders as $key => $order) {
                DB::table('order_has_status')->insert([
                    'order_id' => $order->id,
                    'status_id' =>  1,
                    'user_id' => 4,
                    'comment' => 'Initial comment',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            //Orders In the last 3 days
            foreach ($demoOrdersLast3Days as $key => $order) {
                //JUST created
                DB::table('order_has_status')->insert([
                    'order_id' => $order->id,
                    'status_id' =>  1,
                    'user_id' => 4,
                    'comment' => 'New order created',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                //For orders for restaurant ID 1 - set order items
                if ($order->restorant_id == 1) {
                    //Add orders items
                    DB::table('order_has_items')->insert([
                        'order_id' => $order->id,
                        'item_id' =>  7,
                        'qty' => 2,
                        'extras' => '["Peperoni + $2.00","Olive + $1.00"]',
                        'vat' => 21,
                        'vatvalue' => 5.88,
                        'variant_price' => 10.99,
                        'variant_name' => 'small,hand-tosset',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('order_has_items')->insert([
                        'order_id' => $order->id,
                        'item_id' =>  8,
                        'qty' => 1,
                        'extras' => '["Peperoni + $1.00","Olive + $1.00"]',
                        'vat' => 21,
                        'vatvalue' => 5.88,
                        'variant_price' => 25.99,
                        'variant_name' => 'family,double-decker',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('orders')->where('id', $order->id)->update(['order_price' => 55.97, 'vatvalue'=>11.75]);
                }

                //Every 10th rejected by admin
                if ($order->id % 10 == 0) {
                    DB::table('order_has_status')->insert([
                        'order_id' => $order->id,
                        'status_id' =>  8,
                        'user_id' => 1,
                        'comment' => 'Rejected by admin',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    //Move on
                    //one in 10 - wait for user
                    if ($order->id % 9 != 0) {
                        DB::table('order_has_status')->insert([
                            'order_id' => $order->id,
                            'status_id' =>  2,
                            'user_id' => 1,
                            'comment' => 'Accepted by admin',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        //Every 24th rejected by admin
                        if ($order->id % 24 == 0) {
                            DB::table('order_has_status')->insert([
                                'order_id' => $order->id,
                                'status_id' =>  9,
                                'user_id' => 1,
                                'comment' => 'Rejected by restaurant',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        } else {
                            if ($order->id % 2 == 0) {
                                DB::table('order_has_status')->insert([
                                    'order_id' => $order->id,
                                    'status_id' =>  3,
                                    'user_id' => 1,
                                    'comment' => 'Accepted by restaurant',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                                if ($order->id % 4 == 0) {
                                    DB::table('order_has_status')->insert([
                                        'order_id' => $order->id,
                                        'status_id' =>  4,
                                        'user_id' => 1,
                                        'comment' => 'Assigned to driver',
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                    $order->driver_id = 3;
                                    $order->update();
                                    DB::table('order_has_status')->insert([
                                        'order_id' => $order->id,
                                        'status_id' =>  5,
                                        'user_id' => 1,
                                        'comment' => 'Prepared by restaurant',
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);

                                    $comments = [__('Food was amazing. Delivery was on time.'), __('Super fast delivery. Food was warm and person was kind'), __('Highly recommended restaurant'), __('The pizza was amazing.')];

                                    //On every delivered order, put rating
                                    DB::table('ratings')->insert([
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                        'rateable_id' => $order->restorant_id,
                                        'order_id'=>$order->id,
                                        'rating'=>[4, 5][array_rand([4, 5], 1)],
                                        'user_id'=>4,
                                        'rateable_type'=>\App\Restorant::class,
                                        'comment'=> $comments[array_rand($comments, 1)],
                                    ]);

                                    if ($order->id % 8 == 0) {
                                        DB::table('order_has_status')->insert([
                                            'order_id' => $order->id,
                                            'status_id' =>  6,
                                            'user_id' => 1,
                                            'comment' => 'Picked up',
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ]);
                                        $order->lat = $order->restorant->lat;
                                        $order->lng = $order->restorant->lng;
                                        $order->update();
                                        if ($order->id % 16 == 0) {
                                            DB::table('order_has_status')->insert([
                                                'order_id' => $order->id,
                                                'status_id' =>  7,
                                                'user_id' => 1,
                                                'comment' => 'Delivered',
                                                'created_at' => now(),
                                                'updated_at' => now(),
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                //$statuses=["Just created","Accepted by admin","Accepted by restaurant","Assigned to driver","Prepared","Picked up","Delivered","Rejected by admin","Rejected by restaurant"];
            }
        }
    }
}
