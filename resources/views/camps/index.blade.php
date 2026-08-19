@extends('layouts.hc')
@section('title', ($typeLabel ?? 'Kempy') . ' | HC COM-SYS Říčany')

@section('content')

<!-- NAVBAR -->
@include('partials._navbar')

<!-- HERO -->
<section style="padding:120px 0 60px;background:var(--gray);border-bottom:1px solid rgba(42,191,191,0.15);">
  <div class="container">
    <div style="margin-bottom:12px;">
      <a href="{{ route('home') }}" style="color:var(--gray-light);font-size:0.85rem;">← Zpět na hlavní stránku</a>
    </div>
    <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
      <a href="{{ route('camps.index') }}"
         class="btn btn-outline" style="font-size:0.8rem;padding:6px 16px;{{ !request('typ') ? 'border-color:var(--teal);color:var(--teal);' : '' }}">
        Všechny kempy
      </a>
      <a href="{{ route('camps.index', ['typ' => 'letni']) }}"
         class="btn btn-outline" style="font-size:0.8rem;padding:6px 16px;{{ request('typ') === 'letni' ? 'border-color:var(--teal);color:var(--teal);' : '' }}">
        Letní kempy
      </a>
      <a href="{{ route('camps.index', ['typ' => 'skills']) }}"
         class="btn btn-outline" style="font-size:0.8rem;padding:6px 16px;{{ request('typ') === 'skills' ? 'border-color:var(--teal);color:var(--teal);' : '' }}">
        Skills kempy
      </a>
    </div>
    <div class="tag">Kempy &amp; tábory</div>
    <h1 style="font-family:var(--font-display);font-size:clamp(2.5rem,6vw,4rem);line-height:1;margin-top:12px;">
      {{ $typeLabel ?? 'Hokejové kempy' }}
    </h1>
    <p style="color:var(--gray-light);margin-top:16px;max-width:560px;">
      Přihlaste své děti na naše hokejové kempy a tábory. Profesionální trenéři, skvělá parta a nezapomenutelné zážitky na ledě.
    </p>
  </div>
</section>

<!-- CAMPS LIST -->
<section class="section">
  <div class="container">
    @forelse($camps as $camp)
      <div id="kemp-{{ $camp->id }}" style="background:var(--gray);border:1px solid rgba(42,191,191,0.1);border-left:4px solid var(--teal);border-radius:4px;padding:0;margin-bottom:40px;overflow:hidden;">

        {{-- HORNÍ ČÁST: info + plakát --}}
        <div style="display:grid;grid-template-columns:{{ $camp->poster ? '1fr 280px' : '1fr' }};gap:0;align-items:stretch;">
          <div style="padding:32px;">
            @if($camp->date_from)
              <div style="font-size:0.82rem;color:var(--teal);font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:8px;">
                📅 {{ $camp->date_from->format('d.m.Y') }}{{ $camp->date_to ? ' – '.$camp->date_to->format('d.m.Y') : '' }}
              </div>
            @endif

            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
              <h2 style="font-family:var(--font-display);font-size:clamp(1.6rem,3vw,2.2rem);line-height:1.1;margin:0;">
                {{ $camp->title_cs }}
              </h2>
              <span style="font-size:0.72rem;padding:3px 10px;border-radius:3px;font-weight:600;
                background:{{ $camp->type === 'skills' ? '#1a1a3a' : '#1a3a2a' }};
                color:{{ $camp->type === 'skills' ? '#818cf8' : '#4ade80' }};">
                {{ $camp->type === 'skills' ? 'Skills kemp' : 'Letní kemp' }}
              </span>
            </div>

            @if($camp->excerpt_cs)
              <p style="color:var(--gray-light);font-size:0.95rem;margin-bottom:16px;line-height:1.7;">{{ $camp->excerpt_cs }}</p>
            @endif

            @if($camp->body_cs)
              <div style="color:var(--white);font-size:0.88rem;line-height:1.8;white-space:pre-line;margin-bottom:20px;">{{ $camp->body_cs }}</div>
            @endif

            @if($camp->capacity)
              @php $left = $camp->spotsLeft(); @endphp
              <div style="font-size:0.82rem;margin-bottom:16px;color:{{ $left === 0 ? '#f87171' : ($left <= 3 ? '#facc15' : '#4ade80') }};">
                @if($left === 0)
                  ⛔ Kemp je obsazen
                @elseif($left !== null)
                  ✅ Zbývá {{ $left }} z {{ $camp->capacity }} míst
                @endif
              </div>
            @endif

            {{-- TLAČÍTKO REGISTRACE --}}
            @if($camp->registration_open && $camp->spotsLeft() !== 0)
              <button onclick="toggleForm('form-{{ $camp->id }}')"
                      class="btn btn-primary" style="font-size:0.9rem;">
                📝 Registrace na kemp
              </button>
            @elseif(!$camp->registration_open)
              <span class="btn btn-outline" style="opacity:0.4;cursor:default;font-size:0.9rem;">Registrace není otevřena</span>
            @endif
          </div>

          @if($camp->poster)
            <div style="background:url('{{ asset('storage/' . $camp->poster) }}') center/cover no-repeat;min-height:280px;"></div>
          @endif
        </div>

        {{-- REGISTRAČNÍ FORMULÁŘ (skrytý) --}}
        @if($camp->registration_open && $camp->spotsLeft() !== 0)
          <div id="form-{{ $camp->id }}" style="display:none;border-top:1px solid rgba(42,191,191,0.15);padding:32px;background:rgba(0,0,0,0.3);">

            @if(session('reg_success') && session('reg_camp_id') == $camp->id)
              <div style="background:#1a3a1a;border:1px solid #4ade80;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#4ade80;">
                ✅ {{ session('reg_success') }}
              </div>
            @endif
            @if(session('reg_error') && session('reg_camp_id') == $camp->id)
              <div style="background:#3a1a1a;border:1px solid #f87171;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#f87171;">
                {{ session('reg_error') }}
              </div>
            @endif
            @if($errors->any() && old('_camp_id') == $camp->id)
              <div style="background:#3a1a1a;border:1px solid #e55;border-radius:8px;padding:16px;margin-bottom:20px;color:#f88;">
                @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
              </div>
            @endif

            <div style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:24px;color:var(--teal);">
              Registrační formulář
            </div>

            <form method="POST" action="{{ route('camps.register', $camp) }}">
              @csrf
              <input type="hidden" name="_camp_id" value="{{ $camp->id }}">
              <input type="hidden" name="locale" class="locale-field" value="cs">

              {{-- SEKCE: RODIČ --}}
              <div style="font-size:0.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--teal);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid rgba(42,191,191,0.2);">
                Rodič / zákonný zástupce
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div class="form-group">
                  <label class="form-label">Jméno *</label>
                  <input type="text" name="parent_name" class="form-control" required
                         value="{{ old('parent_name') }}" placeholder="Jan">
                </div>
                <div class="form-group">
                  <label class="form-label">Příjmení *</label>
                  <input type="text" name="parent_last_name" class="form-control" required
                         value="{{ old('parent_last_name') }}" placeholder="Novák">
                </div>
                <div class="form-group">
                  <label class="form-label">E-mail *</label>
                  <input type="email" name="parent_email" id="parent-email-{{ $camp->id }}"
                         class="form-control" required
                         value="{{ old('parent_email') }}" placeholder="jan@email.cz">
                </div>
                <div class="form-group">
                  <label class="form-label">Telefon</label>
                  <input type="text" name="parent_phone" class="form-control"
                         value="{{ old('parent_phone') }}" placeholder="+420 777 000 000">
                </div>
              </div>

              {{-- SEKCE: HRÁČ --}}
              <div style="font-size:0.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--teal);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid rgba(42,191,191,0.2);">
                Hráč
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div class="form-group">
                  <label class="form-label">Jméno hráče *</label>
                  <input type="text" name="child_name" class="form-control" required
                         value="{{ old('child_name') }}" placeholder="Tomáš">
                </div>
                <div class="form-group">
                  <label class="form-label">Příjmení hráče *</label>
                  <input type="text" name="child_last_name" class="form-control" required
                         value="{{ old('child_last_name') }}" placeholder="Novák">
                </div>
                <div class="form-group">
                  <label class="form-label">Datum narození *</label>
                  <input type="date" name="child_date_of_birth" class="form-control" required
                         value="{{ old('child_date_of_birth') }}">
                </div>
                <div class="form-group">
                  <label class="form-label">Mateřský klub</label>
                  <input type="text" name="club" class="form-control"
                         value="{{ old('club') }}" placeholder="HC Říčany, bez klubu…">
                </div>
                <div class="form-group">
                  <label class="form-label">Hráčský post</label>
                  <select name="position" class="form-control">
                    <option value="">– vyberte –</option>
                    <option value="útočník" {{ old('position') === 'útočník' ? 'selected' : '' }}>Útočník</option>
                    <option value="obránce" {{ old('position') === 'obránce' ? 'selected' : '' }}>Obránce</option>
                    <option value="brankář" {{ old('position') === 'brankář' ? 'selected' : '' }}>Brankář</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Velikost dresu</label>
                  <div style="display:flex;gap:8px;align-items:center;">
                    <select name="jersey_size" class="form-control"
                            onchange="toggleJerseyCustom(this, 'jc-{{ $camp->id }}')">
                      <option value="">– vyberte –</option>
                      @foreach(['XS','S','M','L','XL','XXL','jiná'] as $sz)
                        <option value="{{ $sz }}" {{ old('jersey_size') === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                      @endforeach
                    </select>
                    <input type="text" name="jersey_size_custom" id="jc-{{ $camp->id }}"
                           class="form-control"
                           style="display:{{ old('jersey_size') === 'jiná' ? 'block' : 'none' }};max-width:130px;"
                           value="{{ old('jersey_size_custom') }}" placeholder="Specifikujte…">
                  </div>
                </div>
              </div>

              {{-- SEKCE: BYDLIŠTĚ --}}
              <div style="font-size:0.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--teal);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid rgba(42,191,191,0.2);">
                Bydliště
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                <div class="form-group" style="grid-column:span 2;">
                  <label class="form-label">Ulice a číslo popisné</label>
                  <input type="text" name="street" class="form-control"
                         value="{{ old('street') }}" placeholder="Nádražní 12">
                </div>
                <div class="form-group">
                  <label class="form-label">Město</label>
                  <input type="text" name="city" class="form-control"
                         value="{{ old('city') }}" placeholder="Praha">
                </div>
                <div class="form-group">
                  <label class="form-label">PSČ</label>
                  <input type="text" name="zip" class="form-control"
                         value="{{ old('zip') }}" placeholder="251 01">
                </div>
                <div class="form-group">
                  <label class="form-label">Země</label>
                  <input type="text" name="country" class="form-control"
                         value="{{ old('country', 'Česká republika') }}" placeholder="Česká republika">
                </div>
              </div>

              {{-- POZNÁMKA --}}
              <div class="form-group" style="margin-bottom:24px;">
                <label class="form-label">Poznámka (alergie, zdravotní omezení…)</label>
                <input type="text" name="note" class="form-control"
                       value="{{ old('note') }}" placeholder="Volitelné">
              </div>

              {{-- SEKCE: VARIANTA --}}
              @if($camp->variants && count($camp->variants) > 0)
                <div style="border-top:1px solid rgba(255,255,255,0.08);padding-top:20px;margin-bottom:24px;">
                  <div style="font-size:0.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--teal);margin-bottom:12px;">
                    Varianta kempu *
                  </div>
                  @foreach($camp->variants as $variant)
                    <label style="display:flex;gap:10px;align-items:center;cursor:pointer;font-size:0.9rem;line-height:1.5;margin-bottom:10px;padding:10px 14px;border:1px solid rgba(255,255,255,0.08);border-radius:6px;transition:border-color .15s;"
                           onmouseover="this.style.borderColor='rgba(42,191,191,0.4)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'">
                      <input type="radio" name="variant" value="{{ $variant }}" required
                             style="accent-color:var(--teal);flex-shrink:0;"
                             {{ old('variant') === $variant ? 'checked' : '' }}>
                      <span>{{ $variant }}</span>
                    </label>
                  @endforeach
                </div>
              @endif

              {{-- GDPR --}}
              <div style="border-top:1px solid rgba(255,255,255,0.08);padding-top:20px;">
                <div class="form-group">
                  <label style="display:flex;gap:12px;align-items:flex-start;cursor:pointer;font-size:0.85rem;line-height:1.5;">
                    <input type="checkbox" name="gdpr_consent" value="1" required
                           style="accent-color:var(--teal);margin-top:3px;flex-shrink:0;">
                    <span>
                      <strong>Souhlas se zpracováním osobních údajů *</strong><br>
                      Souhlasím se zpracováním osobních údajů (jméno, e-mail, telefon, datum narození) za účelem vyřízení registrace na kemp HC COM-SYS Říčany.
                      Údaje budou uchovávány po dobu nezbytně nutnou a nebudou předány třetím stranám.
                    </span>
                  </label>
                </div>
                <div class="form-group" style="margin-top:12px;">
                  <label style="display:flex;gap:12px;align-items:flex-start;cursor:pointer;font-size:0.85rem;line-height:1.5;">
                    <input type="checkbox" name="marketing_consent" value="1"
                           style="accent-color:var(--teal);margin-top:3px;flex-shrink:0;">
                    <span>
                      <strong>Souhlas s marketingovou komunikací</strong> <em style="color:var(--gray-light);">(volitelné)</em><br>
                      Souhlasím s tím, aby mě HC COM-SYS Říčany informoval o budoucích kempech a akcích klubu na zadaný e-mail.
                      Souhlas mohu kdykoli odvolat.
                    </span>
                  </label>
                </div>
              </div>

              <div style="margin-top:24px;display:flex;gap:12px;align-items:center;">
                <button type="submit" class="btn btn-primary">Odeslat registraci</button>
                <button type="button" onclick="toggleForm('form-{{ $camp->id }}')"
                        class="btn btn-outline">Zrušit</button>
              </div>
            </form>
          </div>
        @endif

      </div>
    @empty
      <div style="text-align:center;padding:80px 0;color:var(--gray-light);">
        <div style="font-size:3rem;margin-bottom:16px;">🏕️</div>
        <p>Momentálně nejsou vypsány žádné kempy. Sledujte naše novinky!</p>
      </div>
    @endforelse
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="footer-bottom" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:24px;">
      <p>© 2025 HC COMSYS Říčany. Všechna práva vyhrazena.</p>
    </div>
  </div>
</footer>

<script>
function toggleForm(id) {
  var el = document.getElementById(id);
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
  if (el.style.display === 'block') {
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

function toggleJerseyCustom(select, customId) {
  var custom = document.getElementById(customId);
  if (!custom) return;
  custom.style.display = select.value === 'jiná' ? 'block' : 'none';
  if (select.value !== 'jiná') custom.value = '';
}

// Po submitu automaticky otevřít formulář pokud jsou chyby
@if($errors->any() && old('_camp_id'))
  document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById('form-{{ old('_camp_id') }}');
    if (el) { el.style.display = 'block'; el.scrollIntoView({ behavior: 'smooth' }); }
  });
@endif
</script>
@endsection
