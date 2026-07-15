/* Connects the shared language dictionary to static and dynamically rendered UI. */
(function () {
  function indexDictionary() {
    const index = new Map();
    Object.entries(I18n.dict).forEach(([key, value]) => { index.set(value.en, key); index.set(value.id, key); });
    return index;
  }
  const dictionaryIndex = () => window.I18n ? indexDictionary() : new Map();
  function bind(root = document.body) {
    const lookup = dictionaryIndex(); if (!lookup.size || !root) return;
    const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT), nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);
    nodes.forEach(node => { const text = node.textContent.trim(), parent = node.parentElement, key = lookup.get(text); if (key && parent && !parent.closest('script,style') && !parent.dataset.i18n) parent.dataset.i18n = key; });
    root.querySelectorAll('input[placeholder],textarea[placeholder]').forEach(input => { const key = lookup.get(input.placeholder); if (key) input.dataset.i18n = key; });
  }
  function addSwitcher() {
    if (document.querySelector('[data-language-switcher]')) return;
    const button = document.createElement('button');
    button.type = 'button'; button.dataset.languageSwitcher = 'true'; button.dataset.langToggle = 'true';
    button.className = 'fixed bottom-4 left-4 z-[100] rounded-full bg-primary px-4 py-2 text-xs font-bold text-white shadow-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-blue-300';
    button.setAttribute('aria-label', 'Switch language'); button.onclick = () => I18n.toggleLang(); document.body.append(button);
  }
  function refresh() { bind(); I18n.applyLang(); }
  function start() { refresh(); addSwitcher(); new MutationObserver(() => refresh()).observe(document.body, { childList: true, subtree: true }); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
  window.refreshLanguage = refresh;
})();
