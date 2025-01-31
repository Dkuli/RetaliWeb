<?php

namespace App\Notifications;

use App\Models\Questionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\FcmService;

class NewQuestionnaireAssigned extends Notification
{
    use Queueable;

    public function __construct(
        public Questionnaire $questionnaire
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'fcm'];
    }

    public function toFcm($notifiable)
    {
        return (new FcmService())->sendNotification(
            [$notifiable->fcm_token],
            'Kuesioner Baru: ' . $this->questionnaire->title,
            $this->questionnaire->description ?? 'Silakan isi kuesioner yang telah ditugaskan',
            [
                'type' => 'new_questionnaire',
                'questionnaire_id' => $this->questionnaire->id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ]
        );
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Kuesioner Baru: ' . $this->questionnaire->title,
            'message' => $this->questionnaire->description ?? 'Silakan isi kuesioner yang telah ditugaskan',
            'url' => route('filament.admin.resources.questionnaires.preview', $this->questionnaire)
        ];
    }
}
