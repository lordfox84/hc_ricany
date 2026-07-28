@extends('layouts.hc')

@section('title', 'HC COM-SYS Říčany')

@section('content')

<!-- NAVBAR -->
@include('partials._navbar')

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-lines"></div>
  <div class="hero-circle"></div>
  <div class="hero-number">16</div>
  <div class="container hero-content">
    <p class="hero-eyebrow fade-in fade-in-1">
      <span>{!! __('site.hero.eyebrow') !!}</span>
    </p>
    <div style="display:flex;align-items:center;gap:40px;flex-wrap:wrap;" class="fade-in fade-in-2">
      <div class="neon-logo-wrap" style="width:clamp(140px,18vw,220px);height:clamp(140px,18vw,220px);">
        <img class="logo-glow" src="{{ asset('assets/img/HC_Ricany_logo.png') }}" alt="" aria-hidden="true">
        <img class="logo-top"  src="{{ asset('assets/img/HC_Ricany_logo.png') }}" alt="HC COM-SYS Říčany logo">
      </div>
      <h1>
        <span>{{ __('site.hero.title') }}</span>
        <em>{{ __('site.hero.title_em') }}</em>
      </h1>
    </div>
    <p class="hero-sub fade-in fade-in-3">
      {{ __('site.hero.subtitle') }}
    </p>
    <div class="hero-ctas fade-in fade-in-3">
      <a href="#news" class="btn btn-primary">{{ __('site.hero.cta_news') }}</a>
      <a href="#contact" class="btn btn-outline">{{ __('site.hero.cta_contact') }}</a>
    </div>
    <div class="hero-stats fade-in fade-in-4">
      <div>
        <div class="hero-stat-num">7</div>
        <div class="hero-stat-label">{!! __('site.hero.stat_teams') !!}</div>
      </div>
      <div>
        <div class="hero-stat-num">75+</div>
        <div class="hero-stat-label">{{ __('site.hero.stat_years') }}</div>
      </div>
      <div>
        <div class="hero-stat-num">200+</div>
        <div class="hero-stat-label">{{ __('site.hero.stat_players') }}</div>
      </div>
    </div>
  </div>
</section>

<!-- TICKER -->
<div class="ticker">
  <div class="ticker-inner" id="ticker">
    <span class="ticker-item">🏒 HC COMSYS Říčany <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">{{ __('site.ticker.newsletter') }} <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">🏆 Sezona 2024/25 <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">{{ __('site.ticker.social') }} <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">🏒 HC COMSYS Říčany <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">{{ __('site.ticker.newsletter') }} <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">🏆 Sezona 2024/25 <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">{{ __('site.ticker.social') }} <span class="ticker-sep">&middot;</span></span>
  </div>
</div>

<!-- NEWS -->
<section class="section" id="news">
  <div class="container">
    <div class="section-header">
      <div>
        <div class="tag">{{ __('site.home.news_tag') }}</div>
        <h2>{{ __('site.home.news_title') }}</h2>
      </div>
      <a href="{{ route('articles.index') }}" class="btn btn-outline">{{ __('site.home.news_all') }}</a>
    </div>

    <div class="news-grid" id="news-grid">
      @forelse($articles as $article)
        <a href="{{ route('articles.show', $article) }}" class="news-card {{ $article->is_featured ? 'featured' : '' }}" style="text-decoration:none;color:inherit;" data-id="{{ $article->id }}">
          <div class="news-date">
            {{ $article->published_at ? ($article->published_at->locale(app()->getLocale())->isoFormat('LL')) : '' }} &middot; {{ $article->trans('category') }}
          </div>
          <h3>{{ $article->trans('title') }}</h3>
          <p>{{ $article->trans('excerpt') }}</p>
          <div class="news-card-arrow"><span>{{ __('site.home.read_more') }}</span> &rarr;</div>
        </a>
      @empty
        <p style="color:var(--gray-light);grid-column:1/-1;">{{ __('site.home.news_empty') }}</p>
      @endforelse
    </div>
  </div>
</section>

<!-- TEAMS -->
<section class="section teams-section" id="teams">
  <div class="container">
    <div class="tag">{{ __('site.home.teams_tag') }}</div>
    <h2>{{ __('site.home.teams_title') }}</h2>
    <div class="teams-grid">
      @foreach($teams as $team)
        <a href="{{ route('teams.show', $team) }}" class="team-card" style="text-decoration:none;border-top-color:{{ $team->color }};">
          <div class="team-name">{{ strtoupper($team->trans('name')) }}</div>
          @if($team->age_group)
            <div class="team-desc">{{ $team->age_group }}</div>
          @endif
          @if($team->trans('description'))
            <div class="team-desc" style="margin-top:6px;">{{ $team->trans('description') }}</div>
          @endif
        </a>
      @endforeach
    </div>
  </div>
</section>

<!-- GALLERY -->
<section class="section" id="gallery">
  <div class="container">
    <div class="section-header">
      <div>
        <div class="tag">{{ __('site.home.gallery_tag') }}</div>
        <h2>{{ __('site.home.gallery_title') }}</h2>
      </div>
      <a href="#" class="btn btn-outline">{{ __('site.home.gallery_all') }}</a>
    </div>
    <div class="gallery-grid">
      <div class="gallery-item"><div class="gallery-placeholder">🏒</div><div class="gallery-overlay">🔍</div></div>
      <div class="gallery-item"><div class="gallery-placeholder">🥅</div><div class="gallery-overlay">🔍</div></div>
      <div class="gallery-item"><div class="gallery-placeholder">🏆</div><div class="gallery-overlay">🔍</div></div>
      <div class="gallery-item"><div class="gallery-placeholder">⛸️</div><div class="gallery-overlay">🔍</div></div>
      <div class="gallery-item"><div class="gallery-placeholder">🎽</div><div class="gallery-overlay">🔍</div></div>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-section" id="contact">
  <div class="container">
    <div class="newsletter-inner">
      <div>
        <div class="tag" style="border-color:rgba(0,0,0,0.3);color:rgba(0,0,0,0.6);">{{ __('site.home.newsletter_tag') }}</div>
        <h2>{{ __('site.home.newsletter_title') }}</h2>
        <p>{{ __('site.home.newsletter_lead') }}</p>
      </div>
      <div>
        @if(session('newsletter_success'))
          <div style="background:rgba(74,222,128,0.1);border:1px solid #4ade80;border-radius:8px;padding:20px 24px;color:#4ade80;font-size:0.95rem;">
            ✓ <span>{{ __('site.home.newsletter_success') }}</span>
          </div>
        @else
          @if($errors->any())
            <div style="background:rgba(248,113,113,0.1);border:1px solid #f87171;border-radius:8px;padding:14px 18px;color:#f87171;font-size:0.85rem;margin-bottom:16px;">
              @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
            </div>
          @endif
          <form class="newsletter-form" method="POST" action="{{ route('subscribers.store') }}">
            @csrf
            <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
            <div class="form-row">
              <input type="text" name="first_name" class="form-input"
                     value="{{ old('first_name') }}"
                     placeholder="{{ __('site.home.newsletter_first_name') }}">
              <input type="text" name="last_name" class="form-input"
                     value="{{ old('last_name') }}"
                     placeholder="{{ __('site.home.newsletter_last_name') }}">
            </div>
            <div class="form-row" style="margin-top:10px;">
              <input type="email" name="email" id="nl-email" class="form-input" required
                     value="{{ old('email') }}"
                     placeholder="{{ __('site.home.newsletter_email') }}">
            </div>
            <div class="checkbox-row" style="margin-top:14px;">
              <input type="checkbox" name="gdpr_consent" id="nl-gdpr" value="1" required
                     {{ old('gdpr_consent') ? 'checked' : '' }}>
              <label for="nl-gdpr">{{ __('site.home.newsletter_gdpr') }}</label>
            </div>
            <div class="checkbox-row" style="margin-top:10px;">
              <input type="checkbox" name="marketing_consent" id="nl-marketing" value="1" required
                     {{ old('marketing_consent') ? 'checked' : '' }}>
              <label for="nl-marketing">{{ __('site.home.newsletter_marketing') }}</label>
            </div>
            <button type="submit" class="btn-dark" style="margin-top:18px;">
              {{ __('site.home.newsletter_submit') }}
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>
</section>

<!-- MEMBER OF -->
<section class="member-of-section">
  <div class="container">
    <p class="sponsors-label">{{ __('site.home.member_of') }}</p>
    <div style="display:flex;justify-content:center;">
      @php $phPath = public_path('assets/img/sponsors/piskej-hokej-logo.png'); @endphp
      <a href="https://piskejhokej.cz/" target="_blank" rel="noopener" class="member-of-link">
        <img src="{{ asset('assets/img/sponsors/piskej-hokej-logo.png') }}?v={{ file_exists($phPath) ? filemtime($phPath) : 1 }}"
             alt="Pískej hokej">
      </a>
    </div>
  </div>
</section>

<!-- SPONSORS -->
<section class="sponsors-section">
  <div class="container">
    <p class="sponsors-label">{!! __('site.home.sponsors_label') !!}</p>
    @php
      $spLogos = [
        ['file' => 'com-sys-arena-logo.png', 'url' => 'https://comsysicearena.cz/',   'alt' => 'COM-SYS Arena',           'main' => true],
        ['file' => 'comsys.png',             'url' => 'https://www.comsys.cz/',        'alt' => 'COM-SYS'],
        ['file' => 'strida-sport-logo.png',  'url' => 'https://www.stridasport.cz/cs/module/ups_storesgroups/store?id_store=9', 'alt' => 'Střída Sport'],
        ['file' => 'Logo_H2invest_nw_top.png','url'=> 'https://h2invest.cz/cs/',       'alt' => 'H2invest'],
        ['file' => 'logo-dva-jedna.png',     'url' => 'https://2jedna.cz/',            'alt' => '2jedna'],
        ['file' => 'AGA_logo_v1.png',        'url' => 'https://www.golmansketreninky.cz/', 'alt' => 'AGA Gólmanské tréninky'],
        ['file' => 'pojd-hrat-hokej_logo.png','url'=> 'https://www.pojdhrathokej.cz/','alt' => 'Pojď hrát hokej'],
        ['file' => 'ricany-01.png',          'url' => 'https://www.ricany.cz/',        'alt' => 'Město Říčany'],
      ];
    @endphp
    <div class="sponsors-grid">
      @foreach($spLogos as $sp)
        @php $path = public_path('assets/img/sponsors/' . $sp['file']); @endphp
        <div class="sponsor-item {{ !empty($sp['main']) ? 'main' : '' }}">
          <a href="{{ $sp['url'] }}" target="_blank" rel="noopener">
            <img src="{{ asset('assets/img/sponsors/' . $sp['file']) }}?v={{ file_exists($path) ? filemtime($path) : 1 }}"
                 alt="{{ $sp['alt'] }}">
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div style="font-family:var(--font-display);font-size:2rem;color:var(--teal);">HC COMSYS ŘÍČANY</div>
        <p>{{ __('site.footer.tagline') }}</p>
        <div class="social-links" style="margin-top:20px;">
          <a href="https://www.facebook.com/IceArenaRicany" target="_blank" rel="noopener" class="social-link" title="Facebook – Ice Arena Říčany"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="https://www.facebook.com/profile.php?id=61556595611810" target="_blank" rel="noopener" class="social-link" title="Facebook – HC Říčany"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/comsys_ice_arena_ricany/" target="_blank" rel="noopener" class="social-link" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
        </div>
      </div>
      <div>
        <div class="footer-heading">{{ __('site.footer.nav_heading') }}</div>
        <ul class="footer-links">
          <li><a href="#news">{{ __('site.nav.news') }}</a></li>
          <li><a href="#teams">{{ __('site.nav.teams') }}</a></li>
          <li><a href="{{ route('camps.index') }}">{{ __('site.nav.camps') }}</a></li>
          <li><a href="#gallery">{{ __('site.nav.gallery') }}</a></li>
          <li><a href="#contact">{{ __('site.nav.contact') }}</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-heading">{{ __('site.footer.club_heading') }}</div>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}">{{ __('site.footer.club_history') }}</a></li>
          <li><a href="#">{{ __('site.footer.club_management') }}</a></li>
          <li><a href="#">{{ __('site.footer.club_coaches') }}</a></li>
          <li><a href="#">{{ __('site.footer.club_sponsors') }}</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-heading">{{ __('site.footer.contact_heading') }}</div>
        <ul class="footer-links">
          <li>HC COM-SYS Říčany</li>
          <li>Škroupova 2625</li>
          <li>251 01 Říčany</li>
          <li style="margin-top:10px;"><a href="mailto:info@hc-ricany.cz"><i class="fa-solid fa-envelope" style="width:16px;"></i> info@hc-ricany.cz</a></li>
          <li><a href="tel:+420737268000"><i class="fa-solid fa-phone" style="width:16px;"></i> 737 268 000</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© {{ date('Y') }} HC COMSYS Říčany. {{ __('site.footer.copyright') }}</p>
      <p>{{ __('site.footer.made_with') }}</p>
    </div>
  </div>
</footer>

<!-- SCROLL TO TOP -->
<button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Nahoru" title="Nahoru">
  &#8679;
</button>

<style>
  #scrollTop {
    position: fixed;
    bottom: 32px;
    right: 32px;
    width: 44px;
    height: 44px;
    background: var(--teal);
    color: #000;
    border: none;
    border-radius: 4px;
    font-size: 1.6rem;
    line-height: 1;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: opacity 0.25s, visibility 0.25s, transform 0.25s, background 0.2s;
    z-index: 999;
  }
  #scrollTop:hover { background: var(--teal-light); }
  #scrollTop.visible { opacity: 1; visibility: visible; transform: translateY(0); }
</style>

<script>
  (function () {
    var btn = document.getElementById('scrollTop');
    window.addEventListener('scroll', function () {
      btn.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
  })();
</script>
@endsection