<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LuggageScan;
use App\Models\Luggage;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class LuggageHistoryController extends Controller
{
    use ApiResponse;

    /**
     * Get luggage scan history by luggage number
     */
    public function getByLuggageNumber(Request $request, string $luggageNumber)
    {
        // Find luggage
        $luggage = Luggage::where('luggage_number', $luggageNumber)->first();

        if (!$luggage) {
            return $this->errorResponse('Luggage not found', 404);
        }

        // Get scan history with tour leader details
        $history = LuggageScan::with(['tourLeader:id,name,avatar'])
            ->where('luggage_id', $luggage->id)
            ->orderBy('scanned_at', 'desc')
            ->get()
            ->map(function ($scan) {
                return [
                    'id' => $scan->id,
                    'scanned_at' => $scan->scanned_at->format('Y-m-d H:i:s'),
                    'scanned_at_human' => $scan->scanned_at->diffForHumans(),
                    'latitude' => $scan->latitude,
                    'longitude' => $scan->longitude,
                    'tour_leader' => [
                        'id' => $scan->tourLeader->id,
                        'name' => $scan->tourLeader->name,
                        'avatar' => $scan->tourLeader->avatar
                    ]
                ];
            });

        return $this->successResponse([
            'luggage' => [
                'number' => $luggage->luggage_number,
                'pilgrim_name' => $luggage->pilgrim_name,
                'phone' => $luggage->phone,
                'group' => $luggage->group
            ],
            'history' => $history
        ]);
    }

    /**
     * Get scan history for logged in tour leader
     */
    public function getMyScans(Request $request)
    {
        $tourLeader = $request->user();

        $scans = LuggageScan::with(['luggage'])
            ->where('tour_leader_id', $tourLeader->id)
            ->orderBy('scanned_at', 'desc')
            ->paginate(20)
            ->through(function ($scan) {
                return [
                    'id' => $scan->id,
                    'scanned_at' => $scan->scanned_at->format('Y-m-d H:i:s'),
                    'scanned_at_human' => $scan->scanned_at->diffForHumans(),
                    'latitude' => $scan->latitude,
                    'longitude' => $scan->longitude,
                    'luggage' => [
                        'number' => $scan->luggage->luggage_number,
                        'pilgrim_name' => $scan->luggage->pilgrim_name,
                        'group' => $scan->luggage->group,
                        'phone' => $scan->luggage->phone
                    ]
                ];
            });

        return $this->successResponse($scans);
    }

    /**
     * Get scan statistics for logged in tour leader
     */
    public function getMyStats(Request $request)
    {
        $tourLeader = $request->user();

        $stats = [
            'total_scans' => LuggageScan::where('tour_leader_id', $tourLeader->id)->count(),
            'today_scans' => LuggageScan::where('tour_leader_id', $tourLeader->id)
                ->whereDate('scanned_at', today())
                ->count(),
            'unique_luggage' => LuggageScan::where('tour_leader_id', $tourLeader->id)
                ->distinct('luggage_id')
                ->count(),
            'last_scan' => LuggageScan::where('tour_leader_id', $tourLeader->id)
                ->latest('scanned_at')
                ->first()?->scanned_at?->diffForHumans()
        ];

        return $this->successResponse($stats);
    }
}
