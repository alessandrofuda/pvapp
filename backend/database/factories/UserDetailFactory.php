<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'user_id' => User::factory(),
            'phone' => $this->faker->phoneNumber(),
            'areas' => 'test-area, test-are2',
            'payer_id' => $this->faker->uuid(),
            'payer_firstname' => $this->faker->name,
            'payer_lastname' => $this->faker->lastName,
            'payer_phone' => $this->faker->phoneNumber,
            'payer_email' => $this->faker->email,
            'invoice_company_name' => $this->faker->company,
            'invoice_address' => $this->faker->address,
            'invoice_cap' => $this->faker->countryCode,
            'invoice_city' => $this->faker->city,
            'invoice_prov' => strtoupper($this->faker->lexify('??')),
            'invoice_fiscal_code' => strtoupper($this->faker->lexify('?????????????????')),
            'invoice_vat' => $this->faker->numerify('IT##########'),
            'invoice_SDI' => $this->faker->lexify('?????'),
            'notes' => $this->faker->text(100)
        ];
    }
}
