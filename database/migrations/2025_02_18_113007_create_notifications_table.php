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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique(); // Unique identifier for notifications
            $table->string('type'); // Type of notification (e.g., 'asset_assigned', 'maintenance_due')
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('notifiable_id'); // User receiving the notification
            $table->string('notifiable_type'); // Model type (usually 'App\Models\User')
            $table->text('data'); // JSON data containing notification details
            $table->text('message'); // Human-readable notification message
            $table->string('link')->nullable(); // Optional link to related resource
            $table->timestamp('read_at')->nullable(); // When the notification was read
            $table->timestamps();

            // Add indexes for better performance
            $table->index(['notifiable_id', 'notifiable_type']);
            $table->index('read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
