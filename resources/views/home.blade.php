@extends('layouts.hc')

@section('title', 'HC COM-SYS Říčany')

@section('content')

<!-- NAVBAR -->
<nav id="navbar">
  <a href="{{ route('home') }}" class="nav-logo">
    <div class="nav-logo-emblem">
      <svg width="32" height="32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="48" stroke="#2abfbf" stroke-width="3" fill="#1c1c1c"/>
        <text x="50" y="66" text-anchor="middle" font-family="'Bebas Neue',sans-serif" font-size="38" fill="#2abfbf">HC</text>
      </svg>
    </div>
    <div>
      <div class="nav-logo-text">HC COM-SYS <span>Říčany</span></div>
    </div>
  </a>

  <ul class="nav-links" id="nav-links">
    <li><a href="#news" data-cs="Novinky" data-en="News">Novinky</a></li>
    <li><a href="#teams" data-cs="Týmy" data-en="Teams">Týmy</a></li>
    <li><a href="#gallery" data-cs="Galerie" data-en="Gallery">Galerie</a></li>
    <li><a href="#contact" data-cs="Kontakt" data-en="Contact">Kontakt</a></li>
    <li><a href="https://comsysicearena.cz/" target="_blank" rel="noopener" data-cs="Rezervace ledu" data-en="Ice Booking" style="color:var(--teal);">Rezervace ledu &nearr;</a></li>
  </ul>

  <div class="nav-right">
    <div class="lang-switcher">
      <button class="lang-btn active" onclick="setLang('cs')">CZ</button>
      <button class="lang-btn" onclick="setLang('en')">EN</button>
    </div>
    @auth
      <a href="{{ route('admin.articles.index') }}" class="nav-admin-btn">
        <span>Admin</span>
      </a>
    @else
      <a href="{{ route('login') }}" class="nav-admin-btn">
        <span data-cs="Admin" data-en="Admin">Admin</span>
      </a>
    @endauth
    <div class="hamburger" onclick="toggleMobileMenu()">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-lines"></div>
  <div class="hero-circle"></div>
  <div class="hero-number">16</div>
  <div class="container hero-content">
    <p class="hero-eyebrow fade-in fade-in-1">
      <span data-cs="Hokejový klub &bull; Říčany &bull; od 1948" data-en="Ice Hockey Club &bull; Říčany &bull; since 1948">Hokejový klub &bull; Říčany &bull; od 1948</span>
    </p>
    <div style="display:flex;align-items:center;gap:40px;flex-wrap:wrap;" class="fade-in fade-in-2">
      <img src="{{ asset('assets/img/HC_Ricany_logo.png') }}" alt="HC COM-SYS Říčany logo"
           style="width:clamp(140px,18vw,220px);height:clamp(140px,18vw,220px);object-fit:contain;filter:drop-shadow(0 0 32px rgba(42,191,191,0.25));flex-shrink:0;">
      <h1>
        <span data-cs="HRÁVÁME" data-en="WE PLAY">HRÁVÁME</span>
        <em>NAPLNO.</em>
      </h1>
    </div>
    <p class="hero-sub fade-in fade-in-3" data-cs="Vítejte v rodině HC COMSYS Říčany. Hokej, který baví, vychovává a spojuje komunitu." data-en="Welcome to the HC COMSYS Říčany family. Hockey that excites, develops talent, and connects the community.">
      Vítejte v rodině HC COMSYS Říčany. Hokej, který baví, vychovává a spojuje komunitu.
    </p>
    <div class="hero-ctas fade-in fade-in-3">
      <a href="#news" class="btn btn-primary" data-cs="Nejnovější zprávy" data-en="Latest News">Nejnovější zprávy</a>
      <a href="#contact" class="btn btn-outline" data-cs="Kontaktujte nás" data-en="Contact Us">Kontaktujte nás</a>
    </div>
    <div class="hero-stats fade-in fade-in-4">
      <div>
        <div class="hero-stat-num">7</div>
        <div class="hero-stat-label" data-cs="Týmů &amp; kategorií" data-en="Teams &amp; categories">Týmů &amp; kategorií</div>
      </div>
      <div>
        <div class="hero-stat-num">75+</div>
        <div class="hero-stat-label" data-cs="Let tradice" data-en="Years of tradition">Let tradice</div>
      </div>
      <div>
        <div class="hero-stat-num">200+</div>
        <div class="hero-stat-label" data-cs="Aktivních hráčů" data-en="Active players">Aktivních hráčů</div>
      </div>
    </div>
  </div>
</section>

<!-- TICKER -->
<div class="ticker">
  <div class="ticker-inner" id="ticker">
    <span class="ticker-item">🏒 HC COMSYS Říčany <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item" data-cs="Přihlaste se k odběru novinek níže" data-en="Sign up for our newsletter below">Přihlaste se k odběru novinek níže <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">🏆 Sezona 2024/25 <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item" data-cs="Sledujte naše sociální sítě" data-en="Follow our social media">Sledujte naše sociální sítě <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">🏒 HC COMSYS Říčany <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item" data-cs="Přihlaste se k odběru novinek níže" data-en="Sign up for our newsletter below">Přihlaste se k odběru novinek níže <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item">🏆 Sezona 2024/25 <span class="ticker-sep">&middot;</span></span>
    <span class="ticker-item" data-cs="Sledujte naše sociální sítě" data-en="Follow our social media">Sledujte naše sociální sítě <span class="ticker-sep">&middot;</span></span>
  </div>
</div>

<!-- NEWS -->
<section class="section" id="news">
  <div class="container">
    <div class="section-header">
      <div>
        <div class="tag" data-cs="Novinky" data-en="News">Novinky</div>
        <h2 data-cs="Co se děje" data-en="What's happening">Co se děje</h2>
      </div>
      <a href="#" class="btn btn-outline" data-cs="Všechny články" data-en="All articles">Všechny články</a>
    </div>

    <div class="news-grid" id="news-grid">
      @forelse($articles as $article)
        <div class="news-card {{ $article->is_featured ? 'featured' : '' }}" data-id="{{ $article->id }}">
          <div class="news-date"
               data-cs="{{ $article->published_at ? $article->published_at->translatedFormat('j. F Y') : '' }} &middot; {{ $article->category_cs }}"
               data-en="{{ $article->published_at ? $article->published_at->format('F j, Y') : '' }} &middot; {{ $article->category_en }}">
            {{ $article->published_at ? $article->published_at->translatedFormat('j. F Y') : '' }} &middot; {{ $article->category_cs }}
          </div>
          <h3 data-cs="{{ $article->title_cs }}" data-en="{{ $article->title_en ?? $article->title_cs }}">
            {{ $article->title_cs }}
          </h3>
          <p data-cs="{{ $article->excerpt_cs }}" data-en="{{ $article->excerpt_en ?? $article->excerpt_cs }}">
            {{ $article->excerpt_cs }}
          </p>
          <div class="news-card-arrow"><span data-cs="Číst více" data-en="Read more">Číst více</span> &rarr;</div>
        </div>
      @empty
        <p style="color:var(--gray-light);grid-column:1/-1;">Zatím nejsou žádné články.</p>
      @endforelse
    </div>
  </div>
</section>

<!-- TEAMS -->
<section class="section teams-section" id="teams">
  <div class="container">
    <div class="tag" data-cs="Naše týmy" data-en="Our teams">Naše týmy</div>
    <h2 data-cs="Kategorie" data-en="Categories">Kategorie</h2>
    <div class="teams-grid">
      <div class="team-card">
        <div class="team-icon">⭐</div>
        <div class="team-name" data-cs="DOROST" data-en="JUNIORS">DOROST</div>
        <div class="team-desc" data-cs="U18 &middot; Krajský přebor" data-en="U18 &middot; Regional championship">U18 &middot; Krajský přebor</div>
      </div>
      <div class="team-card">
        <div class="team-icon">🌟</div>
        <div class="team-name" data-cs="STARŠÍ ŽÁCI" data-en="CADETS">STARŠÍ ŽÁCI</div>
        <div class="team-desc" data-cs="U14 &middot; Přebor středočeského kraje" data-en="U14 &middot; Central Bohemia championship">U14 &middot; Přebor Středočeského kraje</div>
      </div>
      <div class="team-card">
        <div class="team-icon">✨</div>
        <div class="team-name" data-cs="MLADŠÍ ŽÁCI" data-en="YOUNG CADETS">MLADŠÍ ŽÁCI</div>
        <div class="team-desc" data-cs="U12" data-en="U12">U12</div>
      </div>
      <div class="team-card">
        <div class="team-icon">🎯</div>
        <div class="team-name" data-cs="PŘÍPRAVKA" data-en="PREP">PŘÍPRAVKA</div>
        <div class="team-desc" data-cs="U8 &middot; U10" data-en="U8 &middot; U10">U8 &middot; U10</div>
      </div>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section class="section" id="gallery">
  <div class="container">
    <div class="section-header">
      <div>
        <div class="tag" data-cs="Fotogalerie" data-en="Photo gallery">Fotogalerie</div>
        <h2 data-cs="Momenty" data-en="Moments">Momenty</h2>
      </div>
      <a href="#" class="btn btn-outline" data-cs="Celá galerie" data-en="Full gallery">Celá galerie</a>
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
        <div class="tag" style="border-color:rgba(0,0,0,0.3);color:rgba(0,0,0,0.6);" data-cs="Newsletter" data-en="Newsletter">Newsletter</div>
        <h2 data-cs="Zůstaňte v obraze" data-en="Stay informed">Zůstaňte v obraze</h2>
        <p data-cs="Přihlaste se k odběru novinek a jako první se dozvíte o výsledcích, zápasech a akcích klubu." data-en="Subscribe to our newsletter and be the first to know about results, matches, and club events.">Přihlaste se k odběru novinek a jako první se dozvíte o výsledcích, zápasech a akcích klubu.</p>
      </div>
      <div>
        <form class="newsletter-form" id="newsletter-form" onsubmit="handleNewsletter(event)">
          <div class="form-row">
            <input type="text" class="form-input" id="nl-name" placeholder="Jméno / Name" required>
            <input type="email" class="form-input" id="nl-email" placeholder="E-mail" required>
          </div>
          <div class="checkbox-row">
            <input type="checkbox" id="nl-gdpr" required>
            <label for="nl-gdpr">Souhlasím se zpracováním osobních údajů pro účely zasílání newsletteru. Odhlásit se lze kdykoliv.</label>
          </div>
          <button type="submit" class="btn-dark" data-cs="Přihlásit se k odběru" data-en="Subscribe">Přihlásit se k odběru</button>
          <div class="form-success" id="nl-success">✓ Děkujeme! Přihlášení proběhlo úspěšně. Zkontrolujte svůj e-mail.</div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- SPONSORS -->
<section class="sponsors-section">
  <div class="container">
    <p class="sponsors-label" data-cs="Naši partneři &amp; sponzoři" data-en="Our partners &amp; sponsors">Naši partneři &amp; sponzoři</p>
    <div class="sponsors-grid">
      <div class="sponsor-item main">COM&middot;SYS</div>
      <div class="sponsor-item">PARTNER</div>
      <div class="sponsor-item">SPONZOR</div>
      <div class="sponsor-item">PARTNER</div>
      <div class="sponsor-item">SPONZOR</div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div style="font-family:var(--font-display);font-size:2rem;color:var(--teal);">HC COMSYS ŘÍČANY</div>
        <p>Hokejový klub s tradicí od roku 1948. Hrdí na naše hráče, trenéry a fanoušky.</p>
        <div class="social-links" style="margin-top:20px;">
          <a href="#" class="social-link" title="Facebook">f</a>
          <a href="#" class="social-link" title="Instagram">ig</a>
          <a href="#" class="social-link" title="YouTube">yt</a>
        </div>
      </div>
      <div>
        <div class="footer-heading">Navigace</div>
        <ul class="footer-links">
          <li><a href="#news">Novinky</a></li>
          <li><a href="#teams">Týmy</a></li>
          <li><a href="#gallery">Galerie</a></li>
          <li><a href="#contact">Kontakt</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-heading">Klub</div>
        <ul class="footer-links">
          <li><a href="#">Historie</a></li>
          <li><a href="#">Vedení klubu</a></li>
          <li><a href="#">Trenéři</a></li>
          <li><a href="#">Sponzoři</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-heading">Kontakt</div>
        <ul class="footer-links">
          <li><a href="mailto:info@hcricany.cz">info@hcricany.cz</a></li>
          <li><a href="tel:+420000000000">+420 000 000 000</a></li>
          <li><a href="#">Zimní stadion Říčany</a></li>
          <li><a href="#">GDPR &amp; Soukromí</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2025 HC COMSYS Říčany. Všechna práva vyhrazena.</p>
      <p>Navrženo s ❤️ pro hokej</p>
    </div>
  </div>
</footer>

@endsection