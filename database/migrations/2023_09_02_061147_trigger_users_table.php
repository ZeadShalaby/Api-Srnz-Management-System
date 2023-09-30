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
        // update trigger
       
        DB::unprepared('
        CREATE TRIGGER tr_User_Default_Member_Role BEFORE UPDATE ON `users` FOR EACH ROW
        BEGIN
        INSERT INTO forget  (`name` , `email` , `gmail` , `password` ,`phone` , `role` ) VALUES (OLD.name , OLD.email , OLD.gmail , OLD.password  ,OLD.phone , OLD.role);

        END
        ');
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
      DB::unprepared('DROP TRIGGER `tr_User_Default_Member_Role`');


    }
};
