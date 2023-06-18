<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingRoomCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_room_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->references('id')->on('meeting_rooms')->cascadeOnDelete();
            $table->foreignId('customer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamp('from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('to')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meeting_room_customers');
    }
}
