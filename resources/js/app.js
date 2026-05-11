import './bootstrap';
/* ══════════════════════════════════════════════
   UTILS COMPARTIDOS
══════════════════════════════════════════════ */

/* ── TEMA CLARO / OSCURO ── */
function setThemeButtonIcon() {
  const btn = document.getElementById('theme-btn');
  if (!btn) return;
  const theme = document.documentElement.getAttribute('data-theme');
  btn.innerHTML = theme === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
}

function toggleTheme() {
  const html = document.documentElement;
  const dark = html.getAttribute('data-theme') === 'dark';
  html.setAttribute('data-theme', dark ? 'light' : 'dark');
  localStorage.setItem('etno_theme', dark ? 'light' : 'dark');
  setThemeButtonIcon();
}

window.toggleTheme = toggleTheme;

/* Persistir tema entre páginas */
(function () {
  const saved = localStorage.getItem('etno_theme');
  if (saved) {
    document.documentElement.setAttribute('data-theme', saved);
  } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.setAttribute('data-theme', 'dark');
  }
})();

/* ── TOAST ── */
function toast(msg, tipo = '') {
  const el = document.getElementById('toast');
  if (!el) return;
  const ic = { '': 'fa-check-circle', err: 'fa-times-circle', warn: 'fa-exclamation-triangle' };
  el.className = 'toast' + (tipo ? ' ' + tipo : '');
  el.innerHTML = `<i class="fas ${ic[tipo] || ic['']}"></i><span>${msg}</span>`;
  el.classList.add('show');
  setTimeout(() => el.classList.remove('show'), 3200);
}

/* ── BUSCADOR ── */
// Removido, ahora es un form simple

/* ── MODALES ── */
function abrirModal(id) { document.getElementById(id)?.classList.add('open'); }
function cerrarModal(id) { document.getElementById(id)?.classList.remove('open'); }

window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal-ov').forEach(o =>
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); })
  );


});
