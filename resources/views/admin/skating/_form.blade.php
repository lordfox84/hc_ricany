<div class="admin-form-grid" style="max-width:520px;">

  {{-- Datum --}}
  <div class="admin-field">
    <label class="admin-label">Datum <span style="color:var(--teal)">*</span></label>
    <input type="date" name="date" class="admin-input" required
           value="{{ old('date', isset($skating) ? $skating->date->format('Y-m-d') : '') }}">
    @error('date')<span class="admin-error">{{ $message }}</span>@enderror
  </div>

  {{-- Čas od / do --}}
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="admin-field">
      <label class="admin-label">Začátek <span style="color:var(--teal)">*</span></label>
      <input type="time" name="time_from" class="admin-input" required
             value="{{ old('time_from', isset($skating) ? \Str::substr($skating->time_from,0,5) : '') }}">
      @error('time_from')<span class="admin-error">{{ $message }}</span>@enderror
    </div>
    <div class="admin-field">
      <label class="admin-label">Konec <span style="color:var(--teal)">*</span></label>
      <input type="time" name="time_to" class="admin-input" required
             value="{{ old('time_to', isset($skating) ? \Str::substr($skating->time_to,0,5) : '') }}">
      @error('time_to')<span class="admin-error">{{ $message }}</span>@enderror
    </div>
  </div>

  {{-- Poznámka --}}
  <div class="admin-field">
    <label class="admin-label">Poznámka <span style="color:var(--gray-light);font-weight:400;">(volitelná, zobrazí se u termínu)</span></label>
    <input type="text" name="note" class="admin-input" maxlength="300" placeholder="např. Zrušeno z důvodu turnaje"
           value="{{ old('note', $skating->note ?? '') }}">
    @error('note')<span class="admin-error">{{ $message }}</span>@enderror
  </div>

  {{-- Aktivní --}}
  <div class="admin-field" style="display:flex;align-items:center;gap:12px;">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" id="is_active" value="1"
           {{ old('is_active', isset($skating) ? $skating->is_active : true) ? 'checked' : '' }}
           style="width:16px;height:16px;accent-color:var(--teal);">
    <label for="is_active" class="admin-label" style="margin:0;cursor:pointer;">Aktivní (zobrazovat na webu)</label>
  </div>

</div>
