<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Postres',
                'description' => 'Productos dulces y postres',
                'status' => true,
                'sort_order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Bebidas frías y calientes',
                'status' => true,
                'sort_order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Platos Fuertes',
                'description' => 'Platos principales',
                'status' => true,
                'sort_order' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Entradas',
                'description' => 'Entradas y aperitivos',
                'status' => true,
                'sort_order' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
