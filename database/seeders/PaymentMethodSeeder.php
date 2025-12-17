<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // NOTE: These are EXAMPLE payment methods with PLACEHOLDER instructions.
        // Update the instructions with your actual payment details after deployment.
        $methods = [
            [
                'name' => 'Pago Móvil',
                'code' => 'pago_movil',
                'instructions' => 'EJEMPLO: Realizar pago móvil al número: 0414-XXXXXXX | Banco Mercantil | V-XXXXXXXX',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Transferencia',
                'code' => 'transferencia',
                'instructions' => 'EJEMPLO: Realizar transferencia a la cuenta: 0105-XXXX-XXXX-XXXX | Banco Mercantil',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Efectivo',
                'code' => 'efectivo',
                'instructions' => 'Pago en efectivo en nuestra tienda física.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Binance',
                'code' => 'binance',
                'instructions' => 'EJEMPLO: Realizar el pago a: usuario@ejemplo.com',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'instructions' => 'EJEMPLO: Realizar el pago a: paypal@ejemplo.com',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Tarjeta',
                'code' => 'tarjeta',
                'instructions' => 'Pago con tarjeta de débito o crédito.',
                'is_active' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
