<?php

namespace Database\Factories;

use App\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    public function recent()
    {
        return $this->state(function () {
            return [
                'created_at'=>$this->faker->dateTimeBetween('-1 day', 'now'),
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (config('app.isqrsaas')) {
            $resto_id = $this->faker->numberBetween(1, 3);

            return [
                'updated_at' => now(),
                'table_id'=>$this->faker->numberBetween((($resto_id - 1) * 15) + 1, $resto_id * 15),
                'restorant_id'=>$resto_id,
                'payment_status'=>'paid',
                'comment'=>$this->faker->text,
                'order_price'=>$this->faker->numberBetween(30, 100),
                'created_at'=>$this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        } else {
            return [
                'updated_at' => now(),
                'address_id'=>$this->faker->numberBetween(1, 5),
                'client_id'=>$this->faker->numberBetween(4, 5),
                'restorant_id'=>$this->faker->numberBetween(1, 3),
                'payment_status'=>'paid',
                //'driver_id'=>3,
                //'phone'=>$this->faker->phoneNumber,
                'delivery_pickup_interval'=>'630_660',
                'comment'=>$this->faker->text,
                'delivery_price'=>$this->faker->numberBetween(5, 10),
                'order_price'=>$this->faker->numberBetween(30, 100),
                'created_at'=>$this->faker->dateTimeBetween('-1 year', 'now'),
            ];
        }
    }
}
