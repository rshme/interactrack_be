<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Sale status options
        $statuses = ['draft', 'sent', 'paid', 'overdue', 'cancelled'];

        // Product options with prices
        $products = [
            ['name' => 'Basic Package', 'price' => 1000],
            ['name' => 'Premium Package', 'price' => 2500],
            ['name' => 'Enterprise Solution', 'price' => 5000],
            ['name' => 'Consulting Hours', 'price' => 150],
            ['name' => 'Implementation Service', 'price' => 3000],
            ['name' => 'Monthly Support', 'price' => 500],
            ['name' => 'Custom Development', 'price' => 4000],
            ['name' => 'Training Session', 'price' => 800],
        ];

        // Get all customers and users
        $customers = Customer::where('status', 'active')->get();
        $users = User::all();

        // Create sales for active customers
        foreach ($customers as $customer) {
            $numSales = rand(1, 5); // Random number of sales per customer

            for ($i = 0; $i < $numSales; $i++) {
                $status = $faker->randomElement($statuses);
                $invoice_date = $faker->dateTimeBetween('-1 year', 'now');

                // Create sale
                $sale = Sale::create([
                    'customer_id' => $customer->id,
                    'user_id' => $users->random()->id,
                    'invoice_number' => 'INV-' . $faker->unique()->numberBetween(10000, 99999),
                    'status' => $status,
                    'invoice_date' => $invoice_date,
                    'due_date' => Carbon::parse($invoice_date)->addDays(30),
                    'notes' => $faker->optional(0.3)->sentence,
                    'created_at' => $invoice_date,
                ]);

                // Create 1-5 items for each sale
                $numItems = rand(1, 5);
                $total_amount = 0;

                for ($j = 0; $j < $numItems; $j++) {
                    $product = $faker->randomElement($products);
                    $quantity = $faker->numberBetween(1, 5);
                    $unit_price = $product['price'];
                    $subtotal = $quantity * $unit_price;
                    $total_amount += $subtotal;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'description' => $product['name'],
                        'quantity' => $quantity,
                        'unit_price' => $unit_price,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Update sale with total amount and tax
                $tax = $total_amount * 0.1; // 10% tax
                $sale->update([
                    'amount' => $total_amount,
                    'tax' => $tax,
                ]);
            }
        }
    }
}
