<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class TestFcmNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return [FcmChannel::class,'database'];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage(notification: new FcmNotification(
                title: "Test notification",
                body: "Your trip starts now. Please turn on your location.",
                image: "https://res.cloudinary.com/dnrhne5fh/image/upload/v1746387958/ekql8ifrndud5vnwpahk.png"
            )));
    }

    public function toDatabase($notifiable)
    {
        return[
            "icon" => "https://res.cloudinary.com/dnrhne5fh/image/upload/v1746387958/ekql8ifrndud5vnwpahk.png",
            "title" => "Test notification",
            "description" => "Your trip starts now. Please turn on your location."
        ];
    }
}
