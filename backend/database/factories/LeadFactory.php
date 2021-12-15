<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'form' => $this->faker->text(50),
            'service_ids' => $this->faker->randomElement([$this->faker->numerify('#,#,#'), $this->faker->numerify('#,#'), $this->faker->numerify('#')]),
            'name' => $this->faker->name,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'municipality' => $this->faker->city,
            'province' => $this->faker->lexify('??'),
            'region' => 'TestRegion',
            'price' => 99.99,
            'description' => $this->faker->text(),
            'phone_verified' => null,
            'approved' => $this->faker->boolean,
            'sales_counter' => $this->faker->numberBetween(0,5),
            'notes' => $this->faker->text,
            'fake_lead' => 0
        ];
    }
}
