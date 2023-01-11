<?php

namespace Database\Seeders;

use App\Enums\statusEnum;
use App\Models\Products\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addProduct();
    }

    protected function addProduct(){
        for($i=0;$i<7;$i++){
            $percentOverDiscount = Product::query()
                ->firstOrCreate(
                    [
                        'name'     => fake()->name,
                        'category' => fake()->biasedNumberBetween(1,4),
                    ],
                    [
                        'price'     => fake()->biasedNumberBetween(5,15),
                        'stock'     => fake()->biasedNumberBetween(10,90),
                        'status'    => statusEnum::ACTIVE->value
                    ]
                );
        }
    }


}
