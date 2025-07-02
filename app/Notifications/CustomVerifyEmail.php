<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends BaseVerifyEmail
{
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('¡Confirma tu cuenta en PetWallet!')
            ->greeting('🐾 ¡Bienvenido/a a la familia PetWallet!')
            ->line('Estamos muy contentos de que te unas a nuestra comunidad. Solo falta un paso para empezar a gestionar tus mascotas y recordatorios:')
            ->action('Verificar mi correo', $verificationUrl)
            ->line('Si no creaste una cuenta, puedes ignorar este correo.')
            ->salutation('¡Un abrazo del equipo PetWallet! 🐶🐱');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
