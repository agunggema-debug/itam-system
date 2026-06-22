<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'IT Manager',
            'email' => 'manager@itam.test',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@itam.test',
            'password' => bcrypt('password'),
            'role' => 'staff_gudang',
        ]);

        User::create([
            'name' => 'Karyawan',
            'email' => 'karyawan@itam.test',
            'password' => bcrypt('password'),
            'role' => 'karyawan',
        ]);

        $categories = ['Laptop', 'Desktop', 'Monitor', 'Printer', 'Server', 'Network', 'Software'];
        $statuses = ['available', 'assigned', 'under_repair', 'broken', 'disposed'];

        for ($i = 1; $i <= 50; $i++) {
            Asset::create([
                'asset_code' => 'ITAM-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => fake()->randomElement(['Dell', 'HP', 'Lenovo', 'Apple', 'ASUS', 'Acer']) . ' ' . fake()->randomElement(['Laptop', 'Desktop', 'Monitor', 'Server']) . ' ' . fake()->numberBetween(1000, 9999),
                'category' => fake()->randomElement($categories),
                'serial_number' => strtoupper(fake()->bothify('SN-####-????')),
                'specification' => fake()->sentence(),
                'location' => fake()->randomElement(['Gudang A', 'Gudang B', 'Lantai 1', 'Lantai 2', 'Lantai 3', 'IT Room']),
                'status' => fake()->randomElement($statuses),
                'purchase_date' => fake()->dateTimeBetween('-3 years', 'now'),
                'purchase_price' => fake()->randomFloat(2, 1000000, 50000000),
                'warranty_expiry' => fake()->dateTimeBetween('now', '+2 years'),
            ]);
        }
    }
}
