<aside class="admin-sidebar">
  @can('manage-content')
  <div class="admin-sidebar-section">
    <div class="admin-sidebar-heading">Obsah</div>
    <a class="admin-nav-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}"
       href="{{ route('admin.articles.index') }}"><span class="icon">📝</span> Články &amp; novinky</a>
    <a class="admin-nav-item {{ request()->routeIs('admin.articles.create') ? 'active' : '' }}"
       href="{{ route('admin.articles.create') }}"><span class="icon">✏️</span> Nový článek</a>
    <a class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
       href="{{ route('admin.categories.index') }}"><span class="icon">🏷️</span> Tagy</a>
  </div>
  @endcan

  @can('manage-club')
  <div class="admin-sidebar-section" style="margin-top:24px;">
    <div class="admin-sidebar-heading">Klub</div>
    <a class="admin-nav-item {{ request()->routeIs('admin.teams.*') ? 'active' : '' }}"
       href="{{ route('admin.teams.index') }}"><span class="icon">🏒</span> Týmy</a>
    <a class="admin-nav-item {{ request()->routeIs('admin.players.*') ? 'active' : '' }}"
       href="{{ route('admin.players.index') }}"><span class="icon">👤</span> Hráči</a>
    <a class="admin-nav-item {{ request()->routeIs('admin.camps.*') ? 'active' : '' }}"
       href="{{ route('admin.camps.index') }}"><span class="icon">🏕️</span> Kempy</a>
  </div>
  @endcan

  @can('manage-users')
  <div class="admin-sidebar-section" style="margin-top:24px;">
    <div class="admin-sidebar-heading">Uživatelé &amp; marketing</div>
    <a class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
       href="{{ route('admin.users.index') }}"><span class="icon">🔑</span> Uživatelé</a>
    <a class="admin-nav-item {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}"
       href="{{ route('admin.subscribers.index') }}"><span class="icon">📬</span> Odběry</a>
  </div>
  @endcan
</aside>
