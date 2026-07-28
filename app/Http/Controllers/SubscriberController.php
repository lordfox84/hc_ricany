<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'        => 'nullable|string|max:100',
            'last_name'         => 'nullable|string|max:100',
            'email'             => 'required|email:rfc,filter|max:255',
            'gdpr_consent'      => 'accepted',
            'marketing_consent' => 'accepted',
            'locale'            => 'nullable|in:cs,en',
        ], [
            'gdpr_consent.accepted'      => 'Pro přihlášení k odběru musíte souhlasit se zpracováním osobních údajů.',
            'marketing_consent.accepted' => 'Pro přihlášení k odběru je nutný souhlas s marketingovou komunikací.',
            'email.required'             => 'Zadejte e-mailovou adresu.',
            'email.email'                => 'Zadejte platnou e-mailovou adresu.',
        ]);

        $locale = in_array($data['locale'] ?? '', ['cs', 'en']) ? $data['locale'] : 'cs';

        $existing = Subscriber::where('email', $data['email'])->first();

        if ($existing) {
            // Existující kontakt — obnovit souhlas a aktualizovat locale
            $existing->marketing_consent = true;
            $existing->unsubscribed_at   = null;
            $existing->gdpr_consent      = true;
            $existing->locale            = $locale;
            $existing->first_name        = $data['first_name'] ?: $existing->first_name;
            $existing->last_name         = $data['last_name']  ?: $existing->last_name;
            $existing->consented_at      = now();
            $existing->save();
        } else {
            Subscriber::create([
                'email'             => $data['email'],
                'first_name'        => $data['first_name'] ?? null,
                'last_name'         => $data['last_name']  ?? null,
                'locale'            => $locale,
                'source'            => 'newsletter',
                'gdpr_consent'      => true,
                'marketing_consent' => true,
                'consented_at'      => now(),
                'ip_address'        => $request->ip(),
            ]);
        }

        return back()->with('newsletter_success', true);
    }
}
