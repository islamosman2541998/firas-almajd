<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification
{
    public function __construct(public ContactMessage $message) {}

    public function via(object $notifiable): array
    {
        return $notifiable instanceof AnonymousNotifiable ? ['mail'] : ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'message',
            'icon' => 'bi-envelope',
            'id' => $this->message->id,
            'name' => $this->message->name,
            'excerpt' => str($this->message->message)->limit(90)->toString(),
            'url' => route('admin.messages.index', ['open' => $this->message->id]),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('admin.notifications.new_message', ['name' => $this->message->name]))
            ->line($this->message->name.' — '.$this->message->phone)
            ->line($this->message->message)
            ->action(__('admin.common.open'), route('admin.messages.index', ['open' => $this->message->id]));
    }
}
