@extends('layouts.hc')
@section('title', 'Odběry | Admin HC Říčany')

@section('content')
<div class="admin-panel" style="display:block;">
  <div class="admin-nav">
    <div class="admin-nav-left">
      <div class="admin-logo">HC ŘÍČANY</div>
      <div class="admin-badge">Administrace</div>
    </div>
    <div style="display:flex;align-items:center;gap:16px;">
      <span style="font-size:0.8rem;color:var(--gray-light);"><span style="color:var(--teal);">●</span> {{ auth()->user()->name }}</span>
      <a href="{{ route('home') }}" class="admin-close-btn">Zpět na web</a>
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="admin-close-btn" style="background:none;border:1px solid #555;">Odhlásit</button>
      </form>
    </div>
  </div>

  <div class="admin-layout">
    @include('partials._admin_sidebar')

    <main class="admin-content">
      <div class="admin-panel-section active">
        <div class="admin-page-title">Odběry</div>
        <div class="admin-page-subtitle">
          Přihlášení k odběru novinek a marketingové komunikaci.
          Slouží jako základ pro cílení e-mailů v CZ i EN mutaci.
        </div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">{{ session('success') }}</div>
        @endif

        {{-- STATISTIKY --}}
        @php
          $totalCount       = $subscribers->count();
          $activeCount      = $subscribers->filter(fn($s) => $s->is_active)->count();
          $unsubCount       = $totalCount - $activeCount;
          $campCount        = $subscribers->where('source', 'camp')->count();
          $newsletterCount  = $subscribers->where('source', 'newsletter')->count();
          $csCount          = $subscribers->filter(fn($s) => $s->is_active && ($s->locale ?? 'cs') === 'cs')->count();
          $enCount          = $subscribers->filter(fn($s) => $s->is_active && ($s->locale ?? 'cs') === 'en')->count();
        @endphp
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;margin-bottom:24px;">
          @foreach([
            ['Celkem', $totalCount, '#2abfbf'],
            ['Aktivních', $activeCount, '#4ade80'],
            ['Odhlášených', $unsubCount, '#f87171'],
            ['Z newsletteru', $newsletterCount, '#818cf8'],
            ['Z kempů', $campCount, '#facc15'],
            ['CZ aktivní', $csCount, '#7dd3fc'],
            ['EN aktivní', $enCount, '#c084fc'],
          ] as [$label, $count, $color])
            <div style="background:var(--gray);border:1px solid rgba(255,255,255,0.07);border-left:3px solid {{ $color }};border-radius:4px;padding:14px 16px;">
              <div style="font-size:1.6rem;font-weight:700;color:{{ $color }};font-family:var(--font-display);">{{ $count }}</div>
              <div style="font-size:0.75rem;color:var(--gray-light);margin-top:2px;">{{ $label }}</div>
            </div>
          @endforeach
        </div>

        {{-- AKCE --}}
        <div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;align-items:center;">
          <a href="{{ route('admin.subscribers.export') }}" class="btn btn-outline" style="font-size:0.8rem;">
            ⬇ Export aktivních (CSV)
          </a>
          <span style="font-size:0.8rem;color:var(--gray-light);">Filtrovat:</span>
          @foreach([
            ['all', 'Všechny'],
            ['active', 'Aktivní'],
            ['unsubscribed', 'Odhlášeni'],
            ['newsletter', 'Newsletter'],
            ['camp', 'Kempy'],
          ] as [$val, $lbl])
            <a href="{{ route('admin.subscribers.index', ['filter' => $val]) }}"
               class="btn btn-outline"
               style="font-size:0.75rem;padding:6px 14px;{{ $filter === $val ? 'border-color:var(--teal);color:var(--teal);' : '' }}">
              {{ $lbl }}
            </a>
          @endforeach
        </div>

        <div class="admin-table-wrap">
          <div class="admin-table-header">
            <div class="admin-table-title">Kontakty ({{ $subscribers->count() }})</div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Jméno</th>
                <th>Email</th>
                <th style="text-align:center;">Jazyk</th>
                <th style="text-align:center;">Zdroj</th>
                <th style="text-align:center;">Stav</th>
                <th>Souhlas</th>
                <th>Odhlášen</th>
                <th>Přihlášen</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($subscribers as $s)
                <tr>
                  <td style="white-space:nowrap;">
                    <strong>{{ $s->full_name }}</strong>
                  </td>
                  <td>
                    <a href="mailto:{{ $s->email }}" style="color:var(--teal);">{{ $s->email }}</a>
                  </td>
                  <td style="text-align:center;">
                    <span style="font-size:0.72rem;font-weight:700;padding:2px 7px;border-radius:3px;
                      background:{{ ($s->locale ?? 'cs') === 'en' ? '#1a1a3a' : '#1a2a3a' }};
                      color:{{ ($s->locale ?? 'cs') === 'en' ? '#818cf8' : '#7dd3fc' }};">
                      {{ strtoupper($s->locale ?? 'cs') }}
                    </span>
                  </td>
                  <td style="text-align:center;">
                    <span style="font-size:0.72rem;padding:2px 8px;border-radius:3px;
                      background:{{ $s->source === 'camp' ? '#2a1a0a' : '#1a1a2a' }};
                      color:{{ $s->source === 'camp' ? '#facc15' : '#818cf8' }};">
                      {{ $s->source === 'camp' ? '🏕 kemp' : '📬 newsletter' }}
                    </span>
                  </td>
                  <td style="text-align:center;">
                    @if($s->is_active)
                      <span style="color:#4ade80;font-size:0.78rem;font-weight:600;">✓ aktivní</span>
                    @else
                      <span style="color:#f87171;font-size:0.78rem;">✗ odhlášen</span>
                    @endif
                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);white-space:nowrap;">
                    {{ $s->consented_at?->format('d.m.Y') ?? '—' }}
                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);white-space:nowrap;">
                    {{ $s->unsubscribed_at?->format('d.m.Y') ?? '—' }}
                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);white-space:nowrap;">
                    {{ $s->created_at->format('d.m.Y') }}
                  </td>
                  <td style="white-space:nowrap;">
                    {{-- Toggle marketing --}}
                    <form method="POST" action="{{ route('admin.subscribers.toggle', $s) }}" style="display:inline;">
                      @csrf @method('PATCH')
                      <button type="submit" class="btn btn-outline"
                              style="font-size:0.72rem;padding:4px 10px;{{ $s->is_active ? 'border-color:#f87171;color:#f87171;' : 'border-color:#4ade80;color:#4ade80;' }}"
                              title="{{ $s->is_active ? 'Odhlásit z marketingu' : 'Znovu přihlásit' }}">
                        {{ $s->is_active ? 'Odhlásit' : 'Přihlásit' }}
                      </button>
                    </form>
                    {{-- Smazat (GDPR výmaz) --}}
                    <form method="POST" action="{{ route('admin.subscribers.destroy', $s) }}" style="display:inline;"
                          onsubmit="return confirm('Vymazat kontakt {{ addslashes($s->email) }} z databáze? Tato akce je nevratná (GDPR výmaz).')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn"
                              style="font-size:0.72rem;padding:4px 10px;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;">
                        Vymazat
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" style="color:var(--gray-light);text-align:center;padding:40px;">
                    Žádné odběry.
                    @if($filter !== 'all')
                      <a href="{{ route('admin.subscribers.index') }}" style="color:var(--teal);">Zobrazit vše</a>
                    @endif
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- GDPR poznámka --}}
        <div style="margin-top:20px;padding:14px 18px;background:rgba(42,191,191,0.05);border:1px solid rgba(42,191,191,0.15);border-radius:6px;font-size:0.78rem;color:var(--gray-light);">
          <strong style="color:var(--teal);">ℹ GDPR:</strong>
          „Odhlásit" deaktivuje marketing bez smazání záznamu (audit trail).
          „Vymazat" trvale odstraní kontakt z databáze — použijte při žádosti o výmaz osobních údajů dle čl. 17 GDPR.
        </div>
      </div>
    </main>
  </div>
</div>
@endsection
