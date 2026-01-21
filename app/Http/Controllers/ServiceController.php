<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Affiche la page principale des services
     */
    public function index()
    {
        return Inertia::render('Services/Index', [
            'pageTitle' => 'Nos Services - Sandy Platform',
            'metaDescription' => 'Sandy Platform orchestre l’approvisionnement local, le pressage à froid et la livraison express pour vos programmes juice & bien-être.'
        ]);
    }

    /**
     * Affiche la page du service Ménage
     */
    public function menage()
    {
        return Inertia::render('Services/Menage', [
            'pageTitle' => 'Atelier Pressage - Sandy Platform',
            'metaDescription' => 'Accompagnement complet sur vos recettes : sourcing ingrédients, pressage à froid, formulation et contrôle sensoriel.'
        ]);
    }

    /**
     * Affiche la page du service Garde d'Enfants
     */
    public function gardeEnfants()
    {
        return Inertia::render('Services/GardeEnfants', [
            'pageTitle' => 'Kids & Famille - Sandy Platform',
            'metaDescription' => 'Programmes Kids & Famille : jus doux, snacks frais et ateliers pédagogiques adaptés aux minis gourmands.'
        ]);
    }

    /**
     * Affiche la page du service Jardinage
     */
    public function jardinage()
    {
        return Inertia::render('Services/Jardinage', [
            'pageTitle' => 'Approvisionnement & Sourcing - Sandy Platform',
            'metaDescription' => 'Sélection des producteurs, contrôle des lots et logistique amont pour sécuriser vos matières premières premium.'
        ]);
    }

    /**
     * Affiche la page du service Auxiliaire de vie
     */
    public function seniors()
    {
        return Inertia::render('Services/Seniors', [
            'pageTitle' => 'Programmes Bien-être - Sandy Platform',
            'metaDescription' => 'Solutions nutritionnelles dédiées aux entreprises, spas et clubs santé : cures detox, packs énergie et ateliers éducatifs.'
        ]);
    }

    /**
     * Affiche la page du service Repassage
     */
    public function repassage()
    {
        return Inertia::render('Services/Repassage', [
            'pageTitle' => 'Conditionnement & Packaging - Sandy Platform',
            'metaDescription' => 'Personnalisation étiquettes, contrôle packaging et préparation des box retail & corporate.'
        ]);
    }

    public function chauffeur()
    {
        return Inertia::render('Services/Chauffeurs', [
            'pageTitle' => 'Livraison & Cold Chain - Sandy Platform',
            'metaDescription' => 'Planification des tournées, suivi chauffeurs et livraison 2h en caisse isotherme partout à Yaoundé/Douala.'
        ]);
    }

    /**
     * Traite la soumission du formulaire de demande de service
     */
    public function storeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_type' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'service_frequency' => 'required|in:once,weekly,biweekly,monthly',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'message' => 'nullable|string|max:1000',
            'terms' => 'accepted'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ici vous pouvez sauvegarder la demande en base de données
        // et envoyer des emails de confirmation

        return redirect()->route('services.confirmation')
            ->with('success', 'Votre demande a été envoyée à l’équipe Sandy Platform !');
    }

    /**
     * Affiche la page de confirmation après soumission du formulaire
     */
    public function confirmation()
    {
        return Inertia::render('Services/Confirmation', [
            'pageTitle' => 'Confirmation de demande - Sandy Platform',
            'metaDescription' => 'Confirmation de votre demande de service Sandy Platform'
        ]);
    }
}
