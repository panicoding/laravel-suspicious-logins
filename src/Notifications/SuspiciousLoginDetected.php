<?php

namespace AdventDev\SuspiciousLogins\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginDetected extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $geoIp;
    public $ip;

    public function __construct($user, $geoIp, $ip)
    {
        $this->user = $user;
        $this->geoIp = $geoIp;
        $this->ip = $ip;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $ip = $this->ip;
        $email = $this->user->email;
        $location = $this->geoIp->city . ', ' . $this->geoIp->country;

        return (new MailMessage)
            ->subject('Suspicious Login Detected')
            ->greeting('Suspicious Login Alert')
            ->line('Your account (' . $email . ') recently logged in from an unrecognized location.')
            ->line('The logged in IP address was ' . $ip . ' from ' . $location . '.')
            ->line('If this was you, you can ignore this message. If not, please reset your account password ASAP.');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
