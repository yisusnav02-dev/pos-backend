<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Table');
            $table->string('code')->unique();
            $table->integer('capacity')->default(4);
            // ['available', 'occupied', 'reserved', 'maintenance'], ['indoor', 'outdoor', 'terrace', 'vip'];
            $table->bigInteger('status')->default(1);
            $table->bigInteger('type')->default(1);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->decimal('min_consumption', 10, 2)->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('coordinates')->nullable(); // Para mapas del restaurante
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tables');
    }
};