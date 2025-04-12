<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTicket extends Notification
{
    use Queueable;

    protected $ticketId;
    protected $subject;
    protected $name;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticketId, $subject, $name, $message = null)
    {
        $this->ticketId = $ticketId;
        $this->subject = $subject;
        $this->name = $name;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->greeting($this->name . '!')
            ->line($this->message ?? 'We have received your ticket just now. When done, you will receive a completion notification.')
            ->action('View Ticket', url('/tickets?id=' . $this->ticketId))
            ->line('Thank you for using our system!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticketId,
            'subject' => $this->subject,
            'subject' => $this->subject,
        ];
    }
}
