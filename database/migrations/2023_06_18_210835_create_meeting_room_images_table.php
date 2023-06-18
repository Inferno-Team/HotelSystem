<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingRoomImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_room_images', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable();
            $table->foreignId('room_id')->references('id')->on('meeting_rooms')->cascadeOnDelete();
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
        Schema::dropIfExists('meeting_room_images');
    }
}
