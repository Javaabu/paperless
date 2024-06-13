<?php

namespace Javaabu\Paperless\Notifications;

use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Support\StatusEvents\Models\StatusEvent;

;
use Illuminate\Notifications\Messages\MailMessage;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Support\Notifications\Markdown\Tables\Alignments;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationTypeBlueprint;
use Javaabu\Paperless\Support\Notifications\Markdown\Tables\MarkdownRenderer;

abstract class BaseApplicationStatusUpdateNotification extends QuickNotification
{
    public function __construct(
        public Application              $application,
        public StatusEvent              $statusEvent,
        public ApplicationTypeBlueprint $applicationType,
        public bool                     $should_render_details_table = true,
    ) {
    }

    protected function getSubject($notifiable): string
    {
        return __("Application :application_no Status Updated | SAM Portal", [
            'application_no' => $this->application->formatted_id,
        ]);
    }

    protected function getActionText($notifiable): string
    {
        return 'View Application';
    }

    protected function getTitle($notifiable): string
    {
        return "Scout Application Status Updated";
    }

    protected function getMessage($notifiable, $for_sms = false): string
    {
        $message = "Your application :application_no for :application_type status has been changed to :new_status";

        if ($this->getRemarks()) {
            $message .= "\nAdditional notes regarding the update:\n:remarks";
        }

        return $message;
    }

    protected function getRemarks(): ?string
    {
        return $this->application->remarks;
    }

    protected function getMessageParams($notifiable): array
    {
        return [
            'application_no'   => $this->application->formatted_id,
            'application_type' => $this->applicationType->getName(),
            'new_status'       => $this->statusEvent->getStatusClass()->getLabel(),
            'remarks'          => $this->getRemarks(),
            'rating'           => $this->application->star_rating,
        ];
    }

    protected function getThumb($notifiable): ?string
    {
        return $this->application->thumb;
    }

    protected function getUrl($notifiable): ?string
    {
        return route('admin.applications.details', $this->application);
    }

    protected function getModel($notifiable): ?Model
    {
        return $this->application;
    }

    public function toMail($notifiable): MailMessage
    {
        $name = $notifiable->name;

        $mail = (new MailMessage())
            ->subject($this->getSubject($notifiable))
            ->greeting("Hi $name,");

        $lines = explode("\n", $this->translatedMessage($notifiable));

        foreach ($lines as $line) {
            $mail->line($line);
        }

        if ($this->should_render_details_table) {
            $mail->viewData = [
                'table' => $this->getMarkdownTable(),
            ];
        }


        if ($url = $this->getUrl($notifiable)) {
            $mail->action($this->getActionText($notifiable), $url);
        }

        return $mail;
    }

    public function getMarkdownTable(): string
    {
        $data = $this->tableData();

        // unpack the array directly to the constructor
        $renderer = new MarkdownRenderer(...$data);

        return $renderer->render();
    }

    public function tableData(): array
    {
        return [
            'headers'   => ['', ''],
            'alignment' => [
                Alignments::LEFT,
                Alignments::RIGHT,
            ],
            'rows'      => [
                ['**Applicant**',
                    $this->application->applicant?->formatted_name],
                ['**Application No**',
                    "**{$this->application?->formatted_id}**"],
                ['**Application Type**',
                    $this->application->applicationType?->name],
                ['**Status**',
                    "**{$this->statusEvent->getStatusClass()->getLabel()}**"],
                ['**Remarks**',
                    "**{$this->statusEvent->remarks}**"],
                ['**Date**',
                    $this->statusEvent->created_at?->format('jS F Y h:i A')],
            ],
        ];
    }
}
