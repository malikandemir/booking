<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('car_bookings', 'bookings');
    }

    public function down()
    {
        Schema::rename('bookings', 'car_bookings');
    }
};
