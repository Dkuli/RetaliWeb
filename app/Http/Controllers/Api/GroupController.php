<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class GroupController extends Controller
{
    use ApiResponse;

    public function current()
    {
        $group = auth()->user()->currentGroup
            ->with(['pilgrims', 'schedules.activities'])
            ->first();

        return $this->successResponse($group);
    }

    public function pilgrims()
    {
        $pilgrims = auth()->user()->currentGroup->pilgrims()
            ->with('media')
            ->get();

        return $this->successResponse($pilgrims);
    }

    public function schedule()
    {
        $schedule = auth()->user()->currentGroup->schedules()
            ->with('activities')
            ->orderBy('date')
            ->get();

        return $this->successResponse($schedule);
    }
}
