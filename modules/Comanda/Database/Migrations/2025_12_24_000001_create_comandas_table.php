<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('table_id')
                ->constrained('tables')
                ->cascadeOnDelete();   
            $table->bigInteger('reabierto')->default(0);
            $table->string('mesero');
            $table->integer('comensales')->default(1);
            $table->bigInteger('category')->default(1); // 'cocina' | 'bar' | 'postres
            $table->bigInteger('priority')->default(1); // 'normal' | 'high'
            $table->bigInteger('status')->default(1); // 'pendiente' | 'preparando' | 'listo' | 'entregado'   
            $table->decimal('total', 10, 2); // total de la comanda
            $table->timestamps();      
        });
    }

    public function down()
    {
        Schema::dropIfExists('comandas');
    }
};
