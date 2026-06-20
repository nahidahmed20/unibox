<?php

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            $settings = Setting::pluck('value', 'key')->toArray();
        }

        return $settings[$key] ?? $default;
    }

    function getColorStock($productId, $colorId)
    {
        return DB::table('purchase_details')
            ->where('product_id', $productId)
            ->where('color_id', $colorId)
            ->sum('quantity');
    }

    if (!function_exists('sendSms')) {

    function sendSms($phone, $message)
    {
        try {

            $response = Http::timeout(15)->get('http://bulksmsbd.net/api/smsapi', [
                'api_key'  => env('BULKSMSBD_API_KEY'),
                'type'     => 'text',
                'number'   => $phone,
                'senderid' => env('BULKSMSBD_SENDER_ID'),
                'message'  => $message,
            ]);

            return [
                'success' => $response->successful(),
                'response' => $response->body(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'response' => $e->getMessage(),
            ];
        }
    }
}
}
