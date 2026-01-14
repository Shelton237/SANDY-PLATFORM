<?php

namespace App\Http\Controllers;

use App\Mail\PartnershipConfirmationMail;
use App\Mail\PartnershipNotificationMail;
use App\Models\Partnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PartnershipController extends Controller
{
    public function create()
    {
        return inertia('Partnership', [
            'establishmentTypes' => [
                'Restaurant', 'Bar', 'Hôtel', 'Agence de voyage', 'Commerce', 'Autre'
            ],
            'screenOptions' => [
                '1', '2', '3', '4+'
            ],
            'contactTimes' => [
                ['value' => 'morning', 'label' => 'Matin'],
                ['value' => 'afternoon', 'label' => 'Après-midi'],
                ['value' => 'evening', 'label' => 'Soir']
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'establishment' => 'required|string|max:255',
            'phone' => 'required|string',
            'email' => 'required|email',
            'location' => 'required|string',
            'establishmentType' => 'required|string',
            'screens' => 'required|string',
            'contactTime' => 'required|array',
            'consent' => 'required|accepted'
        ]);

        try {
            $partnership = Partnership::create([
                'full_name' => $validated['fullName'],
                'establishment' => $validated['establishment'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'location' => $validated['location'],
                'establishment_type' => $validated['establishmentType'],
                'screens' => $validated['screens'],
                'contact_time' => $validated['contactTime'],
                'consent' => (bool)$validated['consent']
            ]);

            // Envoi des emails
            $this->sendEmails($validated, $partnership->id);

            return redirect()->route('partnership.create')
                    ->with('success', 'Votre demande de partenariat a été envoyée avec succès!');

        } catch (\Exception $e) {
            Log::error('Erreur création partenariat: ' . $e->getMessage());
            
            return back()->withErrors([
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ])->withInput();
        }
    }

    private function sendEmails($data, $partnershipId)
    {
        try {
            // Vérification des données avant envoi
            $requiredFields = ['fullName', 'email', 'establishment', 'phone', 'location'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || $data[$field] === null) {
                    Log::warning("Champ null détecté: {$field}");
                    $data[$field] = ''; // Convertir null en string vide
                }
            }

            // Email à l'administrateur
            try {
                Mail::to('contact@ads360.digital')
                    ->send(new PartnershipNotificationMail($data, $partnershipId));
            } catch (\Exception $e) {
                Log::error("Erreur email admin partenariat: " . $e->getMessage());
            }

            // Email de confirmation au partenaire
            try {
                Mail::to($data['email'])
                    ->send(new PartnershipConfirmationMail($data));
            } catch (\Exception $e) {
                Log::error("Erreur email partenaire: " . $e->getMessage());
            }

            Log::info("Emails envoyés pour le partenariat #{$partnershipId}");

        } catch (\Exception $e) {
            Log::error("Erreur envoi emails partenariat #{$partnershipId}: " . $e->getMessage());
        }
    }

    public function confirmation()
    {
        return inertia('PartnershipConfirmation');
    }
}