<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\PaymentMethod;
use App\Models\Size;
use App\Models\StoreType;
use App\TypeSize;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);

        if (!Color::first()) {
            $this->call(ColorsSeeder::class);
        }

        if (!TypeSize::first()) {
            $this->call(TypeSizeSeeder::class);
        }
        
        if (!Size::first()) {
            $this->call(SizesSeeder::class);
        }
        

        $this->call(BaseCategorySeeder::class);

        if (!Category::first() && !Brand::first()) {
            $this->call(CategoriesAndBrandsSeeder::class);
        }
        

        if (!StoreType::first()) {
            $this->call(StoreTypesSeeder::class);
        }

        if (!PaymentMethod::first()) {
            $this->call(PaymentMethodSeeder::class);
        }
    }
}
