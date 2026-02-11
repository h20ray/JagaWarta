(function () {
  'use strict';

  const header = document.querySelector('[data-jagawarta-header]');
  if (!header) return;

  const nav = document.getElementById('header-nav');
  const logo = document.getElementById('header-logo');
  const menuBtn = document.getElementById('mobile-menu-btn');
  const searchContainer = document.getElementById('header-search-container');
  const searchWrapper = document.getElementById('search-wrapper');
  const searchInput = document.getElementById('header-search');
  const closeBtn = document.getElementById('search-close-btn');
  const resultsContainer = document.getElementById('search-results');
  const searchPlaceholder = document.getElementById('search-placeholder-text');
  const searchScrim = document.getElementById('search-scrim');

  if (!searchWrapper || !searchInput || !resultsContainer) return;

  const ajaxUrl = window.jagawarta_header?.ajax_url || '/wp-admin/admin-ajax.php';
  const BORDER_FADE_MS = 100;
  const EXPAND_DURATION_MS = 200;
  let debounceTimer;

  const collapsedClasses = [
    'w-12', 'h-12', 'justify-center', 'px-0', 'bg-transparent', 'border-0', 'hover:bg-surface-high', 'text-on-surface',
    'md:w-auto', 'md:h-12', 'md:justify-start', 'md:px-4', 'md:bg-surface-low', 'md:border', 'md:border-outline-variant',
    'md:text-on-surface-variant', 'md:hover:bg-surface-mid', 'md:hover:border-outline',
  ];
  const expandedClasses = [
    'w-[90vw]', 'md:w-search-width', 'h-12', 'bg-surface', 'shadow-elevation-2', 'border-0', 'pl-6', 'pr-0',
    'cursor-text', 'pointer-events-auto', 'text-on-surface',
  ];

  function enableSearch() {
    const pillWidth = searchWrapper.getBoundingClientRect().width;
    searchWrapper.style.width = pillWidth + 'px';
    searchWrapper.style.minWidth = pillWidth + 'px';

    const isDesktop = window.matchMedia('(min-width: 768px)').matches;
    if (nav) nav.classList.add('opacity-0', '-translate-x-8', 'pointer-events-none');
    if (logo && !isDesktop) logo.classList.add('opacity-0', 'pointer-events-none');
    if (menuBtn) menuBtn.classList.add('opacity-0', 'pointer-events-none');

    if (searchScrim) {
      searchScrim.hidden = false;
      searchScrim.setAttribute('aria-hidden', 'false');
      requestAnimationFrame(() => searchScrim.classList.add('is-open'));
    }

    searchWrapper.classList.add('search-border-fade', 'border-transparent', 'shadow-elevation-1');
    searchWrapper.classList.remove('md:border-outline-variant');
    if (searchPlaceholder) searchPlaceholder.classList.add('opacity-0');

    setTimeout(() => {
      searchContainer.classList.add('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');

      searchWrapper.style.width = '';
      searchWrapper.style.minWidth = '';
      searchWrapper.classList.remove('search-border-fade', 'border', 'border-transparent', 'shadow-elevation-1');
      searchWrapper.classList.remove(...collapsedClasses);
      searchWrapper.classList.add(...expandedClasses);

      searchInput.classList.remove('opacity-0', 'pointer-events-none');
      searchInput.classList.add('opacity-100', 'pointer-events-auto');
      closeBtn.classList.remove('hidden', 'opacity-0');
      closeBtn.classList.add('flex', 'opacity-100');
      searchInput.focus();
    }, BORDER_FADE_MS);
  }

  function disableSearch() {
    if (nav) nav.classList.remove('opacity-0', '-translate-x-8', 'pointer-events-none');
    if (logo) logo.classList.remove('opacity-0', 'pointer-events-none');
    if (menuBtn) menuBtn.classList.remove('opacity-0', 'pointer-events-none');

    if (searchScrim) {
      searchScrim.classList.remove('is-open');
      searchScrim.setAttribute('aria-hidden', 'true');
      setTimeout(() => { searchScrim.hidden = true; }, 300);
    }

    searchContainer.classList.remove('absolute', 'left-1/2', '-translate-x-1/2', 'z-40', 'w-auto', 'h-full', 'flex', 'items-center', 'justify-center', 'pointer-events-none');

    searchWrapper.style.width = '';
    searchWrapper.style.minWidth = '';
    searchWrapper.classList.remove(...expandedClasses);
    searchWrapper.classList.add(...collapsedClasses, 'cursor-pointer');
    searchWrapper.classList.add('border-transparent');

    setTimeout(() => {
      searchWrapper.classList.add('search-border-fade');
      searchWrapper.classList.remove('border-transparent');
      searchWrapper.classList.add('md:border-outline-variant');
      setTimeout(() => searchWrapper.classList.remove('search-border-fade'), BORDER_FADE_MS);
    }, EXPAND_DURATION_MS);

    if (searchPlaceholder) searchPlaceholder.classList.remove('opacity-0');
    searchInput.classList.add('opacity-0', 'pointer-events-none');
    searchInput.classList.remove('opacity-100', 'pointer-events-auto');
    closeBtn.classList.add('hidden', 'opacity-0');
    closeBtn.classList.remove('flex', 'opacity-100');
    searchInput.value = '';
    resultsContainer.classList.add('hidden');
    resultsContainer.innerHTML = '';
  }

  searchWrapper.addEventListener('click', () => {
    if (searchInput.classList.contains('opacity-0')) enableSearch();
  });

  if (searchPlaceholder) {
    searchPlaceholder.addEventListener('click', (e) => {
      e.stopPropagation();
      enableSearch();
    });
  }

  closeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    disableSearch();
  });

  if (searchScrim) {
    searchScrim.addEventListener('click', () => disableSearch());
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') disableSearch();
  });

  document.addEventListener('click', (e) => {
    if (!searchWrapper.contains(e.target) && !searchInput.classList.contains('opacity-0')) {
      disableSearch();
    }
  });

  function renderSkeleton() {
    const skeletonHtml = Array(3).fill(0).map(() => `
      <div class="flex items-center gap-4 p-3 animate-pulse">
        <div class="w-12 h-12 rounded-md bg-surface-high flex-shrink-0"></div>
        <div class="flex flex-col flex-1 gap-2">
          <div class="h-4 bg-surface-high rounded w-3/4"></div>
          <div class="h-3 bg-surface-high rounded w-1/4"></div>
        </div>
      </div>
    `).join('');
    resultsContainer.innerHTML = `<div class="p-2">${skeletonHtml}</div>`;
    resultsContainer.classList.remove('hidden');
    resultsContainer.classList.add('animate-fade-in');
  }

  function renderResults(data, isFallback = false) {
    const results = Array.isArray(data) ? data : [];
    if (results.length === 0) {
      resultsContainer.innerHTML = '<div class="p-4 text-body-medium text-on-surface-variant text-center">No results found.</div>';
      resultsContainer.classList.remove('hidden');
      return;
    }

    let headerHtml = '';
    if (isFallback) {
      headerHtml = '<div class="px-4 py-2 text-label-medium text-on-surface-variant border-b border-outline-variant">No results found. Recommended:</div>';
    }

    const html = results.map(item => `
      <a href="${item.url}" class="flex items-center gap-4 p-3 hover:bg-surface-high rounded-lg transition-colors group decoration-none border-none outline-none">
        ${item.thumb ? `<img src="${item.thumb}" alt="" class="w-12 h-12 rounded-md object-cover bg-surface-highest flex-shrink-0 shadow-sm">` : ''}
        <div class="flex flex-col">
          <span class="text-body-medium font-medium text-on-surface line-clamp-2 leading-snug group-hover:text-primary transition-colors">${item.title}</span>
          <span class="text-label-small text-on-surface-variant mt-0.5">${item.date}</span>
        </div>
      </a>
    `).join('');

    resultsContainer.innerHTML = `<div class="p-1 flex flex-col border-none outline-none">${headerHtml}${html}</div>`;
    resultsContainer.classList.remove('hidden');
  }

  function performSearch(term) {
    if (term.length < 3) {
      resultsContainer.classList.add('hidden');
      return;
    }
    renderSkeleton();
    fetch(`${ajaxUrl}?action=jagawarta_live_search&s=${encodeURIComponent(term)}`)
      .then(r => r.json())
      .then(res => {
        if (res.success && res.data) {
          const posts = res.data.data;
          const isFallback = res.data.is_fallback;
          renderResults(posts, isFallback);
        } else {
          renderResults([]);
        }
      })
      .catch(() => resultsContainer.classList.add('hidden'));
  }

  searchInput.addEventListener('input', (e) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => performSearch(e.target.value), 300);
  });
})();
