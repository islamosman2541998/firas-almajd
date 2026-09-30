<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as Notifier;
use Throwable;

/**
 * Sends a notification to every active admin (database) and, when set,
 * to the notification e-mail. Failures never break the public form.
 */
class AdminNotifier
{
    public static function send(Notification $notification): void
    {
        try {
            Notifier::send(User::query()->where('is_active', true)->get(), $notification);
        } catch (Throwable $e) {
            report($e);
        }

        $email = setting('general.notify_email');
        if (filled($email) && method_exists($notification, 'toMail')) {
            try {
                Notifier::route('mail', $email)->notify($notification);
            } catch (Throwable $e) {
                report($e);
            }
        }
    }
}
