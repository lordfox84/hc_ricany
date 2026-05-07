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
  if (heroEm) heroEm.textContent = lang === 'cs' ? 'NAPLNO.' : 'FULLY.';
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
    display: flex; flex-direction: column; position: fixed;
    top: 70px; left: 0; right: 0; background: rgba(10,10,10,0.98);
    padding: 24px; gap: 20px; border-bottom: 1px solid rgba(42,191,191,0.2);
    backdrop-filter: blur(12px); z-index: 99;
  `;
}

// ─── LOGIN MODAL ───────────────────────────────────────────
function openAdmin(e) {
  e.preventDefault();
  document.getElementById('login-modal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('login-modal').style.display = 'none';
}
document.getElementById('login-modal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

function switchTab(tab) {
  document.querySelectorAll('.modal-tab').forEach((t, i) =>
    t.classList.toggle('active', (i === 0 && tab === 'login') || (i === 1 && tab === 'forgot')));
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  document.getElementById(`tab-${tab}`).classList.add('active');
}

function handleLogin(e) {
  e.preventDefault();
  const emailInput = document.getElementById('login-email');
  emailInput.value = emailInput.value.trim();
  const email = emailInput.value;
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
    if (item.getAttribute('onclick')?.includes(id)) item.classList.add('active');
  });
}

function showToast(msg) {
  const toast = document.getElementById('admin-toast');
  if (msg) toast.textContent = msg;
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
    <td>${name}</td><td>${email}</td>
    <td><span class="role-badge ${roleCls[role]}">${role}</span></td>
    <td><span class="status-active">● ${currentLang === 'cs' ? 'Aktivní' : 'Active'}</span></td>
    <td>${currentLang === 'cs' ? 'Právě přidán' : 'Just added'}</td>
    <td>
      <button class="action-btn">${currentLang === 'cs' ? 'Upravit' : 'Edit'}</button>
      <button class="action-btn danger">${currentLang === 'cs' ? 'Zrušit' : 'Revoke'}</button>
    </td>`;
  tbody.appendChild(tr);
  document.getElementById('add-user-modal').style.display = 'none';
  showToast();
}

// ─── ARTICLES STORE (localStorage) ─────────────────────────
const STORAGE_KEY = 'hc_ricany_articles';

const defaultArticles = [
  {
    id: 1, featured: true,
    titleCs: 'Vítězné tažení pokračuje — tři výhry v řadě!',
    titleEn: 'Winning streak continues — three wins in a row!',
    excerptCs: 'Naši muži podali skvělý výkon v posledním kole. Výsledek 5:2 potvrdil dobrou formu celého týmu a přiblížil nás play-off. Gratulujeme klukům za skvělý výkon.',
    excerptEn: 'Our men put in a brilliant performance in the last round. The 5:2 result confirmed the good form of the whole team and brought us closer to the playoffs.',
    category: 'Zápas', categoryEn: 'Match',
    dateCs: '15. dubna 2025 · Zápas', dateEn: 'April 15, 2025 · Match', dateShort: '15.4.2025'
  },
  {
    id: 2, featured: false,
    titleCs: 'Dorost slaví postup do semifinále',
    titleEn: 'Juniors celebrate semi-final promotion',
    excerptCs: 'Naše dorostenecká kategorie se probojovala do semifinále krajského přeboru. Výborná zpráva pro celý klub!',
    excerptEn: 'Our junior category has fought through to the semi-final of the regional championship. Excellent news for the whole club!',
    category: 'Mládež', categoryEn: 'Youth',
    dateCs: '10. dubna 2025 · Mládež', dateEn: 'April 10, 2025 · Youth', dateShort: '10.4.2025'
  },
  {
    id: 3, featured: false,
    titleCs: 'Otevírací dny přihlášek na novou sezonu',
    titleEn: 'Registration open days for new season',
    excerptCs: 'Přihlášky na sezonu 2025/26 jsou otevřeny pro všechny věkové kategorie od 5 let.',
    excerptEn: 'Registrations for the 2025/26 season are open for all age categories from 5 years old.',
    category: 'Klub', categoryEn: 'Club',
    dateCs: '5. dubna 2025 · Klub', dateEn: 'April 5, 2025 · Club', dateShort: '5.4.2025'
  },
  {
    id: 4, featured: false,
    titleCs: 'Nový kondicionér posiluje tým',
    titleEn: 'New fitness coach strengthens the team',
    excerptCs: 'Do realizačního týmu přichází zkušený kondicionér Jan Novák, který pracoval s prvoligovými celky.',
    excerptEn: 'Experienced fitness coach Jan Novák joins the coaching staff, having worked with top-flight clubs.',
    category: 'Trénink', categoryEn: 'Training',
    dateCs: '1. dubna 2025 · Trénink', dateEn: 'April 1, 2025 · Training', dateShort: '1.4.2025'
  }
];

let articleIdCounter = 5;

function getArticles() {
  try {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) return JSON.parse(stored);
  } catch(e) {}
  return JSON.parse(JSON.stringify(defaultArticles));
}

function saveArticles(articles) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(articles));
  articleIdCounter = articles.reduce((m, a) => Math.max(m, a.id), 4) + 1;
}

function renderNewsGrid(articles) {
  const grid = document.getElementById('news-grid');
  grid.innerHTML = '';
  articles.forEach(art => {
    const card = document.createElement('div');
    card.className = 'news-card' + (art.featured ? ' featured' : '');
    card.dataset.id = art.id;
    card.innerHTML = `
      <div class="news-date" data-cs="${art.dateCs}" data-en="${art.dateEn}">${currentLang === 'cs' ? art.dateCs : art.dateEn}</div>
      <h3 data-cs="${art.titleCs}" data-en="${art.titleEn || art.titleCs}">${currentLang === 'cs' ? art.titleCs : (art.titleEn || art.titleCs)}</h3>
      <p data-cs="${art.excerptCs}" data-en="${art.excerptEn || art.excerptCs}">${currentLang === 'cs' ? art.excerptCs : (art.excerptEn || art.excerptCs)}</p>
      <div class="news-card-arrow"><span data-cs="Číst více" data-en="Read more">${currentLang === 'cs' ? 'Číst více' : 'Read more'}</span> →</div>`;
    grid.appendChild(card);
    observer.observe(card);
  });
}

function renderAdminTable(articles) {
  const tbody = document.getElementById('admin-articles-table');
  tbody.innerHTML = '';
  articles.forEach(art => {
    const tr = document.createElement('tr');
    tr.dataset.id = art.id;
    tr.innerHTML = `
      <td>${art.titleCs}</td>
      <td><span class="role-badge role-admin">${art.category}</span></td>
      <td>admin</td>
      <td>${art.dateShort}</td>
      <td><span class="status-active">● ${currentLang === 'cs' ? 'Publikováno' : 'Published'}</span></td>
      <td>
        <button class="action-btn" onclick="editArticle(${art.id})">${currentLang === 'cs' ? 'Upravit' : 'Edit'}</button>
        <button class="action-btn danger" onclick="deleteArticle(${art.id})">${currentLang === 'cs' ? 'Smazat' : 'Delete'}</button>
      </td>`;
    tbody.appendChild(tr);
  });
}

// ─── DELETE ARTICLE ────────────────────────────────────────
function deleteArticle(id) {
  if (!confirm(currentLang === 'cs' ? 'Opravdu smazat tento článek?' : 'Really delete this article?')) return;
  const articles = getArticles().filter(a => a.id !== id);
  saveArticles(articles);
  renderNewsGrid(articles);
  renderAdminTable(articles);
  showToast(currentLang === 'cs' ? '✓ Článek byl smazán' : '✓ Article deleted');
}

// ─── EDIT ARTICLE ──────────────────────────────────────────
function editArticle(id) {
  const art = getArticles().find(a => a.id === id);
  if (!art) return;

  document.getElementById('art-title-cs').value   = art.titleCs;
  document.getElementById('art-title-en').value   = art.titleEn || '';
  document.getElementById('art-excerpt-cs').value = art.excerptCs;
  document.getElementById('art-excerpt-en').value = art.excerptEn || '';
  document.getElementById('art-featured').checked = art.featured;

  const catSelect = document.getElementById('art-category');
  catSelect.value = art.category;

  if (art.dateShort) {
    const parts = art.dateShort.split('.');
    if (parts.length === 3) {
      document.getElementById('art-date').value =
        `${parts[2].trim()}-${parts[1].trim().padStart(2,'0')}-${parts[0].trim().padStart(2,'0')}`;
    }
  }

  document.getElementById('art-editing-id').value = id;
  document.getElementById('art-publish-btn').textContent = currentLang === 'cs' ? 'Uložit změny' : 'Save changes';
  document.getElementById('art-cancel-btn').style.display = 'block';
  showAdminSection('new-article');
}

function cancelEdit() {
  document.getElementById('art-editing-id').value = '';
  document.getElementById('art-publish-btn').textContent = currentLang === 'cs' ? 'Publikovat článek' : 'Publish article';
  document.getElementById('art-cancel-btn').style.display = 'none';
  ['art-title-cs','art-title-en','art-excerpt-cs','art-excerpt-en'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('art-featured').checked = false;
}

// ─── PUBLISH / UPDATE ARTICLE ──────────────────────────────
function publishArticle() {
  const titleCs   = document.getElementById('art-title-cs').value.trim();
  const titleEn   = document.getElementById('art-title-en').value.trim();
  const excerptCs = document.getElementById('art-excerpt-cs').value.trim();
  const excerptEn = document.getElementById('art-excerpt-en').value.trim();
  const catEl     = document.getElementById('art-category');
  const category  = catEl.value;
  const categoryEn = catEl.options[catEl.selectedIndex].dataset.en || category;
  const dateVal   = document.getElementById('art-date').value;
  const featured  = document.getElementById('art-featured').checked;
  const editingId = parseInt(document.getElementById('art-editing-id').value) || null;

  if (!titleCs) {
    alert(currentLang === 'cs' ? 'Vyplňte prosím nadpis článku (česky).' : 'Please fill in the article title (Czech).');
    return;
  }

  const csMonths = ['ledna','února','března','dubna','května','června','července','srpna','září','října','listopadu','prosince'];
  const enMonths = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  const d = dateVal ? new Date(dateVal) : new Date();
  const dateCs    = `${d.getDate()}. ${csMonths[d.getMonth()]} ${d.getFullYear()} · ${category}`;
  const dateEn    = `${enMonths[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()} · ${categoryEn}`;
  const dateShort = `${d.getDate()}.${d.getMonth()+1}.${d.getFullYear()}`;

  let articles = getArticles();

  if (editingId) {
    const idx = articles.findIndex(a => a.id === editingId);
    if (idx !== -1) {
      if (featured) articles.forEach(a => a.featured = false);
      articles[idx] = { ...articles[idx], titleCs, titleEn, excerptCs, excerptEn, category, categoryEn, dateCs, dateEn, dateShort, featured };
    }
  } else {
    const newId = articleIdCounter++;
    if (featured) articles.forEach(a => a.featured = false);
    const newArt = { id: newId, titleCs, titleEn, excerptCs, excerptEn, category, categoryEn, dateCs, dateEn, dateShort, featured };
    articles.unshift(newArt);
  }

  saveArticles(articles);
  renderNewsGrid(articles);
  renderAdminTable(articles);
  cancelEdit();
  showToast(editingId
    ? (currentLang === 'cs' ? '✓ Článek byl uložen' : '✓ Article saved')
    : (currentLang === 'cs' ? '✓ Článek byl publikován' : '✓ Article published'));
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

document.querySelectorAll('.team-card, .gallery-item').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(20px)';
  el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
  observer.observe(el);
});

// ─── INIT ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const articles = getArticles();
  saveArticles(articles);
  renderNewsGrid(articles);
  renderAdminTable(articles);
});
