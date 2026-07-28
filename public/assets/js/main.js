// ─── EMAIL VALIDATION ──────────────────────────────────────
// Databáze známých domén pro detekci překlepů (Levenshtein ≤ 2)
const HC_EMAIL_DOMAINS = [
  'gmail.com','googlemail.com',
  'seznam.cz','email.cz','centrum.cz','volny.cz','atlas.cz','post.cz','tiscali.cz','quick.cz','iol.cz',
  'outlook.com','hotmail.com','hotmail.cz','live.com','live.cz','msn.com',
  'icloud.com','me.com','mac.com',
  'yahoo.com','yahoo.co.uk',
  'o2.cz','vodafone.cz','t-mobile.cz','upc.cz',
];

function _emailLev(a, b) {
  if (Math.abs(a.length - b.length) > 3) return 99;
  const dp = Array.from({length: a.length + 1}, (_, i) => [i]);
  for (let j = 0; j <= b.length; j++) dp[0][j] = j;
  for (let i = 1; i <= a.length; i++)
    for (let j = 1; j <= b.length; j++)
      dp[i][j] = a[i-1] === b[j-1] ? dp[i-1][j-1]
                 : 1 + Math.min(dp[i-1][j], dp[i][j-1], dp[i-1][j-1]);
  return dp[a.length][b.length];
}

function _emailSuggest(val) {
  const at = val.lastIndexOf('@');
  if (at < 1) return null;
  const local = val.slice(0, at);
  const domain = val.slice(at + 1).toLowerCase();
  if (!domain || HC_EMAIL_DOMAINS.includes(domain)) return null;
  let best = null, bestD = Infinity;
  for (const k of HC_EMAIL_DOMAINS) {
    const d = _emailLev(domain, k);
    if (d < bestD) { bestD = d; best = k; }
  }
  return bestD <= 2 ? local + '@' + best : null;
}

let _emailErrIdx = 0;
function _emailErrEl(input) {
  if (!input.dataset.hcErrId) {
    const id = 'hc-ee-' + (++_emailErrIdx);
    input.dataset.hcErrId = id;
    const el = document.createElement('div');
    el.id = id;
    el.style.cssText = 'font-size:0.76rem;margin-top:5px;display:none;line-height:1.4;';
    input.after(el);
  }
  return document.getElementById(input.dataset.hcErrId);
}

function validateEmailInput(input) {
  const val = input.value.trim();
  const el = _emailErrEl(input);
  if (!val) { el.style.display = 'none'; return true; }

  const re = /^[^\s@]+@[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*\.[a-zA-Z]{2,}$/;
  if (!re.test(val)) {
    el.style.cssText = 'font-size:0.76rem;margin-top:5px;display:block;color:#f87171;line-height:1.4;';
    el.dataset.hcType = 'error';
    el.innerHTML = currentLang === 'en'
      ? '&#9888; Invalid email format. Example: <em>jan.novak@gmail.com</em>'
      : '&#9888; Neplatný formát. Příklad: <em>jan.novak@gmail.com</em>';
    return false;
  }

  const suggestion = _emailSuggest(val);
  if (suggestion) {
    el.style.cssText = 'font-size:0.76rem;margin-top:5px;display:block;color:#facc15;line-height:1.4;cursor:pointer;';
    el.dataset.hcType = 'warning';
    el.innerHTML = currentLang === 'en'
      ? '&#128161; Did you mean <strong style="text-decoration:underline dotted;">' + suggestion + '</strong>? Click to fix.'
      : '&#128161; Mysleli jste <strong style="text-decoration:underline dotted;">' + suggestion + '</strong>? Klikněte pro opravu.';
    el.onclick = function() {
      input.value = suggestion;
      el.style.display = 'none';
      input.dispatchEvent(new Event('input'));
    };
    return true;
  }

  el.style.display = 'none';
  return true;
}

// Připojit validátor na všechny email inputy po načtení stránky
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('input[type="email"]').forEach(function(input) {
    input.addEventListener('blur', function() { validateEmailInput(this); });
    input.addEventListener('input', function() {
      const el = this.dataset.hcErrId ? document.getElementById(this.dataset.hcErrId) : null;
      // Skrýt pouze chybové hlášky (červené), ne návrhy (žluté)
      if (el && el.dataset.hcType === 'error') el.style.display = 'none';
    });
  });
});

// ─── LANGUAGE (server-rendered per locale) ─────────────────
const currentLang = document.documentElement.lang === 'en' ? 'en' : 'cs';

// ─── NAVBAR SCROLL ─────────────────────────────────────────
window.addEventListener('scroll', () => {
  const nav = document.getElementById('navbar');
  if (window.scrollY > 60) {
    nav.style.height = '60px';
    nav.style.borderBottomColor = 'rgba(42,191,191,0.25)';
  } else {
    nav.style.height = '70px';
    nav.style.borderBottomColor = 'rgba(42,191,191,0.15)';
  }
});

// ─── MOBILE MENU ───────────────────────────────────────────
function toggleMobileMenu() {
  const links = document.getElementById('nav-links');
  const isOpen = links.style.display === 'flex';
  links.style.cssText = isOpen ? '' : `
    display: flex; flex-direction: column; position: fixed;
    top: 70px; left: 0; right: 0; background: rgba(10,10,10,0.98);
    padding: 24px; gap: 20px; border-bottom: 1px solid rgba(42,191,191,0.2);
    backdrop-filter: blur(12px); z-index: 99;
  `;
}

// ─── SCROLL ANIMATIONS ─────────────────────────────────────
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.team-card, .gallery-item').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(20px)';
  el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  observer.observe(el);
});
