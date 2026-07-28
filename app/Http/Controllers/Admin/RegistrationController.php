<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\Registration;

class RegistrationController extends Controller
{
    public function index(Camp $camp)
    {
        $registrations = $camp->registrations()->latest()->get();
        return view('admin.registrations.index', compact('camp', 'registrations'));
    }

    public function exportCsv(Camp $camp)
    {
        $registrations = $camp->registrations()->latest()->get();

        $csv  = "\xEF\xBB\xBF"; // BOM pro Excel (UTF-8)
        $csv .= implode(';', [
            'Jméno rodiče', 'Příjmení rodiče', 'Email', 'Telefon',
            'Jméno hráče', 'Příjmení hráče', 'Datum narození',
            'Klub', 'Post', 'Velikost dresu',
            'Ulice', 'Město', 'PSČ', 'Země',
            'Varianta', 'Poznámka',
            'Jazyk', 'Souhlas s marketingem', 'Datum registrace',
        ]) . "\n";

        foreach ($registrations as $r) {
            $csv .= implode(';', [
                $r->parent_name,
                $r->parent_last_name ?? '',
                $r->parent_email,
                $r->parent_phone ?? '',
                $r->child_name,
                $r->child_last_name ?? '',
                $r->child_date_of_birth ? $r->child_date_of_birth->format('d.m.Y') : '',
                $r->club ?? '',
                $r->position ?? '',
                $r->jersey_size_display,
                $r->street ?? '',
                $r->city ?? '',
                $r->zip ?? '',
                $r->country ?? '',
                $r->variant ?? '',
                $r->note ?? '',
                strtoupper($r->locale ?? 'cs'),
                $r->marketing_consent ? 'Ano' : 'Ne',
                $r->created_at->format('d.m.Y H:i'),
            ]) . "\n";
        }

        $filename = 'registrace-' . $camp->id . '-' . now()->format('Ymd') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportMarketingCsv()
    {
        $emails = Registration::where('marketing_consent', true)
            ->select('parent_name', 'parent_last_name', 'parent_email', 'locale', 'consented_at')
            ->orderBy('parent_email')
            ->get()
            ->unique('parent_email');

        $csv  = "\xEF\xBB\xBF";
        $csv .= "Jméno;Příjmení;Email;Jazyk;Souhlas udělen\n";

        foreach ($emails as $r) {
            $csv .= implode(';', [
                $r->parent_name,
                $r->parent_last_name ?? '',
                $r->parent_email,
                strtoupper($r->locale ?? 'cs'),
                $r->consented_at?->format('d.m.Y') ?? '',
            ]) . "\n";
        }

        $filename = 'marketing-emaily-' . now()->format('Ymd') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function destroy(Registration $registration)
    {
        $camp = $registration->camp;
        $registration->delete();
        return redirect()->route('admin.camps.registrations', $camp)->with('success', 'Registrace byla smazána.');
    }
}
