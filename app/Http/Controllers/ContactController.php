<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact/Index', [
            'contactInfo' => [
                'email' => 'bonjour@sandy-juice.com',
                'phone' => '+237 655 69 98 25',
                'address' => 'Atelier Sandy Juice, Yaoundé, Cameroun',
                'hours' => 'Lun - Sam : 7h - 19h',
                'city' => 'Yaoundé, Cameroun',
            ],
            'socials' => [
                [
                    'label' => 'Facebook',
                    'url' => 'https://web.facebook.com/profile.php?id=61569881558265&_rdc=1&_rdr#',
                    'icon' => 'bi-facebook',
                    'handle' => 'Sandy Juice',
                ],
                [
                    'label' => 'TikTok',
                    'url' => 'https://www.tiktok.com/@sandyjuice',
                    'icon' => 'bi-tiktok',
                    'handle' => '@sandyjuice',
                ],
            ],
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = ContactMessage::create([
            ...$data,
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        Log::channel('stack')->info('Contact message', $message->toArray() + [
            'source' => 'public_contact_form',
        ]);

        return back()->with('success', 'Merci, nous revenons vers vous rapidement !');
    }
}
