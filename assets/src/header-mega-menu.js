(() => {
  'use strict';

  const navMenu = document.querySelector('#header-nav .jw-nav-menu');
  if (!navMenu) return;

  const topLevelItems = Array.from(navMenu.children).filter(
    (el) => el?.classList?.contains?.('menu-item-has-children')
  );
  if (topLevelItems.length === 0) return;

  let openItem = null;

  const isDesktop = () => window.matchMedia('(min-width: 768px)').matches;
  const getPanel = (li) => li?.querySelector?.('.jw-mega-menu-container') ?? null;
  const getTrigger = (li) => li?.querySelector?.('a') ?? null;

  function syncClosed(li) {
    if (!li) return;
    li.classList.remove('is-open');
    const trigger = getTrigger(li);
    if (trigger) trigger.setAttribute('aria-expanded', 'false');
    const panel = getPanel(li);
    if (panel) panel.style.display = 'none';
  }

  function syncOpen(li) {
    if (!li) return;
    li.classList.add('is-open');
    const trigger = getTrigger(li);
    if (trigger) trigger.setAttribute('aria-expanded', 'true');
    const panel = getPanel(li);
    if (panel) panel.style.display = 'block';
  }

  // Initialize: make all panels closed in a deterministic way.
  topLevelItems.forEach((li) => syncClosed(li));

  function close() {
    if (!openItem) return;
    syncClosed(openItem);
    openItem = null;
  }

  function open(li) {
    if (!li) return;
    if (openItem === li) return;
    close();
    syncOpen(li);
    openItem = li;
  }

  function toggle(li) {
    if (!li) return;
    if (openItem === li) close();
    else open(li);
  }

  topLevelItems.forEach((li) => {
    const trigger = getTrigger(li);
    if (!trigger) return;

    // Desktop: hover opens, but we do NOT auto-close on mouseleave (per requirement).
    trigger.addEventListener('mouseenter', () => {
      if (!isDesktop()) return;
      open(li);
    });

    // Click/tap: toggle open/close. (Prevents navigation for parent-with-children items.)
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      toggle(li);
    });

    trigger.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        toggle(li);
        if (openItem === li) li.querySelector('.jw-mega-menu a')?.focus?.();
        return;
      }

      if (event.key === 'ArrowDown') {
        event.preventDefault();
        open(li);
        li.querySelector('.jw-mega-menu a')?.focus?.();
        return;
      }

      if (event.key === 'Escape') {
        close();
        trigger.focus();
      }
    });
  });

  // Close button inside the mega panel.
  navMenu.addEventListener('click', (event) => {
    const closeBtn = event.target?.closest?.('.jw-mega-menu-close');
    if (!closeBtn) return;
    event.preventDefault();
    event.stopPropagation();
    close();
  });

  // Click outside closes.
  document.addEventListener('click', (event) => {
    if (!navMenu.contains(event.target)) close();
  });

  // Escape closes.
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') close();
  });
})();

