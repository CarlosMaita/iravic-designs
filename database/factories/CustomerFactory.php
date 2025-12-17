<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'qualification' => $this->faker->randomElement(['Excelente', 'Bueno', 'Regular', 'Malo']),
            'dni' => $this->faker->numerify('########'),
            'cellphone' => $this->faker->phoneNumber(),
            'shipping_agency' => $this->faker->company(),
            'shipping_agency_address' => $this->faker->address(),
            'google_verified' => false,
            'google_id' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the customer's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the customer is Google verified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function googleVerified()
    {
        return $this->state(function (array $attributes) {
            return [
                'google_verified' => true,
                'google_id' => $this->faker->uuid(),
            ];
        });
    }
}
