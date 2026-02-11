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
})();
