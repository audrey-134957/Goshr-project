<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailToAdminReferingToDeletingProfile extends Notification
{
    use Queueable;

    protected $adminUser;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $adminUser)
    {
        $this->adminUser = $adminUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Suppression de votre compte')
        ->markdown('mails.admins.deletion.admin-account-deleted', ['adminUser' => $notifiable]);
    }
}
