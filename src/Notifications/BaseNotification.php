<?php

namespace Javaabu\Paperless\Notifications;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Javaabu\Paperless\Notifications\SmsNotification\SendsSms;
use Javaabu\Paperless\Notifications\SmsNotification\SmsNotification;

abstract class BaseNotification extends Notification implements SmsNotification
{
    use Queueable;
    use SendsSms;
    use SerializesModels;

    /**
     * @var string
     */
    protected static $morphClass;

    /**
     * Get the morph class
     *
     * @return string
     */
    public static function getMorphClass(): string
    {
        return static::$morphClass ?: Str::snake(class_basename(static::class));
    }

    /**
     * Get the notification title
     *
     * @return string
     */
    public static function getNotificationTitle(): string
    {
        return Str::title(
            Str::snake(class_basename(static::class), ' ')
        );
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues(): array
    {
        return [
            'mail'                 => config('notifications.mail_queue'),
            $this->getSmsChannel() => config('notifications.sms_queue'),
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        $channels = [];

        if ($this->shouldSendDb($notifiable) && $this->wantsToReceiveVia($notifiable, 'database')) {
            $channels[] = 'database';
        }

        if ($this->shouldSendEmail($notifiable) && $this->wantsToReceiveVia($notifiable, 'mail')) {
            $channels[] = 'mail';
        }

        if (config('mobilenumber.should_deliver_sms_notifications')) {
            if ($this->shouldSendSms($notifiable) && $this->wantsToReceiveVia($notifiable, 'sms')) {
                $channels[] = $this->getSmsChannel();
            }
        }

        return $channels;
    }

    /**
     * Whether the current user can receive this notification
     *
     * @param $notifiable
     * @return bool
     */
    public static function canReceive($notifiable): bool
    {
        return true;
    }

    /**
     * Check whether notifiable wants to receive
     * this notification via the specified channel
     *
     * @param  mixed  $notifiable
     * @param         $channel
     * @return bool
     */
    public function wantsToReceiveVia($notifiable, $channel): bool
    {
        if (method_exists($notifiable, 'wantsToReceiveNotificationVia')) {
            $type = static::getMorphClass();

            return $notifiable->wantsToReceiveNotificationVia($type, $channel);
        }

        return true;
    }

    /**
     * Check whether db notification should be sent
     *
     * @param  mixed  $notifiable
     * @return bool
     */
    public function shouldSendDb($notifiable): bool
    {
        return true;
    }

    /**
     * Check whether email should be sent
     *
     * @param  mixed  $notifiable
     * @return bool
     */
    public function shouldSendEmail($notifiable): bool
    {
        return ! empty($notifiable->email);
    }

    /**
     * Check whether sms should be sent
     *
     * @param  mixed  $notifiable
     * @return bool
     */
    public function shouldSendSms($notifiable): bool
    {
        return ! empty($notifiable->routeNotificationForSms());
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function toSms($notifiable): string
    {
        return '';
    }

    /**
     * Parse the data for db notification format
     *
     * @param              $message
     * @param  array       $message_params
     * @param  null        $url
     * @param  Model|null  $model
     * @param  null        $thumb
     * @return array
     */
    public function formatDbNotification(
        $message,
        array $message_params = [],
        $url = null,
        ?Model $model = null,
        $thumb = null
    ): array {
        $params = $this->parseMessageParams($message_params);
        $translated_message = __($message, $params);


        return [
            'message'    => $translated_message,
            'url'        => $url,
            'thumb'      => $thumb,
            'model_type' => $model?->getMorphClass(),
            'model_id'   => $model?->getKey(),
        ];
    }

    /**
     * Parse the data for db notification format
     *
     * @param  array  $params
     * @param  null   $locale
     * @return array
     */
    public function parseMessageParams(array $params, $locale = null): array
    {
        return $params;
    }
}
