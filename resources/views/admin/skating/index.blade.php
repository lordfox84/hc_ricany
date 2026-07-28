@extends('layouts.hc')
@section('title', 'Veřejné bruslení | Admin HC Říčany')

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
        <div class="admin-page-title">Veřejné bruslení</div>
        <div class="admin-page-subtitle">Správa termínů veřejného bruslení. Na webu se zobrazují 3 nejbližší aktivní termíny.</div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">{{ session('success') }}</div>
        @endif

        <div style="display:flex;justify-content:flex-end;margin-bottom:20px;">
          <a href="{{ route('admin.skating.create') }}" class="admin-btn">+ Přidat termín</a>
        </div>

        <table class="admin-table">
          <thead>
            <tr>
              <th>Datum</th>
              <th>Od</th>
              <th>Do</th>
              <th>Poznámka</th>
              <th>Aktivní</th>
              <th>Akce</th>
            </tr>
          </thead>
          <tbody>
            @forelse($skatings as $s)
              <tr style="{{ $s->date->isPast() ? 'opacity:.45;' : '' }}">
                <td>
                  <strong>{{ $s->date->translatedFormat('l') }}</strong><br>
                  <span style="font-size:.85rem;color:var(--gray-light);">{{ $s->date->format('d.m.Y') }}</span>
                </td>
                <td>{{ \Str::substr($s->time_from, 0, 5) }}</td>
                <td>{{ \Str::substr($s->time_to, 0, 5) }}</td>
                <td style="font-size:.85rem;color:var(--gray-light);">{{ $s->note ?? '—' }}</td>
                <td>
                  @if($s->is_active)
                    <span style="color:var(--teal);font-size:.8rem;">● Ano</span>
                  @else
                    <span style="color:var(--gray-light);font-size:.8rem;">● Ne</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('admin.skating.edit', $s) }}" class="admin-btn admin-btn-sm">Upravit</a>
                  <form method="POST" action="{{ route('admin.skating.destroy', $s) }}" style="display:inline;" onsubmit="return confirm('Smazat tento termín?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="admin-btn admin-btn-sm admin-btn-danger">Smazat</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" style="color:var(--gray-light);text-align:center;">Žádné termíny.</td></tr>
            @endforelse
          </tbody>
        </table>

        <div style="margin-top:20px;">{{ $skatings->links() }}</div>
      </div>
    </main>
  </div>
</div>
@endsection
