<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'region_code' => $this->faker->numberBetween(1, 10),
            'region_name' => $this->faker->text(100),
            'prov_code' => $this->faker->numberBetween(1,300),
            'prov_abbr' => strtoupper($this->faker->lexify('??')),
            'prov_name' => $this->faker->text(100),
            'city' => $this->faker->city(),
            'capoluogo' => $this->faker->boolean
        ];
    }
}
