<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Demo users
        User::firstOrCreate(
            ['email' => 'admin@smartpos.test'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ],
        );

        User::firstOrCreate(
            ['email' => 'kasir@smartpos.test'],
            [
                'name'     => 'Kasir 1',
                'password' => Hash::make('password'),
                'role'     => 'kasir',
            ],
        );

        // Sample products
        $products = [
            ['name' => 'Indomie Goreng',       'sku' => 'PRD-001', 'price' => 3500,   'stock' => 100, 'category' => 'Makanan'],
            ['name' => 'Teh Botol Sosro',       'sku' => 'PRD-002', 'price' => 5000,   'stock' => 80,  'category' => 'Minuman'],
            ['name' => 'Aqua 600ml',            'sku' => 'PRD-003', 'price' => 4000,   'stock' => 150, 'category' => 'Minuman'],
            ['name' => 'Chitato 68g',           'sku' => 'PRD-004', 'price' => 12000,  'stock' => 50,  'category' => 'Snack'],
            ['name' => 'Pocari Sweat 350ml',    'sku' => 'PRD-005', 'price' => 8000,   'stock' => 60,  'category' => 'Minuman'],
            ['name' => 'Good Day Cappuccino',   'sku' => 'PRD-006', 'price' => 3000,   'stock' => 120, 'category' => 'Minuman'],
            ['name' => 'Roti Sari Roti Coklat', 'sku' => 'PRD-007', 'price' => 7500,   'stock' => 40,  'category' => 'Makanan'],
            ['name' => 'Silverqueen 65g',       'sku' => 'PRD-008', 'price' => 15000,  'stock' => 30,  'category' => 'Snack'],
            ['name' => 'Minyak Goreng 1L',      'sku' => 'PRD-009', 'price' => 18000,  'stock' => 25,  'category' => 'Kebutuhan'],
            ['name' => 'Gula Pasir 1kg',        'sku' => 'PRD-010', 'price' => 16000,  'stock' => 35,  'category' => 'Kebutuhan'],
            ['name' => 'Kopi ABC Susu',         'sku' => 'PRD-011', 'price' => 2500,   'stock' => 200, 'category' => 'Minuman'],
            ['name' => 'Oreo Original',         'sku' => 'PRD-012', 'price' => 10000,  'stock' => 45,  'category' => 'Snack'],
            ['name' => 'Sabun Lifebuoy',        'sku' => 'PRD-013', 'price' => 5500,   'stock' => 70,  'category' => 'Kebutuhan'],
            ['name' => 'Pasta Gigi Pepsodent',  'sku' => 'PRD-014', 'price' => 9000,   'stock' => 55,  'category' => 'Kebutuhan'],
            ['name' => 'Beng-Beng Maxx',        'sku' => 'PRD-015', 'price' => 5000,   'stock' => 3,   'category' => 'Snack'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }

        // Sample customers
        $customers = [
            ['name' => 'Budi Santoso',   'phone' => '081234567890', 'email' => 'budi@email.com',   'address' => 'Jl. Merdeka No. 1'],
            ['name' => 'Siti Nurhaliza', 'phone' => '082345678901', 'email' => 'siti@email.com',   'address' => 'Jl. Sudirman No. 5'],
            ['name' => 'Andi Wijaya',    'phone' => '083456789012', 'email' => 'andi@email.com',   'address' => 'Jl. Gatot Subroto 12'],
            ['name' => 'Dewi Lestari',   'phone' => '084567890123', 'email' => 'dewi@email.com',   'address' => 'Jl. Diponegoro 8'],
            ['name' => 'Rudi Hartono',   'phone' => '085678901234', 'email' => 'rudi@email.com',   'address' => 'Jl. Ahmad Yani 3'],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['email' => $customer['email']], $customer);
        }
    }
}

