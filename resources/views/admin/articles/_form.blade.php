@php
  $isEdit = isset($article);
  $action = $isEdit ? route('admin.articles.update', $article) : route('admin.articles.store');
  $method = $isEdit ? 'PUT' : 'POST';
@endphp

@extends('layouts.hc')

@section('title', ($isEdit ? 'Upravit článek' : 'Nový článek') . ' | Admin HC Říčany')

@section('content')
<div class="admin-panel" style="display:block;">
  <div class="admin-nav">
    <div class="admin-nav-left">
      <div class="admin-logo">HC ŘÍČANY</div>
      <div class="admin-badge">Administrace</div>
    </div>
    <div style="display:flex;align-items:center;gap:16px;">
      <span style="font-size:0.8rem;color:var(--gray-light);">
        <span style="color:var(--teal);">●</span> {{ auth()->user()->name }}
      </span>
      <a href="{{ route('home') }}" class="admin-close-btn">Zpět na web</a>
    </div>
  </div>

  <div class="admin-layout">
    @include('partials._admin_sidebar')

    <main class="admin-content">
      <div class="admin-panel-section active">
        <div class="admin-page-title">{{ $isEdit ? 'Upravit článek' : 'Nový článek' }}</div>
        <div class="admin-page-subtitle">{{ $isEdit ? 'Upravte obsah a uložte změny.' : 'Vytvořte nový příspěvek nebo novinku.' }}</div>

        @if($errors->any())
          <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:16px;margin-bottom:20px;color:#f88;">
            @foreach($errors->all() as $e)
              <div>• {{ $e }}</div>
            @endforeach
          </div>
        @endif

        <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
          @csrf
          @if($isEdit) @method('PUT') @endif

          <div class="admin-form-grid">
            <div>
              <div class="admin-form-card">
                <div class="admin-form-section-title">Obsah článku</div>

                @php
                  $enComplete = filled(old('excerpt_en', $article->excerpt_en ?? null))
                             && filled(old('body_en', $article->body_en ?? null));
                @endphp

                <div class="lang-tabs">
                  <button type="button" class="lang-tab active" data-tab="cs" onclick="switchArticleTab('cs')">Čeština</button>
                  <button type="button" class="lang-tab" data-tab="en" onclick="switchArticleTab('en')">
                    English
                    @unless($enComplete)
                      <span class="lang-tab-warn" title="Perex nebo text v angličtině chybí — článek se v EN verzi nezobrazí.">⚠</span>
                    @endunless
                  </button>
                  <span class="lang-tab lang-tab--disabled" title="Připravujeme">Deutsch</span>
                </div>

                @unless($enComplete)
                  <p class="lang-tab-hint">
                    Bez vyplněného anglického perexu a textu se článek v anglické verzi webu nezobrazí (v CZ verzi zůstane beze změny).
                  </p>
                @endunless

                <div class="lang-tab-panel" data-panel="cs">
                  <div class="form-group">
                    <label class="form-label">Nadpis (česky) *</label>
                    <input type="text" name="title_cs" class="form-control" required
                           value="{{ old('title_cs', $article->title_cs ?? '') }}"
                           placeholder="Název článku v češtině...">
                  </div>

                  <div class="form-group">
                    <label class="form-label">Perex – česky (krátký popis)</label>
                    <textarea name="excerpt_cs" class="form-control" style="min-height:80px;"
                              placeholder="Krátký popis článku...">{{ old('excerpt_cs', $article->excerpt_cs ?? '') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label class="form-label">Text článku – česky</label>
                    <textarea name="body_cs" class="form-control" style="min-height:160px;"
                              placeholder="Plný text článku v češtině...">{{ old('body_cs', $article->body_cs ?? '') }}</textarea>
                  </div>
                </div>

                <div class="lang-tab-panel" data-panel="en" style="display:none;">
                  <div class="form-group">
                    <label class="form-label">Nadpis (anglicky)</label>
                    <input type="text" name="title_en" class="form-control"
                           value="{{ old('title_en', $article->title_en ?? '') }}"
                           placeholder="Article title in English...">
                  </div>

                  <div class="form-group">
                    <label class="form-label">Perex – anglicky</label>
                    <textarea name="excerpt_en" class="form-control" style="min-height:80px;"
                              placeholder="Short article description...">{{ old('excerpt_en', $article->excerpt_en ?? '') }}</textarea>
                  </div>

                  <div class="form-group">
                    <label class="form-label">Text článku – anglicky</label>
                    <textarea name="body_en" class="form-control" style="min-height:160px;"
                              placeholder="Full article text in English...">{{ old('body_en', $article->body_en ?? '') }}</textarea>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <div class="admin-form-card" style="margin-bottom:20px;">
                <div class="admin-form-section-title">Nastavení publikace</div>

                <div class="form-group">
                  <label class="form-label">Tag</label>
                  <select name="category_id" id="categorySelect" class="form-control" onchange="updateCatPreview(this)">
                    <option value="">— bez tagu —</option>
                    @foreach($categories as $cat)
                      <option value="{{ $cat->id }}"
                              data-color="{{ $cat->color }}"
                              data-name="{{ $cat->name_cs }}"
                              {{ old('category_id', $article->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name_cs }}{{ $cat->name_en ? ' / '.$cat->name_en : '' }}
                      </option>
                    @endforeach
                  </select>
                  <div id="catPreview" style="margin-top:8px;"></div>
                </div>

                <div class="form-group">
                  <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_published" value="1" style="accent-color:var(--teal);"
                           {{ old('is_published', $article->is_published ?? true) ? 'checked' : '' }}>
                    <span>Publikovat ihned</span>
                  </label>
                </div>

                <div class="form-group">
                  <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_featured" value="1" style="accent-color:var(--teal);"
                           {{ old('is_featured', $article->is_featured ?? false) ? 'checked' : '' }}>
                    <span>Zobrazit jako Featured (hlavní)</span>
                  </label>
                  <small style="color:var(--gray-light);display:block;margin-top:4px;">Pouze jeden článek může být featured najednou.</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">
                  {{ $isEdit ? 'Uložit změny' : 'Publikovat článek' }}
                </button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline" style="width:100%;margin-top:10px;display:block;text-align:center;">
                  Zrušit
                </a>
              </div>

              <div class="admin-form-card">
                <div class="admin-form-section-title">Fotografie článku</div>
                @if($isEdit && $article->image)
                  <div style="margin-bottom:12px;">
                    <img src="{{ Storage::url($article->image) }}" alt="Aktuální foto" style="max-width:100%;border-radius:6px;">
                  </div>
                @endif
                <div class="form-group">
                  <label class="form-label">Nahrát fotografii</label>
                  <input type="file" name="image" class="form-control" accept="image/*">
                  <small style="color:var(--gray-light);">JPG, PNG, WebP · max 4 MB</small>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<style>
  .lang-tabs { display: flex; gap: 6px; margin-bottom: 4px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0; }
  .lang-tab {
    font-family: var(--font-cond); font-size: 0.85rem; font-weight: 600; letter-spacing: 0.03em;
    background: transparent; border: none; border-bottom: 2px solid transparent;
    color: var(--gray-light); padding: 8px 14px; cursor: pointer; display: flex; align-items: center; gap: 6px;
  }
  .lang-tab.active { color: var(--teal); border-bottom-color: var(--teal); }
  .lang-tab--disabled { opacity: 0.35; cursor: not-allowed; }
  .lang-tab-warn { color: #facc15; }
  .lang-tab-hint { font-size: 0.8rem; color: #facc15; margin: 10px 0 16px; }
</style>

<script>
function switchArticleTab(lang) {
  document.querySelectorAll('.lang-tab[data-tab]').forEach(t => t.classList.toggle('active', t.dataset.tab === lang));
  document.querySelectorAll('.lang-tab-panel').forEach(p => p.style.display = (p.dataset.panel === lang ? 'block' : 'none'));
}

function updateCatPreview(sel) {
  var opt = sel.options[sel.selectedIndex];
  var preview = document.getElementById('catPreview');
  if (!opt.value) { preview.innerHTML = ''; return; }
  var color = opt.dataset.color || '#2abfbf';
  var name  = opt.dataset.name  || opt.text;
  preview.innerHTML =
    '<span style="display:inline-block;padding:3px 10px;border-radius:4px;font-size:0.78rem;font-weight:600;letter-spacing:.04em;'
    + 'background:' + color + '1a;color:' + color + ';border:1px solid ' + color + '55;">'
    + '#' + name + '</span>';
}
// Init on load
document.addEventListener('DOMContentLoaded', function() {
  var sel = document.getElementById('categorySelect');
  if (sel) updateCatPreview(sel);
});
</script>
@endsection