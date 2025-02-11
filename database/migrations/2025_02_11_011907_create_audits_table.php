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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('auditor_id');
            $table->dateTime('audit_date')->nullable();
            $table->string('previous_condition')->nullable();
            $table->string('new_condition')->nullable();
            $table->boolean('location_verified')->default(false);
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->text('discrepancies')->nullable();
            $table->text('action_taken')->nullable();
            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('auditor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
