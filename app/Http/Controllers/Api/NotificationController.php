<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\TourLeader;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        /** @var TourLeader $tourLeader */
        $tourLeader = auth()->user();

        try {
            $notifications = $tourLeader
                ->notifications()
                ->latest()
                ->paginate(20);

            return $this->successResponse($notifications);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to fetch notifications', 500);
        }
    }

    public function markAsRead(Notification $notification): JsonResponse
    {
        /** @var TourLeader $tourLeader */
        $tourLeader = auth()->user();

        if ($notification->tour_leader_id !== $tourLeader->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        try {
            $notification->update(['is_read' => true]);
            return $this->successResponse($notification->fresh());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to mark notification as read', 500);
        }
    }

    public function markAllAsRead(): JsonResponse
    {
        /** @var TourLeader $tourLeader */
        $tourLeader = auth()->user();

        try {
            $tourLeader->notifications()->update(['is_read' => true]);
            return $this->successResponse(null, 'All notifications marked as read');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to mark all notifications as read', 500);
        }
    }

    public function getUnreadCount(): JsonResponse
    {
        /** @var TourLeader $tourLeader */
        $tourLeader = auth()->user();

        try {
            $count = $tourLeader
                ->notifications()
                ->where('is_read', false)
                ->count();

            return $this->successResponse(['count' => $count]);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get unread count', 500);
        }
    }
}
