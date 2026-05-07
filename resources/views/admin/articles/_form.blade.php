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
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">
        <div class="admin-sidebar-heading">Obsah</div>
        <a class="admin-nav-item" href="{{ route('admin.articles.index') }}">
          <span class="icon">📝</span> Články &amp; novinky
        </a>
        <a class="admin-nav-item active" href="{{ route('admin.articles.create') }}">
          <span class="icon">✏️</span> Nový článek
        </a>
      </div>
    </aside>

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

                <div class="form-group">
                  <label class="form-label">Nadpis (česky) *</label>
                  <input type="text" name="title_cs" class="form-control" required
                         value="{{ old('title_cs', $article->title_cs ?? '') }}"
                         placeholder="Název článku v češtině...">
                </div>

                <div class="form-group">
                  <label class="form-label">Nadpis (anglicky)</label>
                  <input type="text" name="title_en" class="form-control"
                         value="{{ old('title_en', $article->title_en ?? '') }}"
                         placeholder="Article title in English...">
                </div>

                <div class="form-group">
                  <label class="form-label">Perex – česky (krátký popis)</label>
                  <textarea name="excerpt_cs" class="form-control" style="min-height:80px;"
                            placeholder="Krátký popis článku...">{{ old('excerpt_cs', $article->excerpt_cs ?? '') }}</textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Perex – anglicky</label>
                  <textarea name="excerpt_en" class="form-control" style="min-height:80px;"
                            placeholder="Short article description...">{{ old('excerpt_en', $article->excerpt_en ?? '') }}</textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Text článku – česky</label>
                  <textarea name="body_cs" class="form-control" style="min-height:160px;"
                            placeholder="Plný text článku v češtině...">{{ old('body_cs', $article->body_cs ?? '') }}</textarea>
                </div>

                <div class="form-group">
                  <label class="form-label">Text článku – anglicky</label>
                  <textarea name="body_en" class="form-control" style="min-height:160px;"
                            placeholder="Full article text in English...">{{ old('body_en', $article->body_en ?? '') }}</textarea>
                </div>
              </div>
            </div>

            <div>
              <div class="admin-form-card" style="margin-bottom:20px;">
                <div class="admin-form-section-title">Nastavení publikace</div>

                <div class="form-group">
                  <label class="form-label">Kategorie (česky) *</label>
                  <select name="category_cs" class="form-control">
                    @foreach(['Zápas','Mládež','Klub','Trénink','Akce','Novinky'] as $cat)
                      <option value="{{ $cat }}" {{ old('category_cs', $article->category_cs ?? '') === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label class="form-label">Kategorie (anglicky)</label>
                  <input type="text" name="category_en" class="form-control"
                         value="{{ old('category_en', $article->category_en ?? '') }}"
                         placeholder="Match / Youth / Club...">
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
@endsection