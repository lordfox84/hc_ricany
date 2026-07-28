@extends('layouts.hc')

@section('title', $team->trans('name') . ' | HC COM-SYS Říčany')

@section('content')

@include('partials._navbar')

@php $t = __('site.teams_show'); @endphp

{{-- HERO --}}
<section class="team-hero">
  <div class="container">
    <a href="{{ route('home') }}#teams" class="team-hero-back">{!! $t['back'] !!}</a>
    <div class="team-hero-inner">
      <span class="team-hero-bar" style="background:{{ $team->color }};"></span>
      <div>
        @if($team->age_group)
          <div class="team-hero-age" style="color:{{ $team->color }};">{{ $team->age_group }}</div>
        @endif
        <h1 class="team-hero-title">{{ $team->trans('name') }}</h1>
        @if($team->trans('description'))
          <p class="team-hero-desc">{{ $team->trans('description') }}</p>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- SOUPISKA --}}
<section class="section">
  <div class="container">

    @if($players->isEmpty())
      <p style="color:var(--gray-light);text-align:center;padding:60px 0;">{{ $t['no_players'] }}</p>
    @else

      @foreach($playersByPosition as $positionLabel => $group)
        <div class="roster-group">

          {{-- Hlavička pozice --}}
          @php
            $positionLabels = [
              'Brankáři'  => ['icon' => '🥅', 'text' => $t['goalkeepers']],
              'Obránci'   => ['icon' => '🛡️', 'text' => $t['defenders']],
              'Útočníci'  => ['icon' => '🏒', 'text' => $t['forwards']],
              'Ostatní'   => ['icon' => '👤', 'text' => $t['others']],
            ];
            $label = $positionLabels[$positionLabel] ?? ['icon' => '👤', 'text' => $positionLabel];
          @endphp
          <div class="roster-group-header">
            <span class="roster-group-icon">{{ $label['icon'] }}</span>
            <span class="roster-group-title">{{ $label['text'] }}</span>
            <span class="roster-group-count">{{ $group->count() }}</span>
          </div>

          {{-- Tabulka hráčů --}}
          <div class="roster-table-wrap">
            <table class="roster-table">
              <thead>
                <tr>
                  <th class="roster-th-num">#</th>
                  <th class="roster-th-photo"></th>
                  <th>{{ $t['player'] }}</th>
                  <th>{{ $t['dob'] }}</th>
                  <th>{{ $t['hand'] }}</th>
                  <th>{{ $t['height'] }}</th>
                  <th>{{ $t['weight'] }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach($group as $player)
                  <tr class="roster-row {{ $player->hasBirthdayToday() ? 'roster-row--birthday' : '' }}">

                    {{-- Číslo --}}
                    <td class="roster-td-num" style="color:{{ $team->color }};">
                      {{ $player->jersey_number !== null ? '#'.$player->jersey_number : '—' }}
                    </td>

                    {{-- Foto --}}
                    <td class="roster-td-photo">
                      @if($player->photo)
                        <img src="{{ Storage::url($player->photo) }}" alt="{{ $player->full_name }}"
                             class="roster-photo" style="border-color:{{ $team->color }}40;">
                      @else
                        <div class="roster-photo-placeholder" style="border-color:{{ $team->color }}40;">
                          {{ strtoupper(substr($player->first_name ?? $player->name ?? '?', 0, 1)) }}
                        </div>
                      @endif
                    </td>

                    {{-- Jméno --}}
                    <td class="roster-td-name">
                      <span class="roster-last-name">{{ $player->last_name }}</span>
                      <span class="roster-first-name">{{ $player->first_name }}</span>
                      @if($player->hasBirthdayToday())
                        <span class="roster-birthday-badge" title="Dnes slaví narozeniny!">🎂</span>
                      @endif
                    </td>

                    {{-- Datum narození --}}
                    <td class="roster-td-dob">
                      {{ $player->date_of_birth ? $player->date_of_birth->format('d.m.Y') : '—' }}
                    </td>

                    {{-- Ruka --}}
                    <td class="roster-td-hand">
                      @if($player->hand === 'Levák')
                        <span class="roster-hand roster-hand--l">{{ $t['hand_left'] }}</span>
                      @elseif($player->hand === 'Pravák')
                        <span class="roster-hand roster-hand--r">{{ $t['hand_right'] }}</span>
                      @else
                        <span style="color:var(--gray-light);">—</span>
                      @endif
                    </td>

                    {{-- Výška --}}
                    <td class="roster-td-height">
                      {{ $player->height_cm ? $player->height_cm.' cm' : '—' }}
                    </td>

                    {{-- Váha --}}
                    <td class="roster-td-weight">
                      {{ $player->weight_kg ? $player->weight_kg.' kg' : '—' }}
                    </td>

                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      @endforeach

    @endif
  </div>
</section>

{{-- FOOTER --}}
<footer>
  <div class="container">
    <div class="footer-bottom" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:24px;">
      <p>© {{ date('Y') }} HC COMSYS Říčany. Všechna práva vyhrazena.</p>
    </div>
  </div>
</footer>

@endsection
