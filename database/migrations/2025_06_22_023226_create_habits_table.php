<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nombre');
            $table->enum('categoria', ['salud', 'productividad', 'bienestar', 'aprendizaje']);
            $table->integer('dias_racha')->default(0);
            $table->integer('progreso_actual')->default(0);
            $table->integer('progreso_total')->default(7);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('habits');
    }
};