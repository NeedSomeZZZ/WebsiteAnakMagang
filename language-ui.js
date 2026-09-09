/* language-ui.js — Mobile Drawer + Keyboard Shortcuts
   Fitur i18n / penggantian bahasa dihapus.
   Bahasa default: Indonesia (semua teks langsung ditulis di HTML).
*/
(function () {

  // ── Mobile Sidebar Drawer ─────────────────────────────────────────────
  function setupMobileDrawer() {
    const sidebar = document.querySelector('aside');
    const header  = document.querySelector('header');
    if (!sidebar || !header) return;

    if (!header.querySelector('[data-mobile-menu-btn]')) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.dataset.mobileMenuBtn = 'true';
      btn.className = 'md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 mr-2 focus:outline-none';
      btn.innerHTML = '<span class="material-symbols-outlined">menu</span>';
      btn.onclick = () => toggleMobileSidebar();
      header.prepend(btn);
    }
  }

  function toggleMobileSidebar() {
    const sidebar = document.querySelector('aside');
    if (!sidebar) return;
    const isHidden = sidebar.classList.contains('hidden');
    if (isHidden) {
      sidebar.classList.remove('hidden');
      sidebar.classList.add('flex', 'fixed', 'inset-0', 'z-50', 'w-64', 'bg-white', 'shadow-2xl');
      let overlay = document.getElementById('mobile-drawer-overlay');
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'mobile-drawer-overlay';
        overlay.className = 'fixed inset-0 bg-slate-900/50 z-40 md:hidden';
        overlay.onclick = () => toggleMobileSidebar();
        document.body.appendChild(overlay);
      }
      overlay.classList.remove('hidden');
    } else {
      sidebar.classList.add('hidden');
      sidebar.classList.remove('flex', 'fixed', 'inset-0', 'z-50', 'w-64', 'shadow-2xl');
      const overlay = document.getElementById('mobile-drawer-overlay');
      if (overlay) overlay.classList.add('hidden');
    }
  }

  // ── Keyboard Shortcuts ────────────────────────────────────────────────
  function setupKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
      // '/' or Ctrl+K → focus search
      if ((e.key === '/' || (e.ctrlKey && e.key === 'k')) &&
          !['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
        e.preventDefault();
        const searchInput = document.querySelector(
          'input[type="search"], input[placeholder*="Search"], input[placeholder*="Cari"]'
        );
        if (searchInput) searchInput.focus();
      }
      // Escape → close modal or mobile drawer
      if (e.key === 'Escape') {
        const modal = document.getElementById('modal');
        if (modal && !modal.classList.contains('hidden')) {
          modal.classList.add('hidden');
        }
        const overlay = document.getElementById('mobile-drawer-overlay');
        if (overlay && !overlay.classList.contains('hidden')) {
          toggleMobileSidebar();
        }
      }
    });
  }

  // ── Init ──────────────────────────────────────────────────────────────
  function start() {
    setupMobileDrawer();
    setupKeyboardShortcuts();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }

  window.toggleMobileSidebar = toggleMobileSidebar;
  // stub agar tidak error jika ada kode yang masih memanggil refreshLanguage
  window.refreshLanguage = function () {};
})();
