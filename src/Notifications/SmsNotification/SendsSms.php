<?php
/**
 * Methods that sms notifications should have
 */

namespace Javaabu\Paperless\Notifications\SmsNotification;

trait SendsSms
{

    /**
     * Get the sms driver
     *
     * @return string
     */
    public function getSmsDriver()
    {
        return config('mobilenumber.sms_driver');
    }

    /**
     * Get the sms channel
     *
     * @return string
     */
    public function getSmsChannel()
    {
        $sms_driver = $this->getSmsDriver();
        return config('mobilenumber.sms_channels.' . $sms_driver);
    }

    /**
     * Get the twilio representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function toTwilio($notifiable)
    {
        return $this->toSms($notifiable);
    }

    /**
     * Get the dhiraagu representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function toDhiraagu($notifiable)
    {
        return $this->toSms($notifiable);
    }
}
