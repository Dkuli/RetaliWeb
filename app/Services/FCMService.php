<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FcmService
{
    public function sendNotification($tokens, $title, $body, $data = [])
    {
        try {
            Log::info('Firebase credentials path:', [
                'path' => storage_path('app/firebase/google-services.json'),
                'exists' => file_exists(storage_path('app/firebase/google-services.json')),
                'readable' => is_readable(storage_path('app/firebase/google-services.json'))
            ]);

            $messaging = Firebase::messaging();

            $notification = Notification::create($title, $body);

            $message = CloudMessage::new()
                ->withNotification($notification);

            if (!empty($data)) {
                $message = $message->withData($data);
            }

            Log::info('Attempting to send FCM notification', [
                'tokens' => $tokens,
                'title' => $title,
                'body' => $body
            ]);

            $response = $messaging->sendMulticast($message, $tokens);

            Log::info('FCM Response', [
                'successes' => $response->successes()->count(),
                'failures' => $response->failures()->count(),
                'valid_tokens' => $response->validTokens(),
                'invalid_tokens' => $response->invalidTokens(),
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('FCM Notification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
