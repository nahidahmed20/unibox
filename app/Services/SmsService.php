<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function send($number, $message)
    {
        try {
            if (strpos($number, '0') === 0) {
                $number = '88' . $number;
            }

            $response = Http::timeout(15)->get(env('SMS_API_URL'), [
                'api_key'   => env('SMS_API_KEY'),
                'senderid'  => env('SMS_SENDER_ID'),
                'type'      => 'text', 
                'number'    => $number,
                'message'   => $message,
            ]);

            if ($response->successful()) {
                Log::info("SMS sent successfully to {$number}: " . $response->body());
                return true;
            }

            Log::error("SMS failed to {$number}. Response: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("SMS Exception for {$number}: " . $e->getMessage());
            return false;
        }
    }
}