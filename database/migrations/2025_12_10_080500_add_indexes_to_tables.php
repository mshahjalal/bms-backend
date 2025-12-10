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
        Schema::table('users', function (Blueprint $table) {
            $table->index('tenant_id');
            $table->index('role');
        });

        Schema::table('buildings', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('flats', function (Blueprint $table) {
            $table->index('tenant_id');
            $table->index('building_id');
            $table->index('renter_id');
        });

        Schema::table('bill_categories', function (Blueprint $table) {
            $table->index('tenant_id');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->index('tenant_id');
            $table->index('flat_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('due_date'); // Useful for querying unpaid/overdue bills
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tenant_id']);
            $table->dropIndex(['role']);
        });

        // ... others
    }
};
