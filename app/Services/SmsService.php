<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use NotificationAPI\NotificationAPI;

class SmsService
{
    public function send(string $phoneNumber, string $message): bool
    {
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        if (app()->environment('local')) {
            Log::channel('single')->info('SMS simulé', [
                'to' => $formattedPhone,
                'message' => $message,
            ]);
            
            session()->flash('dev_sms', [
                'to' => $formattedPhone,
                'message' => $message,
            ]);
        }

        if (!config('services.notificationapi.client_id') || !config('services.notificationapi.client_secret')) {
            return true;
        }

        try {
            $notificationapi = new NotificationApi(
              config('services.notificationapi.client_id'),
              config('services.notificationapi.client_secret')
            );

            $response = $notificationapi->send([
              'type' => 'sms',
              'to' => [
                'id' => '1',
                'number' => $formattedPhone,
              ],
                'sms' => [
                    'message' => $message,
                ]
            ]);

            Log::info('SMS envoyé', [
                'response' => $response,
            ]);
        
            return isset($response['status']) && $response['status'] === 'success';
        } catch (\Exception $e) {
            Log::error('Erreur envoi SMS', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage(),
            ]);
        }
        return true;
    }

    public function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($phone, '0')) {
            $phone = '+33' . substr($phone, 1);
        }
        
        return $phone;
    }
}
