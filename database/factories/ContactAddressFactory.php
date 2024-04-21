<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\ContactAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactAddress>
 */
class ContactAddressFactory extends Factory
{
    protected $model = ContactAddress::class;

    public function definition(): array
    {
        return [
            'contact_id' => Contact::factory(),
            'cep' => $this->faker->postcode,
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->secondaryAddress,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude
        ];
    }
}
