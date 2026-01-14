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
            'pageTitle' => 'Nos Services - USRA-CARE',
            'metaDescription' => 'Découvrez tous nos services à domicile : ménage, garde d\'enfants, Auxiliaire de vie et jardinage. Des intervenants qualifiés pour votre bien-être.'
        ]);
    }

    /**
     * Affiche la page du service Ménage
     */
    public function menage()
    {
        return Inertia::render('Services/Menage', [
            'pageTitle' => 'Aide Ménagère - USRA-CARE',
            'metaDescription' => 'Service d\'aide ménagère professionnelle pour l\'entretien de votre maison. Intervenants qualifiés et services personnalisés.'
        ]);
    }

    /**
     * Affiche la page du service Garde d'Enfants
     */
    public function gardeEnfants()
    {
        return Inertia::render('Services/GardeEnfants', [
            'pageTitle' => 'Garde d\'Enfants - USRA-CARE',
            'metaDescription' => 'Service de garde d\'enfants à domicile par des intervenants de confiance. Sécurité et bien-être de vos enfants garantis.'
        ]);
    }

    /**
     * Affiche la page du service Jardinage
     */
    public function jardinage()
    {
        return Inertia::render('Services/Jardinage', [
            'pageTitle' => 'Jardinage et Bricolage - USRA-CARE',
            'metaDescription' => 'Service de jardinage et bricolage à domicile. Entretien de vos espaces verts et petites réparations par des professionnels.'
        ]);
    }

    /**
     * Affiche la page du service Auxiliaire de vie
     */
    public function seniors()
    {
        return Inertia::render('Services/Seniors', [
            'pageTitle' => 'Auxiliaire de vie - USRA-CARE',
            'metaDescription' => 'Service d\'accompagnement et d\'assistance aux personnes âgées à domicile. Soutien quotidien et maintien à domicile.'
        ]);
    }

    /**
     * Affiche la page du service Repassage
     */
    public function repassage()
    {
        return Inertia::render('Services/Repassage', [
            'pageTitle' => 'Repassage - USRA-CARE',
            'metaDescription' => 'Service de repassage à domicile professionnel. Linges impeccables et gain de temps assuré.'
        ]);
    }

    public function chauffeur()
    {
        return Inertia::render('Services/Chauffeurs', [
            'pageTitle' => 'Chauffeur - USRA-CARE',
            'metaDescription' => 'Service de chauffeur à domicile pour vos déplacements. Confort, sécurité et ponctualité garantis.'
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
            ->with('success', 'Votre demande a été envoyée avec succès !');
    }

    /**
     * Affiche la page de confirmation après soumission du formulaire
     */
    public function confirmation()
    {
        return Inertia::render('Services/Confirmation', [
            'pageTitle' => 'Confirmation de demande - USRA-CARE',
            'metaDescription' => 'Confirmation de votre demande de service USRA-CARE'
        ]);
    }
}
