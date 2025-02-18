<?php

namespace App\Livewire\Notifications;

use Illuminate\View\View;
use Livewire\Component;

class Dropdown extends Component
{
    public $showDropdown = false;
    public $notifications;
    public $hasUnread;

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $this->notifications = auth()->user()
            ->notifications()
            ->latest()
            ->limit(5)
            ->get();

        $this->hasUnread = auth()->user()->unreadNotifications()->exists();
    }

    public function markAsRead($notificationId): void
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        if ($notification->link) {
            $this->redirect($notification->link);
        }

        $this->loadNotifications();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->loadNotifications();
    }

    public function render(): View
    {
        return view('livewire.notifications.dropdown');
    }
}
