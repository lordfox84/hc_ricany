@extends('layouts.hc')
@section('title', 'Hráči | Admin HC Říčany')

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
        <div class="admin-page-title">Hráči</div>
        <div class="admin-page-subtitle">Správa hráčů napříč všemi týmy.</div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">{{ session('success') }}</div>
        @endif

        <div class="admin-table-wrap">
          <div class="admin-table-header">
            <div class="admin-table-title">Hráči ({{ $players->count() }})</div>
            <a href="{{ route('admin.players.create') }}" class="btn btn-primary" style="font-size:0.78rem;padding:10px 20px;">+ Nový hráč</a>
          </div>

          {{-- Filtrování po týmech --}}
          <div style="display:flex;gap:8px;flex-wrap:wrap;padding:12px 20px;border-bottom:1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('admin.players.index') }}"
               class="btn btn-outline" style="font-size:0.75rem;padding:5px 12px;{{ !request('team') ? 'border-color:var(--teal);color:var(--teal);' : '' }}">
              Všechny
            </a>
            @foreach($teams as $team)
              <a href="{{ route('admin.players.index', ['team' => $team->id]) }}"
                 class="btn btn-outline" style="font-size:0.75rem;padding:5px 12px;{{ request('team') == $team->id ? 'border-color:'.$team->color.';color:'.$team->color.';' : '' }}">
                {{ $team->name_cs }}
              </a>
            @endforeach
          </div>

          {{-- Hromadné přeřazení do jiného týmu --}}
          <form id="bulk-team-form" method="POST" action="{{ route('admin.players.bulk-team') }}"
                style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding:12px 20px;border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
            @csrf
            <span style="font-size:0.8rem;color:var(--gray-light);">Vybráno: <strong id="selected-count" style="color:var(--teal);">0</strong></span>
            <select id="bulk-new-team" name="new_team_id" class="form-control" style="width:auto;font-size:0.8rem;padding:6px 10px;">
              <option value="">— přesunout do týmu —</option>
              @foreach($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name_cs }}{{ $team->age_group ? ' ('.$team->age_group.')' : '' }}</option>
              @endforeach
            </select>
            <button type="submit" id="bulk-submit-btn" class="btn btn-primary" style="font-size:0.78rem;padding:8px 16px;" disabled>
              Přesunout vybrané
            </button>
          </form>

          <table>
            <thead>
              <tr>
                <th style="width:32px;"><input type="checkbox" id="select-all-players"></th>
                <th>Hráč</th>
                <th>Tým</th>
                <th>#</th>
                <th>Post</th>
                <th>Ruka</th>
                <th>Výška</th>
                <th>Váha</th>
                <th>Narozeniny</th>
                <th>Stav</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($players->when(request('team'), fn($c) => $c->where('team_id', request('team'))) as $player)
                <tr>
                  <td><input type="checkbox" class="player-checkbox" data-id="{{ $player->id }}"></td>
                  <td class="admin-player-photo-cell">
                    @if($player->photo)
                      <img src="{{ Storage::url($player->photo) }}" class="admin-player-photo" style="width:32px;height:32px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-right:8px;">
                    @else
                      <span style="display:inline-block;width:32px;height:32px;border-radius:50%;background:var(--gray-mid);line-height:32px;text-align:center;font-size:0.8rem;margin-right:8px;vertical-align:middle;">?</span>
                    @endif
                    {{ $player->full_name }}
                  </td>
                  <td>
                    <span style="font-size:0.78rem;color:{{ $player->team->color }};">{{ $player->team->name_cs }}</span>
                  </td>
                  <td>{{ $player->jersey_number ?? '—' }}</td>
                  <td>{{ $player->position ?? '—' }}</td>
                  <td>{{ $player->hand ?? '—' }}</td>
                  <td>{{ $player->height_cm ? $player->height_cm.' cm' : '—' }}</td>
                  <td>{{ $player->weight_kg ? $player->weight_kg.' kg' : '—' }}</td>
                  <td>
                    @if($player->date_of_birth)
                      {{ $player->date_of_birth->format('d.m.') }}
                      @if($player->hasBirthdayToday())
                        🎂
                      @endif
                    @else
                      —
                    @endif
                  </td>
                  <td>
                    <span style="font-size:0.75rem;padding:2px 8px;border-radius:3px;background:{{ $player->is_active ? '#1a3a1a' : '#3a1a1a' }};color:{{ $player->is_active ? '#4ade80' : '#f87171' }};">
                      {{ $player->is_active ? 'aktivní' : 'neaktivní' }}
                    </span>
                  </td>
                  <td style="white-space:nowrap;">
                    <a href="{{ route('admin.players.edit', $player) }}" class="btn btn-outline" style="font-size:0.75rem;padding:6px 12px;">Upravit</a>
                    <form method="POST" action="{{ route('admin.players.destroy', $player) }}" style="display:inline;"
                          onsubmit="return confirm('Smazat hráče {{ addslashes($player->full_name) }}?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn" style="font-size:0.75rem;padding:6px 12px;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;">Smazat</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="11" style="color:var(--gray-light);">Žádní hráči.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
(function () {
  var selectAll = document.getElementById('select-all-players');
  var bulkForm  = document.getElementById('bulk-team-form');
  var teamSelect = document.getElementById('bulk-new-team');
  var submitBtn = document.getElementById('bulk-submit-btn');
  var countEl   = document.getElementById('selected-count');

  function checkboxes() {
    return Array.from(document.querySelectorAll('.player-checkbox'));
  }

  function updateState() {
    var checked = checkboxes().filter(function (cb) { return cb.checked; });
    countEl.textContent = checked.length;
    submitBtn.disabled = checked.length === 0 || !teamSelect.value;
  }

  selectAll.addEventListener('change', function () {
    checkboxes().forEach(function (cb) { cb.checked = selectAll.checked; });
    updateState();
  });

  checkboxes().forEach(function (cb) { cb.addEventListener('change', updateState); });
  teamSelect.addEventListener('change', updateState);

  bulkForm.addEventListener('submit', function (e) {
    var checked = checkboxes().filter(function (cb) { return cb.checked; });
    if (checked.length === 0 || !teamSelect.value) {
      e.preventDefault();
      return;
    }
    if (!confirm('Opravdu přesunout ' + checked.length + ' hráč(ů) do vybraného týmu?')) {
      e.preventDefault();
      return;
    }
    bulkForm.querySelectorAll('input[name="player_ids[]"]').forEach(function (el) { el.remove(); });
    checked.forEach(function (cb) {
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'player_ids[]';
      input.value = cb.dataset.id;
      bulkForm.appendChild(input);
    });
  });

  updateState();
})();
</script>
@endsection
