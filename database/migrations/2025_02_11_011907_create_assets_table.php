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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('barcode')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->enum('status', ['available', 'assigned', 'in_maintenance', 'retired']);
            $table->enum('condition', ['new', 'good', 'fair', 'poor']);
            $table->boolean('is_mobile')->default(false);
            $table->unsignedBigInteger('current_department_id')->nullable();
            $table->string('current_location')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('assigned_date')->nullable();
            $table->date('expected_return_date')->nullable();
            $table->float('depreciation_rate')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys:
            $table->foreign('category_id')->references('id')->on('asset_categories')->onDelete('cascade');
            $table->foreign('current_department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
