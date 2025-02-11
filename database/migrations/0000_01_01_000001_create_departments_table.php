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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->string('name');
            $table->string('code');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('location')->nullable();
            $table->string('floor')->nullable();
            $table->string('building')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();

            // Self-referential foreign key for hierarchical structure.
            $table->foreign('parent_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            // Omit foreign key for manager_id here to avoid circular dependency.
            // You can add it later via a separate migration if needed:
            // $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
