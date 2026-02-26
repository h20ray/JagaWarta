(function () {
  'use strict';
  const fallbackImages = () => {
    document.addEventListener(
      'error',
      (event) => {
        const target = event.target;
        if (!target || target.tagName !== 'IMG') return;
        const fallback = target.getAttribute('data-jw-fallback');
        if (!fallback || target.getAttribute('data-jw-fallback-applied') === 'true') return;
        target.setAttribute('data-jw-fallback-applied', 'true');
        target.removeAttribute('srcset');
        target.src = fallback;
      },
      true
    );
  };

  fallbackImages();

  const initCategoryTabs = () => {
    const tabRoots = document.querySelectorAll('[data-jw-category-tabs]');
    if (!tabRoots.length) return;

    tabRoots.forEach((root) => {
      const tabs = Array.from(root.querySelectorAll('[data-jw-tab]'));
      const panels = Array.from(root.querySelectorAll('[data-jw-panel]'));
      if (!tabs.length || !panels.length) return;
      const tablist = root.querySelector('[role="tablist"]');
      const select = root.querySelector('[data-jw-tab-select]');

      const activateTab = (nextIndex, shouldFocus = false) => {
        tabs.forEach((tab, index) => {
          const isActive = index === nextIndex;
          tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
          tab.setAttribute('tabindex', isActive ? '0' : '-1');
          tab.classList.toggle('is-active', isActive);
          if (panels[index]) {
            panels[index].hidden = !isActive;
          }
        });

        if (shouldFocus && tabs[nextIndex]) {
          tabs[nextIndex].focus();
        }

        if (tablist && tabs[nextIndex]) {
          tabs[nextIndex].scrollIntoView({
            behavior: 'smooth',
            inline: 'center',
            block: 'nearest',
          });
        }

        if (select) {
          select.value = String(nextIndex);
        }
      };

      tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => activateTab(index));
        tab.addEventListener('keydown', (event) => {
          let nextIndex = index;
          if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
            nextIndex = (index + 1) % tabs.length;
          } else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
            nextIndex = (index - 1 + tabs.length) % tabs.length;
          } else if (event.key === 'Home') {
            nextIndex = 0;
          } else if (event.key === 'End') {
            nextIndex = tabs.length - 1;
          } else {
            return;
          }
          event.preventDefault();
          activateTab(nextIndex, true);
        });
      });

      if (select) {
        select.addEventListener('change', () => {
          const nextIndex = Number.parseInt(select.value, 10);
          if (!Number.isNaN(nextIndex) && nextIndex >= 0 && nextIndex < tabs.length) {
            activateTab(nextIndex, false);
          }
        });
      }

      const activeIndex = tabs.findIndex((tab) => tab.getAttribute('aria-selected') === 'true');
      if (activeIndex >= 0) {
        activateTab(activeIndex, false);
      }
    });
  };

  initCategoryTabs();

  const nav = document.querySelector('[data-jagawarta-nav]');
  if (!nav) {
    return;
  }
  const toggle = nav.querySelector('[data-jagawarta-nav-toggle]');
  const panel = nav.querySelector('[data-jagawarta-nav-panel]');
  const scrim = nav.querySelector('[data-jagawarta-nav-scrim]');
  const menu = nav.querySelector('[data-jagawarta-nav-menu]');
  if (!toggle || !panel || !menu || !scrim) return;

  const mql = window.matchMedia('(min-width: 768px)');
  const TRANSITION_MS = 180;
  let closeTimer;

  const setOpen = (open) => {
    if (closeTimer) {
      clearTimeout(closeTimer);
      closeTimer = null;
    }
    toggle.setAttribute('aria-expanded', String(open));
    document.body.classList.toggle('jw-nav-open', open);

    if (open) {
      panel.hidden = false;
      scrim.hidden = false;
      panel.setAttribute('aria-hidden', 'false');
      scrim.setAttribute('aria-hidden', 'false');
      requestAnimationFrame(() => {
        panel.classList.add('is-open');
        scrim.classList.add('is-open');
      });
      return;
    }

    panel.classList.remove('is-open');
    scrim.classList.remove('is-open');
    panel.setAttribute('aria-hidden', 'true');
    scrim.setAttribute('aria-hidden', 'true');
    closeTimer = setTimeout(() => {
      panel.hidden = true;
      scrim.hidden = true;
    }, TRANSITION_MS);
  };

  const isOpen = () => panel.hidden === false;

  setOpen(false);

  toggle.addEventListener('click', (event) => {
    event.preventDefault();
    setOpen(!isOpen());
  });

  menu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => setOpen(false));
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      setOpen(false);
    }
  });

  document.addEventListener('click', (event) => {
    if (!nav.contains(event.target) && isOpen()) {
      setOpen(false);
    }
  });

  scrim.addEventListener('click', () => setOpen(false));

  if (mql.addEventListener) {
    mql.addEventListener('change', (event) => {
      if (event.matches) {
        setOpen(false);
      }
    });
  } else if (mql.addListener) {
    mql.addListener((event) => {
      if (event.matches) {
        setOpen(false);
      }
    });
  }

  const submenuToggles = menu.querySelectorAll('.jw-mobile-submenu-toggle');
  submenuToggles.forEach((toggle) => {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
      const submenuId = toggle.getAttribute('aria-controls');
      const submenu = document.getElementById(submenuId);

      if (submenu) {
        toggle.setAttribute('aria-expanded', String(!isExpanded));
        if (!isExpanded) {
          submenu.removeAttribute('hidden');
          submenu.style.maxHeight = submenu.scrollHeight + 'px';
        } else {
          submenu.style.maxHeight = '0';
          setTimeout(() => {
            submenu.setAttribute('hidden', '');
          }, 300);
        }
      }
    });
  });
})();
