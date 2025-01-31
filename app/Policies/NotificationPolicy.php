<?php
namespace App\Policies;

use App\Models\Notification;
use App\Models\TourLeader;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function update(User|TourLeader $user, Notification $notification)
    {
        // If user is a TourLeader, check if they own the notification
        if ($user instanceof TourLeader) {
            return $user->id === $notification->tour_leader_id;
        }

        // For regular users, deny access
        return false;
    }
}
