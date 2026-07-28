<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use App\Models\Registration;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request, Camp $camp)
    {
        abort_unless($camp->is_published && $camp->registration_open, 404);

        // Kapacita
        if ($camp->capacity && $camp->spotsLeft() === 0) {
            return back()
                ->with('reg_error', 'Kemp je bohužel obsazen. Zkuste nás kontaktovat pro zařazení na čekací listinu.')
                ->with('reg_camp_id', $camp->id);
        }

        $data = $request->validate([
            'parent_name'         => 'required|string|max:100',
            'parent_last_name'    => 'required|string|max:100',
            'parent_email'        => 'required|email:rfc,filter|max:150',
            'parent_phone'        => 'nullable|string|max:20',
            'child_name'          => 'required|string|max:100',
            'child_last_name'     => 'required|string|max:100',
            'child_date_of_birth' => 'required|date',
            'note'                => 'nullable|string|max:500',
            'street'              => 'nullable|string|max:200',
            'city'                => 'nullable|string|max:100',
            'zip'                 => 'nullable|string|max:20',
            'country'             => 'nullable|string|max:100',
            'club'                => 'nullable|string|max:200',
            'position'            => 'nullable|in:útočník,obránce,brankář',
            'jersey_size'         => 'nullable|in:XS,S,M,L,XL,XXL,jiná',
            'jersey_size_custom'  => 'nullable|string|max:50|required_if:jersey_size,jiná',
            'variant'             => ($camp->variants ? 'required' : 'nullable') . '|string|max:300',
            'gdpr_consent'        => 'accepted',
            'marketing_consent'   => 'nullable|boolean',
            'locale'              => 'nullable|in:cs,en',
        ], [
            'gdpr_consent.accepted'        => 'Pro dokončení registrace musíte souhlasit se zpracováním osobních údajů.',
            'parent_email.email'           => 'Zadejte platnou e-mailovou adresu.',
            'parent_name.required'         => 'Vyplňte jméno rodiče / zákonného zástupce.',
            'parent_last_name.required'    => 'Vyplňte příjmení rodiče / zákonného zástupce.',
            'child_name.required'          => 'Vyplňte jméno hráče.',
            'child_last_name.required'     => 'Vyplňte příjmení hráče.',
            'child_date_of_birth.required' => 'Vyplňte datum narození hráče.',
            'child_date_of_birth.date'     => 'Zadejte platné datum narození.',
            'jersey_size_custom.required_if' => 'Vyplňte vlastní velikost dresu.',
            'variant.required'             => 'Vyberte prosím variantu kempu.',
        ]);

        $registration = Registration::create([
            'camp_id'             => $camp->id,
            'parent_name'         => $data['parent_name'],
            'parent_last_name'    => $data['parent_last_name'],
            'parent_email'        => $data['parent_email'],
            'parent_phone'        => $data['parent_phone'] ?? null,
            'child_name'          => $data['child_name'],
            'child_last_name'     => $data['child_last_name'],
            'child_date_of_birth' => $data['child_date_of_birth'],
            'note'                => $data['note'] ?? null,
            'street'              => $data['street'] ?? null,
            'city'                => $data['city'] ?? null,
            'zip'                 => $data['zip'] ?? null,
            'country'             => $data['country'] ?? null,
            'club'                => $data['club'] ?? null,
            'position'            => $data['position'] ?? null,
            'jersey_size'         => $data['jersey_size'] ?? null,
            'jersey_size_custom'  => $data['jersey_size_custom'] ?? null,
            'variant'             => $data['variant'] ?? null,
            'gdpr_consent'        => true,
            'marketing_consent'   => $request->boolean('marketing_consent'),
            'consented_at'        => now(),
            'ip_address'          => $request->ip(),
            'locale'              => in_array($data['locale'] ?? '', ['cs', 'en']) ? $data['locale'] : 'cs',
        ]);

        // Sync do odběrů — pokud dal souhlas s marketingem
        if ($registration->marketing_consent) {
            Subscriber::syncFromRegistration($registration);
        }

        return back()
            ->with('reg_success', 'Registrace proběhla úspěšně! Brzy vás budeme kontaktovat s dalšími informacemi.')
            ->with('reg_camp_id', $camp->id);
    }
}
