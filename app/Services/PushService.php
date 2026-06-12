<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushService
{
    private function webPush(): WebPush
    {
        return new WebPush([
            'VAPID' => [
                'subject'    => config('webpush.subject'),
                'publicKey'  => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ]);
    }

    private function send(WebPush $webPush, iterable $subscriptions, string $payload): void
    {
        foreach ($subscriptions as $sub) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys'     => ['p256dh' => $sub->p256dh, 'auth' => $sub->auth],
                ]),
                $payload
            );
        }

        foreach ($webPush->flush() as $report) {
            if ($report->isSubscriptionExpired()) {
                PushSubscription::where('endpoint', $report->getEndpoint())->delete();
            }
        }
    }

    /** Diffuse à tous les abonnés (nouveau produit, promo…) */
    public function broadcast(string $title, string $body, string $url = '/products', ?string $image = null): void
    {
        $subscriptions = PushSubscription::all();
        if ($subscriptions->isEmpty()) return;

        $this->send($this->webPush(), $subscriptions, $this->payload($title, $body, $url, $image));
    }

    /** Notifie uniquement un utilisateur identifié (confirmation de commande…) */
    public function notifyUser(int|User $user, string $title, string $body, string $url = '/', ?string $image = null): void
    {
        $userId = $user instanceof User ? $user->id : $user;

        $subscriptions = PushSubscription::where('user_id', $userId)->get();

        // Fallback : chercher aussi par email si l'utilisateur s'est inscrit avant login
        if ($subscriptions->isEmpty() && $user instanceof User) {
            $subscriptions = PushSubscription::whereNull('user_id')
                ->whereHas('user', fn ($q) => $q->where('email', $user->email))
                ->get();
        }

        if ($subscriptions->isEmpty()) return;

        $this->send($this->webPush(), $subscriptions, $this->payload($title, $body, $url, $image));
    }

    private function payload(string $title, string $body, string $url, ?string $image): string
    {
        return json_encode(array_filter([
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
            'image' => $image,
        ]));
    }
}
