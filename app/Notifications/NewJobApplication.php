<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobApplication extends Notification
{
    public function __construct(public JobApplication $application) {}

    public function via(object $notifiable): array
    {
        return $notifiable instanceof AnonymousNotifiable ? ['mail'] : ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $this->application->loadMissing('job');

        return [
            'kind' => 'application',
            'icon' => 'bi-person-badge',
            'id' => $this->application->id,
            'name' => $this->application->name,
            'job' => $this->application->job?->getTranslations('title'),
            'url' => route('admin.applications.index', ['open' => $this->application->id]),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('admin.notifications.new_application', ['name' => $this->application->name]))
            ->line($this->application->name.' — '.$this->application->phone.' — '.$this->application->email)
            ->line($this->application->summary)
            ->action(__('admin.common.open'), route('admin.applications.index', ['open' => $this->application->id]));
    }
}
