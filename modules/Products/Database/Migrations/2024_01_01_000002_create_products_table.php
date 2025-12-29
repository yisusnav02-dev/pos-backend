<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();
            $table->bigInteger('id_unit')->default(1);
            // Información adicional
            $table->string('image')->nullable();
            // Estado
            $table->boolean('availability')->default(true);
            $table->boolean('status')->default(true);
            // Producción / cocina
            $table->integer('preparation_time')->default(0); // minutos
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
