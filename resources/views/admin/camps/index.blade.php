@extends('layouts.hc')
@section('title', 'Kempy | Admin HC Říčany')

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
        <div class="admin-page-title">Kempy</div>
        <div class="admin-page-subtitle">Správa hokejových kempů a táborů. Řazeno od nejnovějšího.</div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">{{ session('success') }}</div>
        @endif

        <div class="admin-table-wrap">
          <div class="admin-table-header">
            <div class="admin-table-title">Kempy ({{ $camps->count() }})</div>
            <a href="{{ route('admin.camps.create') }}" class="btn btn-primary" style="font-size:0.78rem;padding:10px 20px;">+ Nový kemp</a>
          </div>
          <table>
            <thead>
              <tr>
                <th>Plakát</th>
                <th>Název</th>
                <th>Termín</th>
                <th>Publikováno</th>
                <th>Stav</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($camps as $camp)
                <tr>
                  <td style="width:60px;">
                    @if($camp->poster)
                      <img src="{{ Storage::url($camp->poster) }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                    @else
                      <div style="width:50px;height:50px;background:var(--gray-mid);border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🏕️</div>
                    @endif
                  </td>
                  <td>
                    <strong>{{ $camp->title_cs }}</strong>
                    @if($camp->excerpt_cs)
                      <div style="font-size:0.78rem;color:var(--gray-light);margin-top:2px;">{{ Str::limit($camp->excerpt_cs, 60) }}</div>
                    @endif
                  </td>
                  <td style="white-space:nowrap;font-size:0.85rem;">
                    @if($camp->date_from)
                      {{ $camp->date_from->format('d.m.Y') }}
                      @if($camp->date_to)
                        – {{ $camp->date_to->format('d.m.Y') }}
                      @endif
                    @else
                      <span style="color:var(--gray-light);">—</span>
                    @endif
                  </td>
                  <td style="font-size:0.82rem;color:var(--gray-light);">
                    {{ $camp->published_at ? $camp->published_at->format('d.m.Y') : '—' }}
                  </td>
                  <td>
                    <span style="font-size:0.75rem;padding:2px 8px;border-radius:3px;background:{{ $camp->is_published ? '#1a3a1a' : '#3a1a1a' }};color:{{ $camp->is_published ? '#4ade80' : '#f87171' }};">
                      {{ $camp->is_published ? 'publikováno' : 'skrytý' }}
                    </span>
                  </td>
                  <td style="white-space:nowrap;">
                    <a href="{{ route('admin.camps.registrations', $camp) }}" class="btn btn-outline"
                       style="font-size:0.75rem;padding:6px 12px;border-color:#818cf8;color:#818cf8;">
                      👥 {{ $camp->registrations_count ?? $camp->registrations()->count() }}
                    </a>
                    <a href="{{ route('admin.camps.edit', $camp) }}" class="btn btn-outline" style="font-size:0.75rem;padding:6px 12px;">Upravit</a>
                    <form method="POST" action="{{ route('admin.camps.destroy', $camp) }}" style="display:inline;"
                          onsubmit="return confirm('Smazat kemp „{{ addslashes($camp->title_cs) }}"?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn" style="font-size:0.75rem;padding:6px 12px;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;">Smazat</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" style="color:var(--gray-light);">Žádné kempy.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>
@endsection
