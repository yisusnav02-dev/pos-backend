<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->default(100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_warehouse');
    }
};
