<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;

use App\Models\TourLeader;
use App\Services\FcmService;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification as FilamentNotification;

class SendNotification extends Page
{
    protected static string $resource = NotificationResource::class;
    protected static ?string $model = Notification::class;

    protected static string $view = 'filament.pages.send-notification';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tour_leaders')
                    ->multiple()
                    ->label('Tour Leader')
                    ->options(TourLeader::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('title')
                    ->label('Judul Notifikasi')
                    ->required()
                    ->maxLength(255),

                Textarea::make('message')
                    ->label('Pesan')
                    ->required()
                    ->maxLength(65535),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $fcmService = new FcmService();
        $tourLeaders = TourLeader::whereIn('id', $data['tour_leaders'])->get();

        foreach ($tourLeaders as $tourLeader) {
            // Log untuk memverifikasi token FCM
            Log::info('Attempting to send notification to tour leader:', [
                'tour_leader_id' => $tourLeader->id,
                'tour_leader_name' => $tourLeader->name,
                'fcm_token' => $tourLeader->fcm_token ?? 'No token'
            ]);

            // Simpan notifikasi ke database
            Notification::create([
                'tour_leader_id' => $tourLeader->id,
                'title' => $data['title'],
                'message' => $data['message'],
                'is_read' => false,
            ]);

            // Verifikasi token sebelum mengirim
            if ($tourLeader->fcm_token) {
                try {
                    Log::info('Sending FCM notification with token:', [
                        'token' => $tourLeader->fcm_token
                    ]);

                    $fcmService->sendNotification(
                        [$tourLeader->fcm_token],
                        $data['title'],
                        $data['message']
                    );

                    Log::info('FCM notification sent successfully');
                } catch (\Exception $e) {
                    Log::error('Failed to send FCM notification: ' . $e->getMessage(), [
                        'tour_leader_id' => $tourLeader->id,
                        'fcm_token' => $tourLeader->fcm_token
                    ]);
                }
            } else {
                Log::warning('Tour leader has no FCM token:', [
                    'tour_leader_id' => $tourLeader->id,
                    'name' => $tourLeader->name
                ]);
            }
        }

        FilamentNotification::make()
            ->success()
            ->title('Notifikasi berhasil dikirim')
            ->send();

        $this->form->fill();
    }
}
