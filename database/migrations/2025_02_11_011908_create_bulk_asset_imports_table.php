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
        Schema::create('bulk_asset_imports', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number');
            $table->unsignedBigInteger('imported_by');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedInteger('quantity')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->dateTime('import_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('imported_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('asset_categories')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_asset_imports');
    }
};
