@php
  $isEdit = isset($player);
  $action = $isEdit ? route('admin.players.update', $player) : route('admin.players.store');
@endphp

@extends('layouts.hc')
@section('title', ($isEdit ? 'Upravit hráče' : 'Nový hráč') . ' | Admin HC Říčany')

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
        <div class="admin-page-title">{{ $isEdit ? 'Upravit hráče' : 'Nový hráč' }}</div>

        @if($errors->any())
          <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:16px;margin-bottom:20px;color:#f88;">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
          </div>
        @endif

        <form method="POST" action="{{ $action }}" enctype="multipart/form-data" style="max-width:600px;">
          @csrf
          @if($isEdit) @method('PUT') @endif

          <div class="admin-form-card">
            <div class="admin-form-section-title">Základní údaje</div>

            <div class="form-group">
              <label class="form-label">Tým *</label>
              <select name="team_id" class="form-control" required>
                @foreach($teams as $team)
                  <option value="{{ $team->id }}" {{ old('team_id', $player->team_id ?? '') == $team->id ? 'selected' : '' }}>
                    {{ $team->name_cs }}{{ $team->age_group ? ' ('.$team->age_group.')' : '' }}
                  </option>
                @endforeach
              </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div class="form-group">
                <label class="form-label">Jméno *</label>
                <input type="text" name="first_name" class="form-control" required
                       value="{{ old('first_name', $player->first_name ?? '') }}" placeholder="Jan">
              </div>
              <div class="form-group">
                <label class="form-label">Příjmení *</label>
                <input type="text" name="last_name" class="form-control" required
                       value="{{ old('last_name', $player->last_name ?? '') }}" placeholder="Novák">
              </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
              <div class="form-group">
                <label class="form-label">Číslo dresu</label>
                <input type="number" name="jersey_number" class="form-control" min="0" max="99"
                       value="{{ old('jersey_number', $player->jersey_number ?? '') }}">
              </div>
              <div class="form-group">
                <label class="form-label">Post</label>
                <select name="position" class="form-control">
                  <option value="">— vyberte —</option>
                  @foreach(['Brankář','Obránce','Útočník'] as $pos)
                    <option value="{{ $pos }}" {{ old('position', $player->position ?? '') === $pos ? 'selected' : '' }}>{{ $pos }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Ruka</label>
                <select name="hand" class="form-control">
                  <option value="">— vyberte —</option>
                  @foreach(['Levák','Pravák'] as $h)
                    <option value="{{ $h }}" {{ old('hand', $player->hand ?? '') === $h ? 'selected' : '' }}>{{ $h }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div class="form-group">
                <label class="form-label">Výška (cm)</label>
                <input type="number" name="height_cm" class="form-control" min="100" max="230"
                       value="{{ old('height_cm', $player->height_cm ?? '') }}" placeholder="180">
              </div>
              <div class="form-group">
                <label class="form-label">Váha (kg)</label>
                <input type="number" name="weight_kg" class="form-control" min="20" max="200"
                       value="{{ old('weight_kg', $player->weight_kg ?? '') }}" placeholder="75">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Datum narození <small style="color:var(--gray-light);">(zobrazuje se veřejně na soupisce týmu)</small></label>
              <input type="date" name="date_of_birth" class="form-control"
                     value="{{ old('date_of_birth', isset($player->date_of_birth) ? $player->date_of_birth->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group">
              <label class="form-label">Bio / popis hráče</label>
              <textarea name="bio" class="form-control" style="min-height:80px;">{{ old('bio', $player->bio ?? '') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div class="form-group">
                <label class="form-label">Pořadí</label>
                <input type="number" name="sort_order" class="form-control" min="0"
                       value="{{ old('sort_order', $player->sort_order ?? 0) }}">
              </div>
              @if($isEdit)
              <div class="form-group" style="padding-top:24px;">
                <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                  <input type="checkbox" name="is_active" value="1" style="accent-color:var(--teal);"
                         {{ old('is_active', $player->is_active ?? true) ? 'checked' : '' }}>
                  <span>Aktivní</span>
                </label>
              </div>
              @endif
            </div>
          </div>

          <div class="admin-form-card" style="margin-top:20px;">
            <div class="admin-form-section-title">Fotografie</div>
            @if($isEdit && $player->photo)
              <div style="margin-bottom:12px;">
                <img src="{{ asset('storage/' . $player->photo) }}" alt="Foto" style="max-width:160px;border-radius:6px;">
              </div>
            @endif
            <input type="file" name="photo" class="form-control" accept="image/*">
            <small style="color:var(--gray-light);">JPG, PNG, WebP · max 4 MB</small>
          </div>

          <div style="display:flex;gap:10px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Uložit změny' : 'Přidat hráče' }}</button>
            <a href="{{ route('admin.players.index') }}" class="btn btn-outline">Zrušit</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>
@endsection
