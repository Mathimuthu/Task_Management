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
        Schema::table('task_details', function (Blueprint $table) {
            // Remove the old columns if needed
            $table->dropColumn('pending_date');
            $table->dropColumn('inprogress_date');
            $table->dropColumn('completed_date');
            $table->dropColumn('cancelled_date');

            // Add new columns
            $table->json('meta_data')->nullable(); // Add new meta_data column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_details', function (Blueprint $table) {
            // If we need to rollback, we can reverse the changes
            $table->dropColumn('meta_data');

            // Optionally, you can re-add the old columns
            $table->timestamp('pending_date')->nullable();
            $table->timestamp('inprogress_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamp('cancelled_date')->nullable();
        });
    }
};
