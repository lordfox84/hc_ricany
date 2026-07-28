@extends('layouts.hc')
@section('title', 'Tagy | Admin HC Říčany')

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
        <div class="admin-page-title">Tagy</div>
        <div class="admin-page-subtitle">Správa tagů článků. Každý tag má vlastní barvu štítku.</div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:14px 20px;margin-bottom:20px;color:#f88;">{{ session('error') }}</div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

          {{-- ── EXISTUJÍCÍ TAGY ── --}}
          <div class="admin-table-wrap">
            <div class="admin-table-header">
              <div class="admin-table-title">Tagy ({{ $categories->count() }})</div>
            </div>
            <table>
              <thead>
                <tr>
                  <th>Barva</th>
                  <th>Název CZ</th>
                  <th>Název EN</th>
                  <th>Článků</th>
                  <th>Akce</th>
                </tr>
              </thead>
              <tbody>
                @forelse($categories as $cat)
                <tr>
                  <td>
                    <span style="display:inline-block;width:24px;height:24px;border-radius:50%;background:{{ $cat->color }};vertical-align:middle;border:2px solid rgba(255,255,255,0.1);"></span>
                  </td>
                  <td>
                    <span class="cat-tag" style="background:{{ $cat->color }}22;color:{{ $cat->color }};border:1px solid {{ $cat->color }}55;font-size:0.75rem;padding:3px 10px;border-radius:20px;font-family:var(--font-cond);letter-spacing:0.05em;">
                      #{{ $cat->name_cs }}
                    </span>
                  </td>
                  <td style="color:var(--gray-light);font-size:0.85rem;">{{ $cat->name_en ?? '—' }}</td>
                  <td style="color:var(--gray-light);font-size:0.85rem;">{{ $cat->articles_count }}</td>
                  <td>
                    <button class="action-btn"
                      onclick="editCat({{ $cat->id }}, '{{ addslashes($cat->name_cs) }}', '{{ addslashes($cat->name_en ?? '') }}', '{{ $cat->color }}')">
                      Upravit
                    </button>
                    @if($cat->articles_count === 0)
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="display:inline;" onsubmit="return confirm('Smazat tag?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="action-btn danger">Smazat</button>
                    </form>
                    @endif
                  </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:var(--gray-light);padding:32px;">Žádné tagy.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- ── FORMULÁŘ (vytvořit / upravit) ── --}}
          <div class="admin-form-card" id="cat-form-card">
            <div class="admin-form-section-title" id="cat-form-title">Nový tag</div>

            <form id="cat-form" method="POST" action="{{ route('admin.categories.store') }}">
              @csrf
              <input type="hidden" name="_method" id="cat-method" value="POST">
              <input type="hidden" name="_cat_id" id="cat-id" value="">

              <div class="form-group">
                <label class="form-label">Název (česky) *</label>
                <input type="text" name="name_cs" id="cat-name-cs" class="form-control" required placeholder="Zápas">
              </div>
              <div class="form-group">
                <label class="form-label">Název (anglicky)</label>
                <input type="text" name="name_en" id="cat-name-en" class="form-control" placeholder="Match">
              </div>
              <div class="form-group">
                <label class="form-label">Barva štítku</label>
                <div style="display:flex;align-items:center;gap:12px;">
                  <input type="color" name="color" id="cat-color" value="#2abfbf"
                    style="width:48px;height:38px;padding:2px;border:1px solid rgba(255,255,255,0.15);background:var(--gray-mid);border-radius:4px;cursor:pointer;">
                  <span id="cat-color-preview" style="font-family:var(--font-cond);font-size:0.8rem;padding:4px 14px;border-radius:20px;border:1px solid #2abfbf55;background:#2abfbf22;color:#2abfbf;">
                    #Novinky
                  </span>
                </div>
              </div>

              <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Uložit tag</button>
                <button type="button" class="btn btn-outline" id="cat-cancel-btn" style="display:none;" onclick="resetCatForm()">Zrušit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

@push('scripts')
<script>
const form     = document.getElementById('cat-form');
const methodEl = document.getElementById('cat-method');
const catIdEl  = document.getElementById('cat-id');
const titleEl  = document.getElementById('cat-form-title');
const cancelBtn = document.getElementById('cat-cancel-btn');
const colorInput = document.getElementById('cat-color');
const preview    = document.getElementById('cat-color-preview');
const nameCs     = document.getElementById('cat-name-cs');

// Live preview barvy + názvu
colorInput.addEventListener('input', updatePreview);
nameCs.addEventListener('input', updatePreview);

function updatePreview() {
  const c = colorInput.value;
  const n = nameCs.value || 'Tag';
  preview.style.color = c;
  preview.style.borderColor = c + '88';
  preview.style.background  = c + '22';
  preview.textContent = '#' + n;
}

function editCat(id, nameCs, nameEn, color) {
  document.getElementById('cat-name-cs').value = nameCs;
  document.getElementById('cat-name-en').value = nameEn;
  colorInput.value = color;
  catIdEl.value    = id;
  methodEl.value   = 'PUT';
  form.action = `/admin/categories/${id}`;
  titleEl.textContent = 'Upravit tag';
  cancelBtn.style.display = 'block';
  updatePreview();
  document.getElementById('cat-form-card').scrollIntoView({ behavior: 'smooth' });
}

function resetCatForm() {
  form.reset();
  catIdEl.value  = '';
  methodEl.value = 'POST';
  form.action    = '{{ route("admin.categories.store") }}';
  titleEl.textContent = 'Nový tag';
  cancelBtn.style.display = 'none';
  colorInput.value = '#2abfbf';
  updatePreview();
}
</script>
@endpush
@endsection