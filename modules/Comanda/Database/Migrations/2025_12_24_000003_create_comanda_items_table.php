<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comanda_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comanda_id')
                ->constrained('comandas')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->bigInteger('order')->default(1);
            $table->bigInteger('status')->default(1); // 'pendiente' | 'preparando' | 'listo' | 'entregado'  
            $table->string('modidifadores');
            $table->decimal('subtotal', 10, 2); 
            $table->integer('cantidad')->default(1);
            $table->decimal('total', 10, 2); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comanda_items');
    }
};
