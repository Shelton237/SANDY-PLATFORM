<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\CampaignConfirmationMail;
use App\Mail\CampaignNotificationMail;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia; // Import manquant ajouté

class CampaignController extends Controller
{
    public function create()
    {
        return inertia('StartCampaign', [
            'siteTypes' => [
                'Restaurants', 'Bars', 'Hôtels', 'Agences',
                'Bus/Transport', 'Centres commerciaux', 'Mix'
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'role' => 'required|string',
            'preferredSites' => 'required|array',
            'hasAssets' => 'required|string',
            'spotDuration' => 'required|string',
            'budget' => 'required|string',
            'startDate' => 'required|date',
            'campaignDuration' => 'required|string',
            'isUrgent' => 'boolean',
            'customDuration' => 'nullable|string'
        ]);

        try {
            $campaign = Campaign::create([
                'name' => $validated['name'],
                'company' => $validated['company'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'preferred_sites' => $validated['preferredSites'],
                'has_assets' => $validated['hasAssets'],
                'spot_duration' => $validated['spotDuration'],
                'budget' => $validated['budget'],
                'start_date' => $validated['startDate'],
                'campaign_duration' => $validated['campaignDuration'],
                'is_urgent' => $validated['isUrgent'] ?? false,
                'custom_duration' => $validated['customDuration'] ?? null
            ]);

            // Envoi des emails
            $this->sendEmails($validated, $campaign->id);

            // RÉPONSE INERTIA CORRECTE - Deux options :

            // OPTION 1: Redirection avec session flash (nécessite middleware)
            return redirect()->route('campaign.create')
                    ->with('success', 'Votre demande a été envoyée avec succès!');

            // OPTION 2: Réponse Inertia directe avec message (recommandée)
            // return Inertia::render('StartCampaign', [
            //     'siteTypes' => [
            //         'Restaurants', 'Bars', 'Hôtels', 'Agences',
            //         'Bus/Transport', 'Centres commerciaux', 'Mix'
            //     ],
            //     'flash' => [
            //         'success' => 'Votre demande a été envoyée avec succès!'
            //     ]
            // ]);

        } catch (\Exception $e) {
            Log::error('Erreur création campagne: ' . $e->getMessage());
            
            // Gestion d'erreur pour Inertia
            return back()->withErrors([
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ])->withInput();
        }
    }

    private function sendEmails($data, $campaignId)
    {
        try {
            // Vérification des données avant envoi
            $requiredFields = ['name', 'email', 'company', 'phone', 'startDate', 'budget'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || $data[$field] === null) {
                    Log::warning("Champ null détecté: {$field}");
                    $data[$field] = ''; // Convertir null en string vide
                }
            }

            // Email à l'administrateur (avec gestion d'erreur séparée)
            try {
                Mail::to('contact@ads360.digital')
                    ->send(new CampaignNotificationMail($data, $campaignId));
            } catch (\Exception $e) {
                Log::error("Erreur email admin: " . $e->getMessage());
            }

            // Email de confirmation au client
            try {
                Mail::to($data['email'])
                    ->send(new CampaignConfirmationMail($data));
            } catch (\Exception $e) {
                Log::error("Erreur email client: " . $e->getMessage());
            }

            Log::info("Emails envoyés pour la campagne #{$campaignId}");

        } catch (\Exception $e) {
            Log::error("Erreur envoi emails campagne #{$campaignId}: " . $e->getMessage());
            // Ne pas throw pour ne pas bloquer le formulaire
        }
    }

    public function confirmation()
    {
        return inertia('CampaignConfirmation');
    }

    public function webhook(Request $request)
    {
        // Traitement des webhooks pour les statuts d'emails
        $status = $request->input('event');
        $messageId = $request->input('message-id');
        
        Log::info("Webhook email: {$status} for {$messageId}");
        
        return response()->json(['status' => 'received']);
    }
}