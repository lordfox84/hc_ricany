@extends('layouts.hc')

@section('title', __('site.about.meta_title'))

@section('content')
@include('partials._navbar')

@php $t = __('site.about'); @endphp

<div class="about-page">

  {{-- ── HERO ── --}}
  <div class="about-hero">
    <div class="about-hero-bg"></div>
    <div class="container about-hero-content">
      <div class="tag">{{ $t['hero_tag'] }}</div>
      <h1>{{ $t['hero_title'] }}</h1>
      <p>{{ $t['hero_sub'] }}</p>
    </div>
  </div>

  {{-- ── STATS BAR ── --}}
  <div class="about-stats-bar">
    <div class="container">
      <div class="about-stats">
        @foreach($t['stats'] as $stat)
          <div class="about-stat">
            <div class="about-stat-num">{{ $stat['num'] }}</div>
            <div class="about-stat-label">{{ $stat['label'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ── ÚVOD ── --}}
  <section class="section">
    <div class="container about-intro">
      <div class="about-intro-text">
        <div class="tag">{{ $t['story_tag'] }}</div>
        <h2>{{ $t['story_title'] }}</h2>
        <p>{{ $t['story_p1'] }}</p>
        <p>{{ $t['story_p2'] }}</p>
      </div>
      <div class="about-intro-logo">
        <img src="{{ asset('assets/img/HC_Ricany_logo.png') }}" alt="HC COM-SYS Říčany">
      </div>
    </div>
  </section>

  {{-- ── TIMELINE ── --}}
  <section class="section about-history-section">
    <div class="container">
      <div class="tag">{{ $t['chronicle_tag'] }}</div>
      <h2>{{ $t['chronicle_title'] }}</h2>

      <div class="about-timeline">
        @foreach($t['timeline'] as $item)
          <div class="about-tl-item {{ !empty($item['highlight']) ? 'about-tl-item--highlight' : '' }}">
            <div class="about-tl-year">{{ $item['year'] }}</div>
            <div class="about-tl-content">
              <h3>{{ $item['title'] }}</h3>
              <p>{{ $item['body'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── OSOBNOSTI ── --}}
  <section class="section about-persons-section">
    <div class="container">
      <div class="tag">{{ $t['persons_tag'] }}</div>
      <h2>{{ $t['persons_title'] }}</h2>
      <div class="about-persons-grid">
        @foreach($t['persons'] as $person)
          <div class="about-person-card">
            <div class="about-person-name">{{ $person['name'] }}</div>
            <div class="about-person-years">{{ $person['years'] }}</div>
            <p>{{ $person['body'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── PARTNERSKÉ PROJEKTY ── --}}
  <section class="section about-projects-section">
    <div class="container">
      <div class="tag">{{ $t['projects_tag'] }}</div>
      <h2>{{ $t['projects_title'] }}</h2>
      <p class="about-projects-lead">{{ $t['projects_lead'] }}</p>

      @php $p = $t['projects']; @endphp
      <div class="about-projects-grid">

        <a href="https://piskejhokej.cz/" target="_blank" rel="noopener" class="about-project-card">
          @php $logo = public_path('assets/img/sponsors/piskej-hokej-logo.png'); @endphp
          <div class="about-project-logo">
            <img src="{{ asset('assets/img/sponsors/piskej-hokej-logo.png') }}?v={{ file_exists($logo) ? filemtime($logo) : 1 }}" alt="Pískej hokej">
          </div>
          <div class="about-project-info">
            <div class="about-project-name">{{ $p[0]['name'] }}</div>
            <p>{{ $p[0]['body'] }}</p>
          </div>
        </a>

        <a href="https://www.pojdhrathokej.cz/" target="_blank" rel="noopener" class="about-project-card">
          @php $logo2 = public_path('assets/img/sponsors/pojd-hrat-hokej_logo.png'); @endphp
          <div class="about-project-logo">
            <img src="{{ asset('assets/img/sponsors/pojd-hrat-hokej_logo.png') }}?v={{ file_exists($logo2) ? filemtime($logo2) : 1 }}" alt="Pojď hrát hokej">
          </div>
          <div class="about-project-info">
            <div class="about-project-name">{{ $p[1]['name'] }}</div>
            <p>{{ $p[1]['body'] }}</p>
          </div>
        </a>

        <a href="https://www.golmansketreninky.cz/" target="_blank" rel="noopener" class="about-project-card">
          @php $logo3 = public_path('assets/img/sponsors/AGA_logo_v1.png'); @endphp
          <div class="about-project-logo">
            <img src="{{ asset('assets/img/sponsors/AGA_logo_v1.png') }}?v={{ file_exists($logo3) ? filemtime($logo3) : 1 }}" alt="Altrichter Goalie Academy">
          </div>
          <div class="about-project-info">
            <div class="about-project-name">{{ $p[2]['name'] }}</div>
            <p>{{ $p[2]['body'] }}</p>
          </div>
        </a>

        <div class="about-project-card about-project-card--plain">
          <div class="about-project-logo about-project-logo--icon">🏒</div>
          <div class="about-project-info">
            <div class="about-project-name">{{ $p[3]['name'] }}</div>
            <p>{{ $p[3]['body'] }}</p>
          </div>
        </div>

        <div class="about-project-card about-project-card--plain">
          <div class="about-project-logo about-project-logo--icon">🎓</div>
          <div class="about-project-info">
            <div class="about-project-name">{{ $p[4]['name'] }}</div>
            <p>{{ $p[4]['body'] }}</p>
          </div>
        </div>

        <a href="https://www.stridasport.cz/cs/module/ups_storesgroups/store?id_store=9" target="_blank" rel="noopener" class="about-project-card">
          @php $logo4 = public_path('assets/img/sponsors/strida-sport-logo.png'); @endphp
          <div class="about-project-logo">
            <img src="{{ asset('assets/img/sponsors/strida-sport-logo.png') }}?v={{ file_exists($logo4) ? filemtime($logo4) : 1 }}" alt="Střída Sport">
          </div>
          <div class="about-project-info">
            <div class="about-project-name">{{ $p[5]['name'] }}</div>
            <p>{{ $p[5]['body'] }}</p>
          </div>
        </a>

      </div>
    </div>
  </section>

  {{-- ── CTA ── --}}
  <div style="background:var(--gray);padding:60px 0;text-align:center;border-top:1px solid rgba(42,191,191,0.1);">
    <div class="container">
      <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);margin-bottom:16px;">{{ $t['cta_title'] }}</h2>
      <p style="color:var(--gray-light);margin-bottom:28px;">{{ $t['cta_sub'] }}</p>
      <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('home') }}#contact" class="btn btn-primary">{{ $t['cta_contact'] }}</a>
        <a href="{{ route('camps.index') }}" class="btn btn-outline">{{ $t['cta_camps'] }}</a>
      </div>
    </div>
  </div>

</div>
@endsection
