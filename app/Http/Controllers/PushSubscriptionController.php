<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'p256dh'   => 'required|string',
            'auth'     => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            ['p256dh' => $data['p256dh'], 'auth' => $data['auth'], 'user_id' => $request->user()?->id],
        );

        return response()->json(['status' => 'subscribed']);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $endpoint = $request->validate(['endpoint' => 'required|string'])['endpoint'];

        PushSubscription::where('endpoint', $endpoint)->delete();

        return response()->json(['status' => 'unsubscribed']);
    }
}
