<?php

namespace Database\Factories;

use App\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->address,
            'updated_at' => now(),
            'lat' => 40.621997,
            'lng' => -73.938831,
            'user_id'=>$this->faker->numberBetween(4, 5),
        ];
    }
}
