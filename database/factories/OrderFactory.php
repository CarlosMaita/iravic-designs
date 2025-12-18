<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subtotal = $this->faker->randomFloat(2, 10, 500);

        return [
            'customer_id' => Customer::factory(),
            'user_id' => null,
            'date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'total' => $subtotal,
            'subtotal' => $subtotal,
            'discount' => 0,
            'status' => Order::STATUS_CREATED,
            'archived' => false,
            'exchange_rate' => $this->faker->randomFloat(2, 30, 50),
            'shipping_name' => $this->faker->name(),
            'shipping_dni' => $this->faker->numerify('########'),
            'shipping_phone' => $this->faker->phoneNumber(),
            'shipping_agency' => $this->faker->randomElement(Order::SHIPPING_AGENCIES),
            'shipping_address' => $this->faker->address(),
            'shipping_tracking_number' => null,
            'payed_bankwire' => 0,
            'payed_card' => 0,
            'payed_cash' => 0,
            'payed_credit' => 0,
        ];
    }

    /**
     * Indicate that the order is archived.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'archived' => true,
            ];
        });
    }

    /**
     * Indicate that the order is paid.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function paid()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Order::STATUS_PAID,
            ];
        });
    }

    /**
     * Indicate that the order is shipped.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function shipped()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Order::STATUS_SHIPPED,
            ];
        });
    }

    /**
     * Indicate that the order is completed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Order::STATUS_COMPLETED,
            ];
        });
    }

    /**
     * Indicate that the order is cancelled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Order::STATUS_CANCELLED,
            ];
        });
    }
}
