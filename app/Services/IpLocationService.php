<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpLocationService
{
    public function fromRequest(): ?array
    {
        $ip = request()->ip();

        if (in_array($ip, ['127.0.0.1', '::1']) || app()->environment('local')) {
            $ip = config('services.ip_location.fallback_ip', '36.73.34.1');
        }

        try {
            $response = Http::timeout(4)->get("https://ipapi.co/{$ip}/json/");

            if (! $response->ok() || $response->json('error')) {
                return null;
            }

            $data = $response->json();

            return [
                'city' => $data['city'] ?? null,
                'address' => collect([
                    $data['city'] ?? null,
                    $data['region'] ?? null,
                    $data['country_name'] ?? null,
                ])->filter()->implode(', '),
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::warning('Gagal mengambil lokasi dari IP: '.$e->getMessage());

            return null;
        }
    }
}