<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
        //  $table->string('name_ar')->unique();
            $table->string('name_ar');
            $table->string('name_en')->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->index('user_id');
            $table->bigInteger('department_id')->unsigned();
            $table->index('department_id');
            $table->string('gmail');
            $table->bigInteger('phone');
            $table->bigInteger('price')->unsigned()->nullable();
            $table->string('description');
            $table->string('path');
            $table->bigInteger('view')->unsigned()->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
