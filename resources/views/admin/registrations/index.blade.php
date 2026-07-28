@extends('layouts.hc')
@section('title', 'Registrace – ' . $camp->title_cs . ' | Admin')

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
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:4px;">
          <a href="{{ route('admin.camps.index') }}" style="color:var(--gray-light);font-size:0.85rem;">← Zpět na kempy</a>
        </div>
        <div class="admin-page-title">Registrace: {{ $camp->title_cs }}</div>
        <div class="admin-page-subtitle">
          {{ $registrations->count() }} přihlášení
          @if($camp->capacity) · kapacita {{ $camp->capacity }} míst @endif
          · {{ $registrations->where('marketing_consent', true)->count() }} souhlasí s marketingem
        </div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">{{ session('success') }}</div>
        @endif

        {{-- EXPORT TLAČÍTKA --}}
        <div style="display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap;">
          <a href="{{ route('admin.camps.registrations.export', $camp) }}" class="btn btn-outline" style="font-size:0.8rem;">
            ⬇ Export CSV (tento kemp)
          </a>
          <a href="{{ route('admin.registrations.marketing-export') }}" class="btn btn-outline" style="font-size:0.8rem;border-color:#818cf8;color:#818cf8;">
            ⬇ Export marketingových emailů (všechny kempy)
          </a>
        </div>

        <div class="admin-table-wrap" style="overflow-x:auto;">
          <table style="min-width:1300px;">
            <thead>
              <tr>
                <th>Rodič</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Hráč</th>
                <th>Datum nar.</th>
                <th>Klub</th>
                <th>Post</th>
                <th>Dres</th>
                <th>Město / PSČ</th>
                <th>Varianta</th>
                <th>Poznámka</th>
                <th style="text-align:center;">Jazyk</th>
                <th style="text-align:center;">Mkting</th>
                <th>Datum reg.</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($registrations as $r)
                <tr>
                  <td style="white-space:nowrap;"><strong>{{ $r->parent_full_name }}</strong></td>
                  <td><a href="mailto:{{ $r->parent_email }}" style="color:var(--teal);">{{ $r->parent_email }}</a></td>
                  <td style="white-space:nowrap;">{{ $r->parent_phone ?? '—' }}</td>
                  <td style="white-space:nowrap;"><strong>{{ $r->child_full_name }}</strong></td>
                  <td style="white-space:nowrap;font-size:0.82rem;">
                    {{ $r->child_date_of_birth ? $r->child_date_of_birth->format('d.m.Y') : '—' }}
                  </td>
                  <td style="font-size:0.82rem;">{{ $r->club ?? '—' }}</td>
                  <td style="font-size:0.82rem;white-space:nowrap;">{{ $r->position ?? '—' }}</td>
                  <td style="font-size:0.82rem;white-space:nowrap;">{{ $r->jersey_size_display ?: '—' }}</td>
                  <td style="font-size:0.82rem;">
                    @if($r->city || $r->zip)
                      {{ $r->city }}@if($r->city && $r->zip), @endif{{ $r->zip }}
                    @else
                      <span style="color:var(--gray-light);">—</span>
                    @endif
                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);max-width:200px;word-break:break-word;">{{ $r->variant ?? '—' }}</td>
                  <td style="font-size:0.82rem;color:var(--gray-light);max-width:150px;word-break:break-word;">{{ $r->note ?? '—' }}</td>
                  <td style="text-align:center;">
                    <span style="font-size:0.72rem;font-weight:700;padding:2px 7px;border-radius:3px;
                      background:{{ ($r->locale ?? 'cs') === 'en' ? '#1a1a3a' : '#1a2a3a' }};
                      color:{{ ($r->locale ?? 'cs') === 'en' ? '#818cf8' : '#7dd3fc' }};">
                      {{ strtoupper($r->locale ?? 'cs') }}
                    </span>
                  </td>
                  <td style="text-align:center;">
                    @if($r->marketing_consent)
                      <span style="color:#4ade80;" title="Souhlasí s marketingem">✓</span>
                    @else
                      <span style="color:var(--gray-light);">—</span>
                    @endif
                  </td>
                  <td style="font-size:0.78rem;color:var(--gray-light);white-space:nowrap;">{{ $r->created_at->format('d.m.Y H:i') }}</td>
                  <td>
                    <form method="POST" action="{{ route('admin.registrations.destroy', $r) }}"
                          onsubmit="return confirm('Smazat registraci {{ addslashes($r->parent_full_name) }}?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn" style="font-size:0.72rem;padding:4px 10px;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;">Smazat</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="15" style="color:var(--gray-light);">Žádné registrace.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>
@endsection
