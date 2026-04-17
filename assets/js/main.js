// ─── LANGUAGE SYSTEM ───────────────────────────────────────
let currentLang = 'cs';

function setLang(lang) {
  currentLang = lang;
  document.documentElement.lang = lang;
  document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
  document.querySelector(`.lang-btn[onclick="setLang('${lang}')"]`).classList.add('active');

  document.querySelectorAll('[data-cs]').forEach(el => {
    if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
      el.placeholder = lang === 'cs' ? (el.dataset.csPlaceholder || el.placeholder) : (el.dataset.enPlaceholder || el.placeholder);
    } else {
      el.textContent = lang === 'cs' ? el.dataset.cs : el.dataset.en;
    }
  });

  const heroEm = document.querySelector('.hero h1 em');
  if(heroEm) heroEm.textContent = lang === 'cs' ? 'NAPLNO.' : 'FULLY.';

  document.title = lang === 'cs' ? 'HC COMSYS Říčany' : 'HC COMSYS Říčany — Official Website';
}

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
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 70px; left: 0; right: 0;
    background: rgba(10,10,10,0.98);
    padding: 24px;
    gap: 20px;
    border-bottom: 1px solid rgba(42,191,191,0.2);
    backdrop-filter: blur(12px);
    z-index: 99;
  `;
}

// ─── LOGIN MODAL ───────────────────────────────────────────
function openAdmin(e) {
  e.preventDefault();
  const m = document.getElementById('login-modal');
  m.style.display = 'flex';
}
function closeModal() {
  document.getElementById('login-modal').style.display = 'none';
}
document.getElementById('login-modal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

function switchTab(tab) {
  document.querySelectorAll('.modal-tab').forEach((t,i) => t.classList.toggle('active', (i===0&&tab==='login')||(i===1&&tab==='forgot')));
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  document.getElementById(`tab-${tab}`).classList.add('active');
}

function handleLogin(e) {
  e.preventDefault();
  const email = document.getElementById('login-email').value;
  const pass = document.getElementById('login-password').value;
  const err = document.getElementById('login-error');

  if (email === 'admin@hcricany.cz' && pass === 'heslo123') {
    closeModal();
    openAdmin_panel();
  } else {
    err.style.display = 'block';
    setTimeout(() => err.style.display = 'none', 3000);
  }
}

function handleForgot(e) {
  e.preventDefault();
  alert(currentLang === 'cs' ? 'Reset hesla byl odeslán na váš e-mail.' : 'Password reset has been sent to your email.');
}

// ─── ADMIN PANEL ───────────────────────────────────────────
function openAdmin_panel() {
  document.getElementById('admin-panel').style.display = 'block';
  document.body.style.overflow = 'hidden';
}
function closeAdmin() {
  document.getElementById('admin-panel').style.display = 'none';
  document.body.style.overflow = '';
}
function showAdminSection(id) {
  document.querySelectorAll('.admin-panel-section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.admin-nav-item').forEach(i => i.classList.remove('active'));
  const sec = document.getElementById(`section-${id}`);
  if (sec) sec.classList.add('active');

  document.querySelectorAll('.admin-nav-item').forEach(item => {
    if (item.getAttribute('onclick') && item.getAttribute('onclick').includes(id)) {
      item.classList.add('active');
    }
  });
}

function showToast() {
  const toast = document.getElementById('admin-toast');
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 2500);
}

function showAddUserModal() {
  document.getElementById('add-user-modal').style.display = 'flex';
}

function addUser(e) {
  e.preventDefault();
  const name = document.getElementById('new-user-name').value;
  const email = document.getElementById('new-user-email').value;
  const role = document.getElementById('new-user-role').value;

  const roleCls = { Admin: 'role-admin', Editor: 'role-editor', Viewer: 'role-viewer' };
  const tbody = document.getElementById('users-table');
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${name}</td>
    <td>${email}</td>
    <td><span class="role-badge ${roleCls[role]}">${role}</span></td>
    <td><span class="status-active">● ${currentLang==='cs'?'Aktivní':'Active'}</span></td>
    <td>${currentLang==='cs'?'Právě přidán':'Just added'}</td>
    <td>
      <button class="action-btn">${currentLang==='cs'?'Upravit':'Edit'}</button>
      <button class="action-btn danger">${currentLang==='cs'?'Zrušit':'Revoke'}</button>
    </td>`;
  tbody.appendChild(tr);

  document.getElementById('add-user-modal').style.display = 'none';
  showToast();
}

// ─── PUBLISH ARTICLE ───────────────────────────────────────
function publishArticle() {
  const titleCs = document.getElementById('art-title-cs').value.trim();
  const titleEn = document.getElementById('art-title-en').value.trim();
  const excerptCs = document.getElementById('art-excerpt-cs').value.trim();
  const excerptEn = document.getElementById('art-excerpt-en').value.trim();
  const category = document.getElementById('art-category').value;
  const dateVal = document.getElementById('art-date').value;
  const featured = document.getElementById('art-featured').checked;

  if (!titleCs) {
    alert(currentLang === 'cs' ? 'Vyplňte prosím nadpis článku (česky).' : 'Please fill in the article title (Czech).');
    return;
  }

  const csMonths = ['ledna','února','března','dubna','května','června','července','srpna','září','října','listopadu','prosince'];
  const enMonths = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  const d = dateVal ? new Date(dateVal) : new Date();
  const dateCs = `${d.getDate()}. ${csMonths[d.getMonth()]} ${d.getFullYear()} · ${category}`;
  const catEl = document.getElementById('art-category');
  const categoryEn = catEl.options[catEl.selectedIndex].dataset.en || category;
  const dateEn = `${enMonths[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()} · ${categoryEn}`;

  const card = document.createElement('div');
  card.className = 'news-card' + (featured ? ' featured' : '');
  card.style.opacity = '0';
  card.style.transform = 'translateY(20px)';
  card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  card.innerHTML = `
    <div class="news-date" data-cs="${dateCs}" data-en="${dateEn}">${currentLang === 'cs' ? dateCs : dateEn}</div>
    <h3 data-cs="${titleCs}" data-en="${titleEn || titleCs}">${currentLang === 'cs' ? titleCs : (titleEn || titleCs)}</h3>
    <p data-cs="${excerptCs}" data-en="${excerptEn || excerptCs}">${currentLang === 'cs' ? excerptCs : (excerptEn || excerptCs)}</p>
    <div class="news-card-arrow"><span data-cs="Číst více" data-en="Read more">${currentLang === 'cs' ? 'Číst více' : 'Read more'}</span> →</div>`;

  const grid = document.getElementById('news-grid');
  if (featured) {
    grid.insertBefore(card, grid.firstChild);
  } else {
    grid.appendChild(card);
  }

  requestAnimationFrame(() => { card.style.opacity = '1'; card.style.transform = 'translateY(0)'; });

  // Reset form
  ['art-title-cs','art-title-en','art-excerpt-cs','art-excerpt-en'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('art-featured').checked = false;

  showToast();
  showAdminSection('articles');
}

// ─── NEWSLETTER ────────────────────────────────────────────
function handleNewsletter(e) {
  e.preventDefault();
  const form = document.getElementById('newsletter-form');
  const success = document.getElementById('nl-success');
  form.querySelectorAll('input, button').forEach(el => el.style.display = 'none');
  success.style.display = 'block';

  const statNums = document.querySelectorAll('.admin-stat-num');
  if (statNums[1]) statNums[1].textContent = parseInt(statNums[1].textContent) + 1;
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

document.querySelectorAll('.news-card, .team-card, .gallery-item').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(20px)';
  el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  observer.observe(el);
});
