<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertGrarage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('garages')->insert([
            "hotel_id" => 1,
            "price" => rand(1000, 10_000),
        ]);
        DB::table("parking_spaces")->insert([
            "garage_id" => 1,
            "number" => 1
        ]);
        DB::table("parking_spaces")->insert([
            "garage_id" => 1,
            "number" => 2
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
