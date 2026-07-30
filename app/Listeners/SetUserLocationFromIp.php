<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\IpLocationService;
use Illuminate\Auth\Events\Login;

class SetUserLocationFromIp
{
    public function __construct(protected IpLocationService $locationService)
    {
    }

    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $user = $event->user;

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