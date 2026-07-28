@extends('layouts.hc')
@section('title', 'Týmy | Admin HC Říčany')

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
        <div class="admin-page-title">Týmy</div>
        <div class="admin-page-subtitle">Správa týmů klubu. Pořadí ovlivňuje zobrazení na webu — přetáhněte řádek pomocí ⠿ pro změnu pořadí.</div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:14px 20px;margin-bottom:20px;color:#f88;">{{ session('error') }}</div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

          {{-- SEZNAM TÝMŮ --}}
          <div class="admin-table-wrap">
            <div class="admin-table-header">
              <div class="admin-table-title">Týmy ({{ $teams->count() }})</div>
            </div>
            <table>
              <thead>
                <tr>
                  <th style="width:28px;"></th>
                  <th>Název</th>
                  <th>Věk. kat.</th>
                  <th>Pořadí</th>
                  <th>Stav</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="teams-sortable">
                @forelse($teams as $team)
                  <tr draggable="true" data-id="{{ $team->id }}" class="team-row">
                    <td class="team-drag-handle" title="Přetáhněte pro změnu pořadí">⠿</td>
                    <td>
                      <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $team->color }};margin-right:8px;"></span>
                      <strong>{{ $team->name_cs }}</strong>
                      @if($team->name_en)
                        <span style="color:var(--gray-light);font-size:0.8rem;"> / {{ $team->name_en }}</span>
                      @endif
                    </td>
                    <td>{{ $team->age_group ?? '—' }}</td>
                    <td class="team-sort-order">{{ $team->sort_order }}</td>
                    <td>
                      <span style="font-size:0.75rem;padding:2px 8px;border-radius:3px;background:{{ $team->is_active ? '#1a3a1a' : '#3a1a1a' }};color:{{ $team->is_active ? '#4ade80' : '#f87171' }};">
                        {{ $team->is_active ? 'aktivní' : 'skrytý' }}
                      </span>
                    </td>
                    <td style="white-space:nowrap;">
                      <button onclick="editTeam({{ $team->id }}, '{{ addslashes($team->name_cs) }}', '{{ addslashes($team->name_en) }}', '{{ addslashes($team->age_group) }}', '{{ $team->color }}', {{ $team->is_active ? 1 : 0 }}, `{{ addslashes($team->description_cs) }}`, `{{ addslashes($team->description_en) }}`)"
                              class="btn btn-outline" style="font-size:0.75rem;padding:6px 12px;">Upravit</button>
                      <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" style="display:inline;"
                            onsubmit="return confirm('Opravdu smazat tým {{ addslashes($team->name_cs) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn" style="font-size:0.75rem;padding:6px 12px;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;">Smazat</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" style="color:var(--gray-light);">Žádné týmy.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- FORMULÁŘ --}}
          <div class="admin-form-card">
            <div class="admin-form-section-title" id="form-title">Nový tým</div>

            <form method="POST" id="team-form" action="{{ route('admin.teams.store') }}">
              @csrf
              <div id="method-field"></div>

              <div class="form-group">
                <label class="form-label">Název (česky) *</label>
                <input type="text" name="name_cs" id="f-name_cs" class="form-control" required placeholder="Dorost, Starší žáci...">
              </div>
              <div class="form-group">
                <label class="form-label">Název (anglicky)</label>
                <input type="text" name="name_en" id="f-name_en" class="form-control" placeholder="Juniors, Cadets...">
              </div>
              <div class="form-group">
                <label class="form-label">Věková kategorie</label>
                <input type="text" name="age_group" id="f-age_group" class="form-control" placeholder="U18, U14, U8–U10...">
              </div>
              <div class="form-group">
                <label class="form-label">Popis (česky)</label>
                <textarea name="description_cs" id="f-description_cs" class="form-control" style="min-height:70px;"></textarea>
              </div>
              <div class="form-group">
                <label class="form-label">Popis (anglicky)</label>
                <textarea name="description_en" id="f-description_en" class="form-control" style="min-height:70px;"></textarea>
              </div>
              <div class="form-group">
                <label class="form-label">Barva</label>
                <input type="color" name="color" id="f-color" value="#2abfbf" class="form-control" style="height:40px;padding:4px;">
              </div>
              <small style="color:var(--gray-light);display:block;margin:-6px 0 12px;">Pořadí týmu se nastavuje přetažením řádku v seznamu vlevo.</small>
              <div class="form-group" id="active-row" style="display:none;">
                <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                  <input type="checkbox" name="is_active" id="f-is_active" value="1" checked style="accent-color:var(--teal);">
                  <span>Aktivní (zobrazit na webu)</span>
                </label>
              </div>

              <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Uložit</button>
                <button type="button" onclick="resetForm()" class="btn btn-outline">Zrušit</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </main>
  </div>
</div>


<style>
  .team-drag-handle { cursor: grab; color: var(--gray-light); text-align: center; font-size: 1rem; user-select: none; }
  .team-row.dragging { opacity: 0.4; }
  .team-row.drag-over { border-top: 2px solid var(--teal); }
</style>

<script>
function editTeam(id, name_cs, name_en, age_group, color, is_active, desc_cs, desc_en) {
  document.getElementById('form-title').textContent = 'Upravit tým';
  document.getElementById('team-form').action = '/admin/teams/' + id;
  document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
  document.getElementById('f-name_cs').value      = name_cs;
  document.getElementById('f-name_en').value      = name_en;
  document.getElementById('f-age_group').value    = age_group;
  document.getElementById('f-color').value        = color;
  document.getElementById('f-is_active').checked  = !!is_active;
  document.getElementById('f-description_cs').value = desc_cs;
  document.getElementById('f-description_en').value = desc_en;
  document.getElementById('active-row').style.display = 'block';
  window.scrollTo({top: document.getElementById('team-form').getBoundingClientRect().top + window.scrollY - 80, behavior:'smooth'});
}
function resetForm() {
  document.getElementById('form-title').textContent = 'Nový tým';
  document.getElementById('team-form').action = '{{ route('admin.teams.store') }}';
  document.getElementById('method-field').innerHTML = '';
  document.getElementById('team-form').reset();
  document.getElementById('f-color').value = '#2abfbf';
  document.getElementById('active-row').style.display = 'none';
}

// ─── DRAG & DROP REORDER ────────────────────────────────────
(function () {
  var tbody = document.getElementById('teams-sortable');
  if (!tbody) return;
  var draggedRow = null;

  tbody.querySelectorAll('.team-row').forEach(function (row) {
    row.addEventListener('dragstart', function () {
      draggedRow = row;
      row.classList.add('dragging');
    });
    row.addEventListener('dragend', function () {
      row.classList.remove('dragging');
      tbody.querySelectorAll('.team-row').forEach(function (r) { r.classList.remove('drag-over'); });
    });
    row.addEventListener('dragover', function (e) {
      e.preventDefault();
      if (row === draggedRow) return;
      var rect = row.getBoundingClientRect();
      var before = (e.clientY - rect.top) < rect.height / 2;
      row.classList.add('drag-over');
      tbody.insertBefore(draggedRow, before ? row : row.nextSibling);
    });
    row.addEventListener('dragleave', function () {
      row.classList.remove('drag-over');
    });
    row.addEventListener('drop', function (e) {
      e.preventDefault();
      persistOrder();
    });
  });

  function persistOrder() {
    var ids = Array.from(tbody.querySelectorAll('.team-row')).map(function (r) { return r.dataset.id; });

    fetch('{{ route('admin.teams.reorder') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({ team_ids: ids }),
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.ok) {
          tbody.querySelectorAll('.team-row').forEach(function (row, index) {
            var cell = row.querySelector('.team-sort-order');
            if (cell) cell.textContent = index;
          });
        } else {
          alert(data.error || 'Přeuspořádání se nezdařilo.');
          window.location.reload();
        }
      })
      .catch(function () {
        alert('Přeuspořádání se nezdařilo — zkuste to znovu.');
        window.location.reload();
      });
  }
})();
</script>
@endsection
