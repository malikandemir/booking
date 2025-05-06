<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'car_id')) {
                $table->dropColumn('car_id');
            }
            if (!Schema::hasColumn('bookings', 'item_id')) {
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'item_id')) {
                $table->dropForeign(['item_id']);
                $table->dropColumn('item_id');
            }
            if (!Schema::hasColumn('bookings', 'car_id')) {
                $table->integer('car_id')->nullable();
            }
        });
    }
};
