@php
  $isEdit = isset($user);
  $action = $isEdit ? route('admin.users.update', $user) : route('admin.users.store');
@endphp

@extends('layouts.hc')
@section('title', ($isEdit ? 'Upravit uživatele' : 'Nový uživatel') . ' | Admin HC Říčany')

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
        <div class="admin-page-title">{{ $isEdit ? 'Upravit uživatele' : 'Nový uživatel' }}</div>

        @if($errors->any())
          <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:16px;margin-bottom:20px;color:#f88;">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
          </div>
        @endif

        @if($isEdit && $user->hasRole(\App\Models\Role::ADMIN))
          <div style="background:rgba(42,191,191,0.08);border:1px solid var(--teal);border-radius:8px;padding:14px 20px;margin-bottom:20px;color:var(--teal);font-size:0.85rem;">
            Tento uživatel má roli Admin. Roli Admin nelze přes toto rozhraní odebrat ani nikomu jinému přidělit.
          </div>
        @endif

        <form method="POST" action="{{ $action }}" style="max-width:520px;">
          @csrf
          @if($isEdit) @method('PUT') @endif

          <div class="admin-form-card">
            <div class="admin-form-section-title">Přihlašovací údaje</div>

            <div class="form-group">
              <label class="form-label">Jméno *</label>
              <input type="text" name="name" class="form-control" required
                     value="{{ old('name', $user->name ?? '') }}" placeholder="Jan Novák">
            </div>

            <div class="form-group">
              <label class="form-label">E-mail *</label>
              <input type="email" name="email" class="form-control" required
                     value="{{ old('email', $user->email ?? '') }}" placeholder="jan.novak@hcricany.cz">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div class="form-group">
                <label class="form-label">{{ $isEdit ? 'Nové heslo' : 'Heslo *' }}</label>
                <div class="password-field">
                  <input type="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }}
                         placeholder="{{ $isEdit ? 'Ponechte prázdné pro zachování' : 'min. 8 znaků' }}">
                  <button type="button" class="password-toggle" onclick="togglePasswordVisibility(this)" aria-label="Zobrazit heslo">
                    <i class="fa-solid fa-eye-slash"></i>
                  </button>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">{{ $isEdit ? 'Nové heslo znovu' : 'Heslo znovu *' }}</label>
                <div class="password-field">
                  <input type="password" name="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }}>
                  <button type="button" class="password-toggle" onclick="togglePasswordVisibility(this)" aria-label="Zobrazit heslo">
                    <i class="fa-solid fa-eye-slash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="admin-form-card" style="margin-top:20px;">
            <div class="admin-form-section-title">Role</div>
            <small style="color:var(--gray-light);display:block;margin-bottom:12px;">Uživatel může mít přiděleno více rolí zároveň.</small>

            @php $userRoleKeys = $isEdit ? $user->roles->pluck('key')->toArray() : old('roles', []); @endphp

            @foreach($roles as $role)
              <label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;margin-bottom:14px;">
                <input type="checkbox" name="roles[]" value="{{ $role->key }}" style="accent-color:var(--teal);margin-top:3px;"
                       {{ in_array($role->key, $userRoleKeys) ? 'checked' : '' }}>
                <span>
                  <span style="display:block;color:var(--white);font-weight:600;">{{ $role->name }}</span>
                  @if($role->description)
                    <small style="color:var(--gray-light);display:block;margin-top:2px;">{{ $role->description }}</small>
                  @endif
                </span>
              </label>
            @endforeach
          </div>

          <div style="display:flex;gap:10px;margin-top:20px;">
            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Uložit změny' : 'Přidat uživatele' }}</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Zrušit</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<style>
  .password-field { position: relative; }
  .password-field .form-control { padding-right: 40px; }
  .password-toggle {
    position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
    background: rgba(255,255,255,0.1); border: none; border-radius: 50%;
    cursor: pointer; font-size: 0.85rem; width: 26px; height: 26px;
    display: flex; align-items: center; justify-content: center;
    color: var(--gray-light); opacity: 0.8; padding: 0; line-height: 1;
  }
  .password-toggle:hover { opacity: 1; background: rgba(255,255,255,0.18); }
  .password-toggle.is-visible { color: var(--teal); opacity: 1; }
</style>
<script>
function togglePasswordVisibility(btn) {
  var input = btn.previousElementSibling;
  var icon  = btn.querySelector('i');
  var willBeVisible = input.type === 'password';

  input.type = willBeVisible ? 'text' : 'password';
  // Ikona odráží aktuální stav: přeškrtnuté oko = heslo skryté, otevřené oko = heslo viditelné.
  icon.classList.toggle('fa-eye', willBeVisible);
  icon.classList.toggle('fa-eye-slash', !willBeVisible);
  btn.classList.toggle('is-visible', willBeVisible);
  btn.setAttribute('aria-label', willBeVisible ? 'Skrýt heslo' : 'Zobrazit heslo');
}
</script>
@endsection
