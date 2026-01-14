<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class ContactMessageController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only('search', 'status');

        $messages = ContactMessage::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                if ($status === 'handled') {
                    $query->whereNotNull('handled_at');
                } elseif ($status === 'pending') {
                    $query->whereNull('handled_at');
                }
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/ContactMessages/Index', [
            'messages' => $messages,
            'filters' => $filters,
        ]);
    }

    public function show(ContactMessage $contactMessage): Response
    {
        return Inertia::render('Admin/ContactMessages/Show', [
            'message' => $contactMessage,
        ]);
    }

    public function update(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $data = $request->validate([
            'handled' => ['nullable', 'boolean'],
            'note' => ['nullable', 'string'],
        ]);

        if (array_key_exists('handled', $data)) {
            $contactMessage->handled_at = $data['handled']
                ? Carbon::now()
                : null;
        }

        if (!empty($data['note'])) {
            $contactMessage->metadata = [
                ...($contactMessage->metadata ?? []),
                'note' => $data['note'],
            ];
        }

        $contactMessage->save();

        return redirect()
            ->route('admin.contact-messages.show', $contactMessage)
            ->with('success', $contactMessage->handled_at ? 'Message archivé.' : 'Message marqué en attente.');
    }
}
