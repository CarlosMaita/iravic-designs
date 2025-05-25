<?php

namespace Database\Seeders;

use App\Helpers\FormatHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsernameCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')
            ->orderBy('id')
            ->chunk(100, function ($customers) {
                foreach ($customers as $customer) {
                    $dniFormatted = FormatHelper::formatDniNumber($customer->dni);
                    DB::table('customers')
                        ->where('id', $customer->id)
                        ->update([
                            'username' => $dniFormatted,
                            'password' => bcrypt('12345')
                        ]);
                }
            });
    }
}
