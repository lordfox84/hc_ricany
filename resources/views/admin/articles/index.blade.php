@extends('layouts.hc')

@section('title', 'Admin – Články | HC Říčany')

@section('content')
<div class="admin-panel" style="display:block;">
  <div class="admin-nav">
    <div class="admin-nav-left">
      <div class="admin-logo">HC ŘÍČANY</div>
      <div class="admin-badge">Administrace</div>
    </div>
    <div style="display:flex;align-items:center;gap:16px;">
      <span style="font-size:0.8rem;color:var(--gray-light);">
        <span style="color:var(--teal);">●</span>
        {{ auth()->user()->name }}
      </span>
      <a href="{{ route('home') }}" class="admin-close-btn">Zpět na web</a>
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="admin-close-btn" style="background:none;border:1px solid #555;">Odhlásit</button>
      </form>
    </div>
  </div>

  <div class="admin-layout">
    <aside class="admin-sidebar">
      <div class="admin-sidebar-section">
        <div class="admin-sidebar-heading">Obsah</div>
        <a class="admin-nav-item active" href="{{ route('admin.articles.index') }}">
          <span class="icon">📝</span> Články &amp; novinky
        </a>
        <a class="admin-nav-item" href="{{ route('admin.articles.create') }}">
          <span class="icon">✏️</span> Nový článek
        </a>
      </div>
    </aside>

    <main class="admin-content">
      <div class="admin-panel-section active">
        <div class="admin-page-title">Články &amp; novinky</div>
        <div class="admin-page-subtitle">Správa publikovaných článků a novinek.</div>

        @if(session('success'))
          <div class="admin-toast" style="position:static;display:block;margin-bottom:20px;opacity:1;transform:none;">
            {{ session('success') }}
          </div>
        @endif

        <div class="admin-table-wrap">
          <div class="admin-table-header">
            <div class="admin-table-title">Všechny články ({{ $articles->count() }})</div>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary" style="font-size:0.78rem;padding:10px 20px;">+ Nový článek</a>
          </div>
          <table>
            <thead>
              <tr>
                <th>Název</th>
                <th>Kategorie</th>
                <th>Autor</th>
                <th>Datum</th>
                <th>Stav</th>
                <th>Akce</th>
              </tr>
            </thead>
            <tbody>
              @forelse($articles as $article)
              <tr>
                <td>
                  {{ $article->title_cs }}
                  @if($article->is_featured)
                    <span style="color:var(--teal);font-size:0.75rem;margin-left:6px;">★ Featured</span>
                  @endif
                </td>
                <td><span class="role-badge role-admin">{{ $article->category_cs }}</span></td>
                <td>{{ $article->author?->name ?? '—' }}</td>
                <td>{{ $article->published_at?->format('d.m.Y') ?? '—' }}</td>
                <td>
                  @if($article->is_published)
                    <span class="status-active">● Publikováno</span>
                  @else
                    <span class="status-inactive">○ Koncept</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('admin.articles.edit', $article) }}" class="action-btn">Upravit</a>
                  <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" style="display:inline;" onsubmit="return confirm('Opravdu smazat článek?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn danger">Smazat</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" style="text-align:center;color:var(--gray-light);padding:40px;">
                  Žádné články. <a href="{{ route('admin.articles.create') }}" style="color:var(--teal);">Vytvořit první článek</a>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>
@endsection