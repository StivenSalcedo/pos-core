<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{

    public function run()
    {
       /* $products = Product::factory(50)->create();

        foreach ($products as $product => $value) {
            $id = TaxRate::all()->random()->id;
            if ($id === 7 || $id === 8) {
                $value->taxRates()->attach($id, ['value' => fake()->randomElement([200, 300, 400])]);
            }else{
                $value->taxRates()->attach($id);
            }
        }*/

        $product = Product::create([
            'barcode' => 12345,
            'reference' => 12345,
            'category_id' => Category::all()->random()->id,
            'name' => 'Ibuprofeno',
            'cost' => 4000,
            'price' => 5000,
            'stock' => 10,
            'quantity' => 20,
            'units' => 200,
            'has_presentations' => '0',
        ]);

        $product->taxRates()->attach(3);

        /*$product->presentations()->create([
            'name' => 'Pastilla',
            'quantity' => 1,
            'price' => 500,
        ]);

        $product->presentations()->create([
            'name' => 'Sobre x10',
            'quantity' => 10,
            'price' => 3000,
        ]);

        $product->presentations()->create([
            'name' => 'Caja x20',
            'quantity' => 20,
            'price' => 5000,
        ]);*/

        $product = Product::create([
            'barcode' => 123456,
            'reference' => 123456,
            'category_id' => Category::all()->random()->id,
            'name' => 'Aspirina',
            'cost' => 3000,
            'price' => 6000,
            'stock' => 20,
            'quantity' => 30,
            'units' => 600,
            'has_presentations' => '0',
        ]);

        $product->taxRates()->attach(3);

       /* $product->presentations()->create([
            'name' => 'Pastilla',
            'quantity' => 1,
            'price' => 600,
        ]);

        $product->presentations()->create([
            'name' => 'Sobre x15',
            'quantity' => 15,
            'price' => 3500,
        ]);

        $product->presentations()->create([
            'name' => 'Caja x30',
            'quantity' => 30,
            'price' => 6000,
        ]);*/
    }
}
