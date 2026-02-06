/**
 * Paste in DevTools Console (F12 → Console) on the single post page.
 * Diagnoses why JagaWarta theme CSS may not be loading.
 */
(function () {
  const root = document.documentElement;
  const allStyles = document.querySelectorAll('link[rel="stylesheet"]');

  console.group('JagaWarta — Border radius / DevTools check');
  console.log('1. Theme stylesheets (must both be present):');
  const tokensLink = document.querySelector('link[href*="tokens.css"]');
  const mainLink = document.querySelector('link[href*="main.css"]');
  console.log('   tokens.css:', tokensLink ? tokensLink.href : 'NOT FOUND');
  console.log('   main.css:', mainLink ? mainLink.href : 'NOT FOUND');

  if (!tokensLink || !mainLink) {
    console.warn('2. All stylesheets in page (theme CSS missing → wrong theme or cached HTML):');
    allStyles.forEach((l, i) => console.log('   [' + i + ']', l.href));
  }

  const varValue = getComputedStyle(root).getPropertyValue('--md-sys-shape-corner-extra-large').trim();
  console.log('3. CSS variable --md-sys-shape-corner-extra-large:', varValue || 'NOT SET');

  const mainEl = document.querySelector('main#main');
  const articleEl = mainEl?.querySelector('article');
  const figureEl = document.querySelector('main#main article figure');
  console.log('4. DOM structure:');
  console.log('   main#main:', !!mainEl);
  console.log('   article:', !!articleEl, articleEl?.className || '');
  console.log('   figure:', !!figureEl, figureEl?.className || '');

  const figureRounded = document.querySelector('main#main article figure.rounded-xl');
  if (figureRounded) {
    const s = getComputedStyle(figureRounded);
    console.log('5. Figure rounded-xl computed border-radius:', s.borderRadius);
  } else {
    console.log('5. No figure.rounded-xl (expected if theme CSS not loaded or different markup)');
  }
  console.groupEnd();
})();
