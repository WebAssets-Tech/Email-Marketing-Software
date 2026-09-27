<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailContactFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'owner_id' => 1,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'country_code' => $this->faker->countryCode(),
            'phone' => $this->faker->phoneNumber(),
            'favourites' => 0,
            'blocked' => 0,
            'trashed' => 0,
            'is_subscribed' => 0,
        ];
    }
}
