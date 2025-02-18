<?php

namespace App\Livewire\Notifications;

use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAll extends Component
{
    use WithPagination;

    public function getNotificationsProperty()
    {
        return auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);
    }

    public function markAsRead($notificationId): void
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        if ($notification->link) {
            $this->redirect($notification->link);
        }
    }
    public function render(): View
    {
        return view('livewire.notifications.show-all', [
            'notifications' => $this->notifications
        ]);
    }
}
