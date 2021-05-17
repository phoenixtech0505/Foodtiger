<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RestorantsTableSeeder extends Seeder
{
    public function shuffle_assoc(&$array)
    {
        $keys = array_keys($array);

        shuffle($keys);

        foreach ($keys as $key) {
            $new[$key] = $array[$key];
        }

        $array = $new;

        return true;
    }

    private function createSubdomainFromName($name)
    {
        $cyr = [
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я', ];
        $lat = [
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q', ];
        $name = str_replace($cyr, $lat, $name);

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*$pizza = json_decode(File::get('database/seeds/json/pizza_res.json'),true);
        foreach (json_decode($pizza,true) as $key => $value) {
           print_r($key."\n");
           print_r(json_encode($value)."\n");
        }*/

        //Restorant owner
        DB::table('users')->insert([
            'name' => 'Demo Owner',
            'email' =>  'owner@example.com',
            'password' => Hash::make('secret'),
            'api_token' => Str::random(80),
            'email_verified_at' => now(),
            'phone' =>  '',
            'created_at' => now(),
            'updated_at' => now(),
            'plan_id'=> config('app.isqrsaas') ? 3 : null,
        ]);

        //Assign owner role
        DB::table('model_has_roles')->insert([
            'role_id' => 2,
            'model_type' =>  \App\User::class,
            'model_id'=> 2,
        ]);

        //Bronx, Manhattn, Queens, Brooklyn

        //Brooklyn
        $BrooklynLatLng = [['40.654509', '-73.948990'], ['40.588894', '-73.939175'], ['40.607402', '-73.987272'], ['40.621997', '-73.938831']];
        $BrooklynArea = '[{"lat": 40.61811135844185, "lng": -74.04154967363282}, {"lat": 40.593088644275994, "lng": -74.00447081621094}, {"lat": 40.57483700944677, "lng": -74.01271056230469}, {"lat": 40.57222922648421, "lng": -73.94816588457032}, {"lat": 40.58318123192112, "lng": -73.87812804277344}, {"lat": 40.620196161732025, "lng": -73.89117430742188}, {"lat": 40.64520872605633, "lng": -73.85340880449219}, {"lat": 40.71862893820799, "lng": -73.96327208574219}, {"lat": 40.680627151592745, "lng": -74.01957701738282}, {"lat": 40.66552453494284, "lng": -74.00447081621094}]';

        //Queens
        $QueensLatLng = [['40.716729', '-73.793035'], ['40.751418', '-73.809531'], ['40.753759', '-73.799224'], ['40.749078', '-73.812623']];
        $QueensArea = '[{"lat": 40.72538611607021, "lng": -73.96387365315225}, {"lat": 40.65093145404635, "lng": -73.85195043537881}, {"lat": 40.62175171970407, "lng": -73.76680639241006}, {"lat": 40.64051158441822, "lng": -73.71462133381631}, {"lat": 40.670724724281335, "lng": -73.67891576741006}, {"lat": 40.76388243617103, "lng": -73.74758031819131}, {"lat": 40.79351981360113, "lng": -73.84851720783975}, {"lat": 40.78728146460242, "lng": -73.91100194905069}]';

        //Manhattn
        $ManhattnLatLng = [['40.726358', '-73.996879'], ['40.724883', '-74.001985'], ['40.724102', '-73.999064'], ['40.717857', '-74.004561']];
        $ManhattnArea = '[{"lat": 40.70279189834177, "lng": -74.01818193403926}, {"lat": 40.711640621663136, "lng": -73.97972978560176}, {"lat": 40.798503799354734, "lng": -73.91381181685176}, {"lat": 40.83487975446948, "lng": -73.94745744673457}, {"lat": 40.750665070026194, "lng": -74.01200212446895}]';

        //Bronx
        $BronxLatLng = [['40.842930', '-73.866629'], ['40.850218', '-73.887522'], ['40.842427', '-73.866908'], ['40.832557', '-73.889583']];
        $BronxArea = '[{"lat": 40.79717207195068, "lng": -73.91185629950473}, {"lat": 40.83822431229653, "lng": -73.94893515692661}, {"lat": 40.90572332325597, "lng": -73.9097963629813}, {"lat": 40.89793848793209, "lng": -73.83426535712192}, {"lat": 40.886519072020896, "lng": -73.78139365302036}, {"lat": 40.81900047658591, "lng": -73.79169333563755}]';

        $pizza = json_decode(File::get(base_path('database/seeders/json/pizza_res.json')), true);
        $mex = json_decode(File::get(base_path('database/seeders/json/mexican_res.json')), true);
        $burg = json_decode(File::get(base_path('database/seeders/json/burger_res.json')), true);
        $reg = json_decode(File::get(base_path('database/seeders/json/regular_res.json')), true);

        $restorants = [
            ['city_id'=>2, 'latlng'=>$BrooklynLatLng[0], 'area'=>$BrooklynArea, 'items'=>$pizza, 'name'=>'Leuka Pizza', 'description'=>'italian, pasta, pizza', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/9d180742-9fb3-4b46-8563-8c24c9004fd3'],
            ['city_id'=>2, 'latlng'=>$BrooklynLatLng[1], 'area'=>$BrooklynArea, 'items'=>$burg, 'name'=>'Oasis Burgers', 'description'=>'burgers, drinks, best chicken', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/c8d27bcc-54da-4c18-b8e6-f1414c71612c'],
            ['city_id'=>2, 'latlng'=>$BrooklynLatLng[2], 'area'=>$BrooklynArea, 'items'=>$mex, 'name'=>'Brooklyn Taco', 'description'=>'yummy taco, wraps, fast food', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/3e571ad8-e161-4245-91d9-88b47d6d6770'],
            ['city_id'=>2, 'latlng'=>$BrooklynLatLng[3], 'area'=>$BrooklynArea, 'items'=>$reg, 'name'=>'The Brooklyn tree', 'description'=>'drinks, lunch, bbq', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/6fa5233f-00f3-4f52-950c-5a1705583dfc'],
        ];

        $this->shuffle_assoc($pizza);
        $this->shuffle_assoc($mex);
        $this->shuffle_assoc($burg);
        $this->shuffle_assoc($reg);

        array_push($restorants, ['city_id'=>3, 'latlng'=>$QueensLatLng[0], 'area'=>$QueensArea, 'items'=>$pizza, 'name'=>'Awang Italian Restorant', 'description'=>'italian, pasta, pizza', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/4a2067cb-f39c-4b26-83ef-9097512d3328']);
        array_push($restorants, ['city_id'=>3, 'latlng'=>$QueensLatLng[1], 'area'=>$QueensArea, 'items'=>$mex, 'name'=>'Wendy Taco', 'description'=>'yummy taco, wraps, fast food', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/6f9e8892-4a28-4c99-ab24-57179a1424b9']);
        array_push($restorants, ['city_id'=>3, 'latlng'=>$QueensLatLng[2], 'area'=>$QueensArea, 'items'=>$burg, 'name'=>'Burger 2Go', 'description'=>'burgers, drinks, best chicken', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/80a49037-07e9-4e28-b23e-66fd641c1c77']);
        array_push($restorants, ['city_id'=>3, 'latlng'=>$QueensLatLng[3], 'area'=>$QueensArea, 'items'=>$reg, 'name'=>'Titan Foods', 'description'=>'drinks, lunch, bbq', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/56e90ea7-5321-4cfd-8b2c-918ccd3c3f77']);

        $this->shuffle_assoc($pizza);
        $this->shuffle_assoc($mex);
        $this->shuffle_assoc($burg);
        $this->shuffle_assoc($reg);

        array_push($restorants, ['city_id'=>4, 'latlng'=>$ManhattnLatLng[0], 'area'=>$ManhattnArea, 'items'=>$pizza, 'name'=>'Pizza Manhattn', 'description'=>'italian, international, pasta', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/0102bebe-b6c4-46b0-9195-ee06bca71a37']);
        array_push($restorants, ['city_id'=>4, 'latlng'=>$ManhattnLatLng[1], 'area'=>$ManhattnArea, 'items'=>$mex, 'name'=>'il Buco', 'description'=>'tacos, wraps, Quesadilla', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/4384df9b-9656-49d1-bfc1-9b5e85e1193a']);
        array_push($restorants, ['city_id'=>4, 'latlng'=>$ManhattnLatLng[2], 'area'=>$ManhattnArea, 'items'=>$burg, 'name'=>'Vandal Burgers', 'description'=>'drinks, beef burgers', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/5757558a-94d7-4ba9-b39c-2e258701f051']);
        array_push($restorants, ['city_id'=>4, 'latlng'=>$ManhattnLatLng[3], 'area'=>$ManhattnArea, 'items'=>$reg, 'name'=>'Malibu Diner', 'description'=>'drinks, lunch, bbq', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/a2b5b612-9fec-4e28-bb7d-88a06d97bda6']);

        $this->shuffle_assoc($pizza);
        $this->shuffle_assoc($mex);
        $this->shuffle_assoc($burg);
        $this->shuffle_assoc($reg);

        array_push($restorants, ['city_id'=>1, 'latlng'=>$BronxLatLng[0], 'area'=>$BronxArea, 'items'=>$pizza, 'name'=>'Pizza Relham', 'description'=>'italian, international, pasta', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/0102bebe-b6c4-46b0-9195-ee06bca71a37']);
        array_push($restorants, ['city_id'=>1, 'latlng'=>$BronxLatLng[1], 'area'=>$BronxArea, 'items'=>$mex, 'name'=>'NorWood Burito', 'description'=>'tacos, wraps, Quesadilla', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/4384df9b-9656-49d1-bfc1-9b5e85e1193a']);
        array_push($restorants, ['city_id'=>1, 'latlng'=>$BronxLatLng[2], 'area'=>$BronxArea, 'items'=>$burg, 'name'=>'Morris Park Burger', 'description'=>'drinks, beef burgers', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/5757558a-94d7-4ba9-b39c-2e258701f051']);
        array_push($restorants, ['city_id'=>1, 'latlng'=>$BronxLatLng[3], 'area'=>$BronxArea, 'items'=>$reg, 'name'=>'Bronx VanNest Restorant', 'description'=>'drinks, lunch, bbq', 'image'=>'https://foodtiger.mobidonia.com/uploads/restorants/a2b5b612-9fec-4e28-bb7d-88a06d97bda6']);

        $id = 1;
        $catId = 1;
        foreach ($restorants as $key => $restorant) {
            DB::table('restorants')->insert([
                'name'=>$restorant['name'],
                'logo'=>$restorant['image'],
                'city_id'=>$restorant['city_id'],
                'subdomain'=>$this->createSubdomainFromName($restorant['name']), // strtolower(preg_replace('/[^A-Za-z0-9]/', '', $restorant['name'])),
                'user_id'=>2,
                'created_at' => now(),
                'updated_at' => now(),
                'lat' => $restorant['latlng'][0],
                'lng' => $restorant['latlng'][1],
                'radius'=>$restorant['area'],
                'address' => '6 Yukon Drive Raeford, NC 28376',
                'phone' => '(530) 625-9694',
                'description'=>$restorant['description'],
                'minimum'=>10,
            ]);

            DB::table('hours')->insert([
                'restorant_id' => $id,
                '0_from' => '05:00',
                '0_to' => '23:00',
                '1_from' => '05:00',
                '1_to' => '23:00',
                '2_from' => '05:00',
                '2_to' => '23:00',
                '3_from' => '05:00',
                '3_to' => '23:00',
                '4_from' => '05:00',
                '4_to' => '23:00',
                '5_from' => '05:00',
                '5_to' => '23:00',
                '6_from' => '05:00',
                '6_to' => '23:00',
            ]);

            $areas = [['name'=>'Inside', 'count'=>10], ['name'=>'Terrasse', 'count'=>5]];
            foreach ($areas as $key => $restoarea) {
                $lastAreaID = DB::table('restoareas')->insertGetId([
                    'name'=>$restoarea['name'],
                    'restaurant_id' => $id,
                ]);

                for ($i = 0; $i < $restoarea['count']; $i++) {
                    DB::table('tables')->insertGetId([
                        'name'=>'Table '.($i + 1),
                        'restaurant_id' => $id,
                        'restoarea_id' => $lastAreaID,
                    ]);
                }
            }

            foreach ($restorant['items'] as $category => $categoryData) {
                DB::table('categories')->insert([
                    'name'=>$category,
                    'restorant_id'=>$id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($categoryData as $key => $menuItem) {
                    $lastItemID = DB::table('items')->insertGetId([
                        'name'=>isset($menuItem['title']) ? $menuItem['title'] : '',
                        'description'=>isset($menuItem['description']) ? $menuItem['description'] : '',
                        'image'=>isset($menuItem['image']) ? $menuItem['image'] : '',
                        'price'=>isset($menuItem['price']) ? $menuItem['price'] : '',
                        'vat'=>21,
                        'category_id'=>$catId,
                        'created_at' => now(),
                        'updated_at' => now(),
                        'has_variants'=>strpos($menuItem['title'], 'izza') !== false,
                    ]);

                    //Add regular extras extras
                    if (strpos($menuItem['title'], 'alad') !== false) {
                        //Add extras
                        DB::table('extras')->insertGetId([
                            'name'=>'Extra cheese',
                            'price'=>1.2,
                            'item_id'=>$lastItemID,

                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        DB::table('extras')->insertGetId([
                            'name'=>'Extra olives',
                            'price'=>0.3,
                            'item_id'=>$lastItemID,

                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        DB::table('extras')->insertGetId([
                            'name'=>'Tuna',
                            'price'=>1.5,
                            'item_id'=>$lastItemID,

                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    //Add variants and options
                    if (strpos($menuItem['title'], 'izza') !== false) {

                        //Extras
                        DB::table('extras')->insertGetId([
                            'name'=>'Olive',
                            'price'=>1,
                            'item_id'=>$lastItemID,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        DB::table('extras')->insertGetId([
                            'name'=>'Mushroom',
                            'price'=>0.5,
                            'item_id'=>$lastItemID,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $lastPeperoniExpensive = DB::table('extras')->insertGetId([
                            'name'=>'Peperoni',
                            'price'=>2,
                            'item_id'=>$lastItemID,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'extra_for_all_variants'=>0,
                        ]);

                        $lastPeperoniCheep = DB::table('extras')->insertGetId([
                            'name'=>'Peperoni',
                            'price'=>1,
                            'item_id'=>$lastItemID,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'extra_for_all_variants'=>0,
                        ]);

                        $options_one = 'Small,Medium,Large,Family';
                        $options_two = 'Hand-Tosset,Thin Crust,Double Decker';

                        //Add options
                        $optionONEID = DB::table('options')->insertGetId([
                            'name'=>'Size',
                            'options'=>$options_one,
                            'item_id'=>$lastItemID,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $optionTWOID = DB::table('options')->insertGetId([
                            'name'=>'Crust',
                            'options'=>$options_two,
                            'item_id'=>$lastItemID,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        //Add variants
                        $incPrice = 0;
                        foreach (explode(',', $options_one) as $key_one => $value_one) {
                            foreach (explode(',', $options_two) as $key_two => $value_two) {
                                $lastVariant = DB::table('variants')->insertGetId([
                                    'price'=>$menuItem['price'] + $incPrice,
                                    'options'=>'{"'.$optionONEID.'":"'.str_replace(' ', '-', mb_strtolower(trim($value_one))).'","'.$optionTWOID.'":"'.str_replace(' ', '-', mb_strtolower(trim($value_two))).'"}',
                                    'item_id'=>$lastItemID,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                                $incPrice++;

                                //Pepperoni price
                                if ($value_one == 'Large' || $value_one == 'Family') {
                                    DB::table('variants_has_extras')->insertGetId([
                                        'variant_id'=>$lastVariant,
                                        'extra_id'=>$lastPeperoniCheep,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                } else {
                                    DB::table('variants_has_extras')->insertGetId([
                                        'variant_id'=>$lastVariant,
                                        'extra_id'=>$lastPeperoniExpensive,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                }
                            }
                        }
                    }
                }
                $catId++;
            }

            $id++;
        }
    }
}
