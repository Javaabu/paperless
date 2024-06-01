<?php

namespace Javaabu\Paperless\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;

abstract class QuickNotification extends BaseNotification implements ShouldQueue
{
    /**
     * Get the title
     *
     * @param $notifiable
     * @return string
     */
    protected abstract function getTitle($notifiable): string;

    /**
     * Get the message
     *
     * @param        $notifiable
     * @param  bool  $for_sms
     * @return string
     */
    protected abstract function getMessage($notifiable, bool $for_sms = false): string;

    /**
     * Get the message params
     *
     * @param $notifiable
     * @return array
     */
    protected abstract function getMessageParams($notifiable): array;

    /**
     * Get the thumb
     *
     * @param $notifiable
     * @return string|null
     */
    protected abstract function getThumb($notifiable): ?string;

    /**
     * Get the url
     *
     * @param $notifiable
     * @return string|null
     */
    protected abstract function getUrl($notifiable): ?string;

    /**
     * Get the model
     *
     * @param $notifiable
     * @return Model|null
     */
    protected abstract function getModel($notifiable): ?Model;

    /**
     * Get the action text
     *
     * @param $notifiable
     * @return string
     */
    protected function getActionText($notifiable): string
    {
        return 'More Info';
    }

    /**
     * Get the translated version of the message
     *
     * @param          $notifiable
     * @param  string  $locale
     * @param  bool    $for_sms
     * @return string
     */
    protected function translatedMessage($notifiable, string $locale = 'en', bool $for_sms = false): string
    {
        return __($this->getMessage($notifiable, $for_sms), $this->translatedMessageParams($notifiable, $locale), $locale);
    }

    /**
     * Get the translated message parameters
     *
     * @param          $notifiable
     * @param  string|null  $locale
     * @return array
     */
    protected function translatedMessageParams($notifiable, string $locale = null): array
    {
        return $this->getMessageParams($notifiable);
    }

    /**
     * Ge the sms representation of the message
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function toSms($notifiable): string
    {
        $message = $this->translatedMessage($notifiable, for_sms: true);

        return str($message)->remove('**')->toString();
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $name = $notifiable->name;

        $mail = (new MailMessage)
            ->subject($this->getTitle($notifiable))
            ->greeting("Hi $name,");

        $lines = explode("\n", $this->translatedMessage($notifiable));

        foreach ($lines as $line) {
            $mail->line($line);
        }

        if ($url = $this->getUrl($notifiable)) {
            $mail->action($this->getActionText($notifiable), $url);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        $message = str($this->getMessage($notifiable))->remove('**')->toString();

        return $this->formatDbNotification(
            $message,
            $this->getMessageParams($notifiable),
            $this->getUrl($notifiable),
            $this->getModel($notifiable),
            $this->getThumb($notifiable)
        );
    }
}
