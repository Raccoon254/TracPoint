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
        Schema::table('asset_categories', function (Blueprint $table) {
            // Add organization_id after the id column.
            $table->unsignedBigInteger('organization_id')->nullable()->after('id');

            // Add a foreign key constraint referencing the organizations table.
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade'); // Adjust onDelete action as needed.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_categories', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });
    }
};
