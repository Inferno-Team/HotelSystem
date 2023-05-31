<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('location');
            $table->timestamps();
        });
        DB::table('hotels')->insert([
            'manager_id' => 1,
            'name' => 'Hotel',
            'location' => 'Syria,Aleppo',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotels');
    }
}
