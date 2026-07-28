<nav id="navbar">
  <a href="{{ route('home') }}" class="nav-logo">
    <div class="nav-logo-emblem">
      <img src="{{ asset('assets/img/HC_Ricany_logo.png') }}" alt="HC COM-SYS Říčany" style="width:36px;height:36px;object-fit:contain;display:block;">
    </div>
    <div>
      <div class="nav-logo-text">HC COM-SYS <span>Říčany</span></div>
    </div>
  </a>

  <ul class="nav-links" id="nav-links">
    <li>
      <a href="{{ request()->routeIs('articles.*') ? route('articles.index') : route('home').'#news' }}"
         {{ request()->routeIs('articles.*') ? 'style="color:var(--teal);"' : '' }}>{{ __('site.nav.news') }}</a>
    </li>

    <li>
      <a href="{{ route('about') }}"
         {{ request()->routeIs('about') ? 'style="color:var(--teal);"' : '' }}>{{ __('site.nav.about') }}</a>
    </li>

    <li class="has-dropdown">
      <a href="{{ route('home') }}#teams">{{ __('site.nav.teams') }}</a>
      <ul class="dropdown">
        @foreach($teams as $t)
          <li>
            <a href="{{ route('teams.show', $t) }}"
               style="{{ isset($team) && $t->id === $team->id ? 'color:var(--teal);' : '' }}">
              {{ $t->trans('name') }}{{ $t->age_group ? ' · '.$t->age_group : '' }}
            </a>
          </li>
        @endforeach
      </ul>
    </li>

    <li class="has-dropdown">
      <a href="{{ route('camps.index') }}"
         {{ request()->routeIs('camps.*') ? 'style="color:var(--teal);"' : '' }}>{{ __('site.nav.camps') }}</a>
      <ul class="dropdown">
        <li><a href="{{ route('camps.index', ['typ' => 'letni']) }}">{{ __('site.nav.camps_summer') }}</a></li>
        <li><a href="{{ route('camps.index', ['typ' => 'skills']) }}">{{ __('site.nav.camps_skills') }}</a></li>
      </ul>
    </li>

    <li><a href="{{ route('home') }}#gallery">{{ __('site.nav.gallery') }}</a></li>
    <li><a href="{{ route('home') }}#contact">{{ __('site.nav.contact') }}</a></li>
    <li><a href="https://comsysicearena.cz/" target="_blank" rel="noopener" style="color:var(--teal);">{{ __('site.nav.ice_booking') }} &nearr;</a></li>
  </ul>

  <div class="nav-right">
    <div class="lang-switcher">
      <a href="{{ route('locale.switch', 'cs') }}" class="lang-btn {{ app()->getLocale() === 'cs' ? 'active' : '' }}">CZ</a>
      <a href="{{ route('locale.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
      <span class="lang-btn lang-btn--disabled" title="Připravujeme / Coming soon" aria-disabled="true">DE</span>
    </div>
    @auth
      <a href="{{ route('admin.dashboard') }}" class="nav-admin-btn"><span>{{ __('site.nav.admin') }}</span></a>
    @else
      <a href="{{ route('login') }}" class="nav-admin-btn"><span>{{ __('site.nav.admin') }}</span></a>
    @endauth
    <div class="hamburger" onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>
