@extends('layouts.hc')
@section('title', 'Nový termín bruslení | Admin HC Říčany')

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
        <div class="admin-page-title">Nový termín bruslení</div>
        <div class="admin-page-subtitle"><a href="{{ route('admin.skating.index') }}" style="color:var(--teal);">← Zpět na seznam</a></div>

        <form method="POST" action="{{ route('admin.skating.store') }}" style="margin-top:28px;">
          @csrf
          @include('admin.skating._form')
          <div style="margin-top:24px;display:flex;gap:12px;">
            <button type="submit" class="admin-btn">Uložit termín</button>
            <a href="{{ route('admin.skating.index') }}" class="admin-btn" style="background:transparent;border:1px solid #555;">Zrušit</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>
@endsection
