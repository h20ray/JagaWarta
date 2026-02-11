(function () {
  'use strict';

  const navMenu = document.querySelector('#header-nav .jw-nav-menu');
  if (!navMenu) return;

  const menuItems = navMenu.querySelectorAll('.menu-item-has-children');
  let openItem = null;

  function closeMegaMenu() {
    if (openItem) {
      openItem.classList.remove('is-open');
      const link = openItem.querySelector('a');
      if (link) link.setAttribute('aria-expanded', 'false');
      openItem = null;
    }
  }

  function openMegaMenu(item) {
    closeMegaMenu();
    item.classList.add('is-open');
    const link = item.querySelector('a');
    if (link) link.setAttribute('aria-expanded', 'true');
    openItem = item;
  }

  menuItems.forEach(item => {
    const link = item.querySelector('a');
    if (!link) return;

    link.setAttribute('aria-expanded', 'false');
    link.setAttribute('aria-haspopup', 'true');

    link.addEventListener('mouseenter', () => openMegaMenu(item));

    item.addEventListener('mouseleave', () => closeMegaMenu());

    link.addEventListener('click', () => closeMegaMenu());

    link.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        if (item.classList.contains('is-open')) {
          closeMegaMenu();
        } else {
          openMegaMenu(item);
          const firstSubItem = item.querySelector('.jw-mega-menu a');
          if (firstSubItem) firstSubItem.focus();
        }
      } else if (e.key === 'Escape') {
        closeMegaMenu();
        link.focus();
      } else if (e.key === 'ArrowDown') {
        e.preventDefault();
        openMegaMenu(item);
        const firstSubItem = item.querySelector('.jw-mega-menu a');
        if (firstSubItem) firstSubItem.focus();
      }
    });
  });

  document.addEventListener('click', (e) => {
    if (!navMenu.contains(e.target)) closeMegaMenu();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && openItem) closeMegaMenu();
  });
})();
