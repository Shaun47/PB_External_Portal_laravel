<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $products = ['Mobile', 'Laptop', 'Watch'];
        static $invoice = 20;
        return [
            'product_name' => $products[rand(0,3)],
            'currency' =>  'BDT',
            'amount' => rand(1500, 2000),
            'invoice' => $invoice++, // password
            'status' => 'Pending',
        ];
    }
}
