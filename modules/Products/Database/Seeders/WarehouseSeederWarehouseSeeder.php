<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('warehouses')->insertOrIgnore([
            [
                'name'        => 'Almacén General',
                'code'        => 'GENERAL',
                'is_default'  => true,
                'status'      => true,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]
        ]);
    }
}
