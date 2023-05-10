<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditCustomerParkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_parkings', function (Blueprint $table) {
            $table->timestamp('from')->default(DB::raw('CURRENT_TIMESTAMP'))->after('parking_id');
            $table->timestamp('to')->default(DB::raw('CURRENT_TIMESTAMP'))->after('from');
        });
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
