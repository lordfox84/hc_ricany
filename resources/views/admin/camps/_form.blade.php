@php
  $isEdit = isset($camp);
  $action = $isEdit ? route('admin.camps.update', $camp) : route('admin.camps.store');
  $existingVariants = old('variants', $isEdit ? ($camp->variants ?? []) : []);
@endphp

@extends('layouts.hc')
@section('title', ($isEdit ? 'Upravit kemp' : 'Nový kemp') . ' | Admin HC Říčany')

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
    </div>
  </div>

  <div class="admin-layout">
    @include('partials._admin_sidebar')

    <main class="admin-content">
      <div class="admin-panel-section active">
        <div class="admin-page-title">{{ $isEdit ? 'Upravit kemp' : 'Nový kemp' }}</div>
        <div class="admin-page-subtitle">{{ $isEdit ? 'Upravte informace o kempu.' : 'Přidejte nový kemp nebo tábor.' }}</div>

        @if($errors->any())
          <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:16px;margin-bottom:20px;color:#f88;">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
          </div>
        @endif

        <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
          @csrf
          @if($isEdit) @method('PUT') @endif

          <div class="admin-form-grid">

            {{-- LEVÝ SLOUPEC --}}
            <div>
              <div class="admin-form-card">
                <div class="admin-form-section-title">Obsah</div>

                <div class="form-group">
                  <label class="form-label">Název kempu (česky) *</label>
                  <input type="text" name="title_cs" class="form-control" required
                         value="{{ old('title_cs', $camp->title_cs ?? '') }}"
                         placeholder="Letní hokejový kemp 2025...">
                </div>

                <div class="form-group">
                  <label class="form-label">Název kempu (anglicky)</label>
                  <input type="text" name="title_en" class="form-control"
                         value="{{ old('title_en', $camp->title_en ?? '') }}"
                         placeholder="Summer Hockey Camp 2025...">
                </div>

                <div class="form-group">
                  <label class="form-label">Perex – česky</label>
                  <textarea name="excerpt_cs" class="form-control" style="min-height:80px;"
                            placeholder="Krátký popis kempu...">{{ old('excerpt_cs', $camp->excerpt_cs ?? '') }}</textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Perex – anglicky</label>
                  <textarea name="excerpt_en" class="form-control" style="min-height:80px;"
                            placeholder="Short camp description...">{{ old('excerpt_en', $camp->excerpt_en ?? '') }}</textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Popis kempu – česky</label>
                  <textarea name="body_cs" class="form-control" style="min-height:200px;"
                            placeholder="Podrobné informace o kempu, program, cena...">{{ old('body_cs', $camp->body_cs ?? '') }}</textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Popis kempu – anglicky</label>
                  <textarea name="body_en" class="form-control" style="min-height:200px;"
                            placeholder="Detailed camp information, program, price...">{{ old('body_en', $camp->body_en ?? '') }}</textarea>
                </div>
              </div>

              {{-- VARIANTY --}}
              <div class="admin-form-card" style="margin-top:20px;">
                <div class="admin-form-section-title">Varianty kempu</div>
                <div style="font-size:0.82rem;color:var(--gray-light);margin-bottom:14px;">
                  Každá varianta bude nabídnuta jako možnost výběru v registračním formuláři (radio tlačítko).
                  Např. „26.7.–1.8.2026 včetně ubytování".
                </div>

                <div id="variants-list">
                  @forelse($existingVariants as $i => $variant)
                    <div class="variant-row" style="display:flex;gap:8px;margin-bottom:8px;">
                      <input type="text" name="variants[]" class="form-control" style="flex:1;"
                             value="{{ $variant }}"
                             placeholder="Varianta {{ $i + 1 }}…">
                      <button type="button" onclick="removeVariant(this)"
                              style="flex-shrink:0;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;border-radius:6px;padding:0 14px;cursor:pointer;font-size:1.1rem;line-height:1;">×</button>
                    </div>
                  @empty
                    <div class="variant-row" style="display:flex;gap:8px;margin-bottom:8px;">
                      <input type="text" name="variants[]" class="form-control" style="flex:1;" placeholder="Varianta 1…">
                      <button type="button" onclick="removeVariant(this)"
                              style="flex-shrink:0;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;border-radius:6px;padding:0 14px;cursor:pointer;font-size:1.1rem;line-height:1;">×</button>
                    </div>
                  @endforelse
                </div>

                <button type="button" onclick="addVariant()"
                        style="margin-top:6px;background:rgba(42,191,191,0.08);color:var(--teal);border:1px dashed var(--teal);border-radius:6px;padding:9px 16px;cursor:pointer;font-size:0.82rem;width:100%;">
                  + Přidat variantu
                </button>
              </div>
            </div>

            {{-- PRAVÝ SLOUPEC --}}
            <div>
              <div class="admin-form-card" style="margin-bottom:20px;">
                <div class="admin-form-section-title">Termín &amp; publikace</div>

                <div class="form-group">
                  <label class="form-label">Typ kempu</label>
                  <select name="type" class="form-control">
                    <option value="letni"  {{ old('type', $camp->type ?? 'letni')  === 'letni'  ? 'selected' : '' }}>Letní kemp</option>
                    <option value="skills" {{ old('type', $camp->type ?? 'letni')  === 'skills' ? 'selected' : '' }}>Skills kemp</option>
                  </select>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                  <div class="form-group">
                    <label class="form-label">Datum od</label>
                    <input type="date" name="date_from" class="form-control"
                           value="{{ old('date_from', isset($camp->date_from) ? $camp->date_from->format('Y-m-d') : '') }}">
                  </div>
                  <div class="form-group">
                    <label class="form-label">Datum do</label>
                    <input type="date" name="date_to" class="form-control"
                           value="{{ old('date_to', isset($camp->date_to) ? $camp->date_to->format('Y-m-d') : '') }}">
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-label">Kapacita (max. počet míst)</label>
                  <input type="number" name="capacity" class="form-control" min="1"
                         value="{{ old('capacity', $camp->capacity ?? '') }}" placeholder="Ponechte prázdné = neomezeno">
                </div>

                <div class="form-group">
                  <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_published" value="1" style="accent-color:var(--teal);"
                           {{ old('is_published', $camp->is_published ?? true) ? 'checked' : '' }}>
                    <span>Publikovat ihned</span>
                  </label>
                </div>
                <div class="form-group">
                  <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="registration_open" value="1" style="accent-color:var(--teal);"
                           {{ old('registration_open', $camp->registration_open ?? false) ? 'checked' : '' }}>
                    <span>Otevřít registrace</span>
                  </label>
                  <small style="color:var(--gray-light);margin-top:4px;display:block;">Po zaškrtnutí se na webu zobrazí registrační formulář.</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">
                  {{ $isEdit ? 'Uložit změny' : 'Přidat kemp' }}
                </button>
                <a href="{{ route('admin.camps.index') }}" class="btn btn-outline" style="width:100%;margin-top:10px;display:block;text-align:center;">
                  Zrušit
                </a>
              </div>

              <div class="admin-form-card">
                <div class="admin-form-section-title">Plakát kempu</div>
                @if($isEdit && $camp->poster)
                  <div style="margin-bottom:12px;">
                    <img src="{{ asset('storage/' . $camp->poster) }}" alt="Plakát" style="max-width:100%;border-radius:6px;">
                  </div>
                @endif
                <div class="form-group">
                  <label class="form-label">Nahrát plakát / obrázek</label>
                  <input type="file" name="poster" class="form-control" accept="image/*">
                  <small style="color:var(--gray-light);">JPG, PNG, WebP · max 8 MB</small>
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<script>
function addVariant() {
  var list = document.getElementById('variants-list');
  var count = list.querySelectorAll('.variant-row').length + 1;
  var row = document.createElement('div');
  row.className = 'variant-row';
  row.style.cssText = 'display:flex;gap:8px;margin-bottom:8px;';
  row.innerHTML =
    '<input type="text" name="variants[]" class="form-control" style="flex:1;" placeholder="Varianta ' + count + '…">' +
    '<button type="button" onclick="removeVariant(this)" style="flex-shrink:0;background:#3a1a1a;color:#f87171;border:1px solid #f8717155;border-radius:6px;padding:0 14px;cursor:pointer;font-size:1.1rem;line-height:1;">×</button>';
  list.appendChild(row);
  row.querySelector('input').focus();
}

function removeVariant(btn) {
  var list = document.getElementById('variants-list');
  if (list.querySelectorAll('.variant-row').length <= 1) {
    // Zachovat alespoň jeden řádek, pouze vymazat hodnotu
    btn.previousElementSibling.value = '';
    return;
  }
  btn.parentElement.remove();
}
</script>
@endsection
