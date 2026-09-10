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

/* ── MENÚ MÓVIL DEL HEADER ── */
function toggleMobileMenu() {
  const panel  = document.getElementById('hdr-collapsible');
  const burger = document.getElementById('hdr-burger');
  if (!panel || !burger) return;
  const open = panel.classList.toggle('open');
  burger.setAttribute('aria-expanded', open ? 'true' : 'false');
  burger.innerHTML = open ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
}
window.toggleMobileMenu = toggleMobileMenu;

document.addEventListener('DOMContentLoaded', () => {
  const panel  = document.getElementById('hdr-collapsible');
  const burger = document.getElementById('hdr-burger');
  if (!panel || !burger) return;

  // Cerrar el panel móvil al hacer click en un enlace o botón dentro de él
  panel.addEventListener('click', e => {
    if (e.target.closest('a, button:not(#theme-btn):not(#notif-btn)')) {
      panel.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
      burger.innerHTML = '<i class="fas fa-bars"></i>';
    }
  });

  // Si la ventana vuelve a tamaño de escritorio, resetear el estado del menú
  window.addEventListener('resize', () => {
    if (window.innerWidth > 860 && panel.classList.contains('open')) {
      panel.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
      burger.innerHTML = '<i class="fas fa-bars"></i>';
    }
  });
});

/* ── MOSTRAR / OCULTAR CONTRASEÑA ── */
function togglePassword(btn) {
  const input = btn.previousElementSibling;
  if (!input) return;
  const mostrar = input.type === 'password';
  input.type = mostrar ? 'text' : 'password';
  btn.innerHTML = mostrar ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
  btn.setAttribute('aria-label', mostrar ? 'Ocultar contraseña' : 'Mostrar contraseña');
}
window.togglePassword = togglePassword;

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
