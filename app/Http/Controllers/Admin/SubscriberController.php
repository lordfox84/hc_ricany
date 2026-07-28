<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all'); // all | active | unsubscribed | camp | newsletter

        $query = Subscriber::latest();

        match ($filter) {
            'active'       => $query->where('marketing_consent', true)->whereNull('unsubscribed_at'),
            'unsubscribed' => $query->where(fn($q) => $q->where('marketing_consent', false)->orWhereNotNull('unsubscribed_at')),
            'camp'         => $query->where('source', 'camp'),
            'newsletter'   => $query->where('source', 'newsletter'),
            default        => null,
        };

        $subscribers = $query->get();
        return view('admin.subscribers.index', compact('subscribers', 'filter'));
    }

    /** Přepnout marketing souhlas (odhlásit / znovu přihlásit) */
    public function toggleMarketing(Subscriber $subscriber)
    {
        if ($subscriber->marketing_consent && $subscriber->unsubscribed_at === null) {
            // Odhlásit z marketingu
            $subscriber->marketing_consent = false;
            $subscriber->unsubscribed_at   = now();
        } else {
            // Znovu přihlásit
            $subscriber->marketing_consent = true;
            $subscriber->unsubscribed_at   = null;
        }
        $subscriber->save();

        return back()->with('success', 'Stav odběru byl upraven.');
    }

    /** Smazat kompletně z DB (GDPR právo na výmaz) */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->route('admin.subscribers.index')->with('success', 'Kontakt byl vymazán z databáze.');
    }

    /** Export CSV — všichni aktivní odběratelé (marketing_consent=true) */
    public function exportCsv()
    {
        $subscribers = Subscriber::where('marketing_consent', true)
            ->whereNull('unsubscribed_at')
            ->orderBy('email')
            ->get();

        $csv  = "\xEF\xBB\xBF";
        $csv .= "Jméno;Příjmení;Email;Jazyk;Zdroj;Souhlas udělen\n";

        foreach ($subscribers as $s) {
            $csv .= implode(';', [
                $s->first_name ?? '',
                $s->last_name  ?? '',
                $s->email,
                strtoupper($s->locale ?? 'cs'),
                $s->source === 'camp' ? 'Registrace kempu' : 'Newsletter',
                $s->consented_at?->format('d.m.Y') ?? '',
            ]) . "\n";
        }

        $filename = 'odbery-' . now()->format('Ymd') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
