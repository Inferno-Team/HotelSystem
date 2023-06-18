<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingRoomOccupationRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_room_occupation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamp('from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('to')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->double('price')->default(0.0);
            $table->enum('status', ['requested', 'rejected', 'approved'])->default('requested');
            $table->string('type');
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
        Schema::dropIfExists('meeting_room_occupation_requests');
    }
}
