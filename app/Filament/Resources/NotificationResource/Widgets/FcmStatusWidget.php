<?php
// app/Filament/Resources/NotificationResource/Widgets/FcmStatusWidget.php
namespace App\Filament\Resources\NotificationResource\Widgets;

use Filament\Widgets\Widget;
use Kreait\Firebase\Messaging;
use App\Models\Notification;

class FcmStatusWidget extends Widget
{
    protected static string $view = 'filament.resources.notification.widgets.fcm-status';

    public ?string $fcmStatus = null;
    public array $lastDeliveryStats = [];

    public function mount()
    {
        $this->checkFcmStatus();
        $this->getDeliveryStats();
    }

    protected function checkFcmStatus()
    {
        try {
            $messaging = app(Messaging::class);
            $this->fcmStatus = 'Connected';
        } catch (\Exception $e) {
            $this->fcmStatus = 'Error: ' . $e->getMessage();
        }
    }

    protected function getDeliveryStats()
    {
        // Get last 24h stats
        $this->lastDeliveryStats = [
            'sent' => Notification::where('created_at', '>=', now()->subDay())->count(),
            'delivered' => Notification::where('created_at', '>=', now()->subDay())
                ->whereNotNull('delivered_at')
                ->count(),
        ];
    }
}
