<?php

namespace App\Listeners;

use App\Services\IpLocationService;
use Illuminate\Auth\Events\Login;

class SetUserLocationFromIp
{
    public function __construct(protected IpLocationService $locationService)
    {
    }

    public function handle(Login $event): void
    {
        $user = $event->user;

        // Kalau user sudah pernah isi alamat sendiri, jangan ditimpa otomatis.
        if (! empty($user->address)) {
            return;
        }

        $location = $this->locationService->fromRequest();

        if ($location) {
            $user->forceFill([
                'city' => $location['city'],
                'address' => $location['address'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
            ])->save();
        }
    }
}
