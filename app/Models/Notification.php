<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Notification extends Model
{
    protected $fillable = [
        'uuid',
        'type',
        'from_user_id',
        'notifiable_id',
        'notifiable_type',
        'data',
        'message',
        'link',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            $notification->uuid = (string) Str::uuid();
        });
    }

    /**
     * Get the user who created the notification.
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Get the notifiable entity (user receiving the notification).
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     */
    public function markAsUnread(): void
    {
        if (! is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
        }
    }

    /**
     * Determine if the notification has been read.
     */
    public function read(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if the notification is unread.
     */
    public function unread(): bool
    {
        return $this->read_at === null;
    }

    /**
     * Create a new notification.
     */
    public static function create(
        string $type,
        Model $notifiable,
        string $message,
        array $data = [],
        ?string $link = null,
        ?User $fromUser = null
    ): self {
        return static::query()->create([
            'type' => $type,
            'from_user_id' => $fromUser?->id,
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'data' => $data,
            'message' => $message,
            'link' => $link,
        ]);
    }
}
