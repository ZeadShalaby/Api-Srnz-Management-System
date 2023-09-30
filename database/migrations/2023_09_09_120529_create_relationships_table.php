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
        Schema::table('orders', function (Blueprint $table) {

            $table->foreign('department_id')->references('id')->on('departments');

            $table->foreign('user_id')->references('id')->on('users');
            

        });

        Schema::table('favourites', function (Blueprint $table) {

            $table->foreign('orders_id')->references('id')->on('orders');

            $table->foreign('user_id')->references('id')->on('users');

        });

        Schema::table('cards', function (Blueprint $table) {

            $table->foreign('orders_id')->references('id')->on('orders');

            $table->foreign('user_id')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
