<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the purpose column if it exists
            if (Schema::hasColumn('bookings', 'purpose')) {
                $table->dropColumn('purpose');
            }
            
            // Add description column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'description')) {
                $table->text('description')->nullable();
            }

            // Add approved_by and approved_at columns if they don't exist
            if (!Schema::hasColumn('bookings', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }

            // Drop rejection_reason if it exists
            if (Schema::hasColumn('bookings', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['description', 'approved_by', 'approved_at']);
            $table->string('purpose')->nullable();
            $table->string('rejection_reason')->nullable();
        });
    }
};
