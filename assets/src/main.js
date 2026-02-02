(function () {
  'use strict';
  const nav = document.querySelector('[data-jagawarta-nav]');
  if (!nav) return;
  const toggle = nav.querySelector('[data-jagawarta-nav-toggle]');
  const menu = nav.querySelector('[data-jagawarta-nav-menu]');
  if (!toggle || !menu) return;
  toggle.setAttribute('aria-expanded', 'false');
  toggle.addEventListener('click', function () {
    const open = menu.hidden === true;
    menu.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
  });
})();
