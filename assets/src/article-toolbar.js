(function () {
  'use strict';

  const init = () => {
    const desktopMql = window.matchMedia('(min-width: 768px)');

    const shareModal = document.querySelector('[data-share-modal]');
    const shareScrim = document.querySelector('[data-share-modal-scrim]');
    const shareCloseBtn = document.querySelector('[data-share-modal-close]');
    const shareTriggers = document.querySelectorAll('[data-share-trigger]');
    const shareRoot = document.querySelector('[data-share-root]');
    const copyButtons = document.querySelectorAll('[data-share-copy]');
    const liveRegion = document.querySelector('[data-share-message]');

    if (shareModal && shareModal.hasAttribute('hidden')) {
      shareModal.setAttribute('inert', '');
      shareModal.style.pointerEvents = 'none';
    }

    const shareTitle = shareRoot ? shareRoot.getAttribute('data-share-title') || document.title : document.title;
    const shareText = shareRoot ? shareRoot.getAttribute('data-share-text') || shareTitle : shareTitle;
    const shareUrl = shareRoot ? shareRoot.getAttribute('data-share-url') || window.location.href : window.location.href;

    const supportsNativeShare = typeof navigator !== 'undefined' && !!navigator.share;
    const preferNativeShare = supportsNativeShare && window.matchMedia('(max-width: 767px)').matches;
    let modalOpenedAt = 0;

    const openModal = () => {
      if (!shareModal) return;
      modalOpenedAt = Date.now();
      shareModal.removeAttribute('inert');
      shareModal.removeAttribute('hidden');
      shareModal.style.pointerEvents = 'auto';
      document.body.style.overflow = 'hidden';
      shareModal.classList.add('is-open');
      if (shareCloseBtn) {
        shareCloseBtn.focus();
      }
    };

    const closeModal = () => {
      if (!shareModal) return;
      shareModal.classList.remove('is-open');
      shareModal.style.pointerEvents = 'none';
      setTimeout(() => {
        shareModal.setAttribute('inert', '');
        shareModal.setAttribute('hidden', '');
        document.body.style.overflow = '';
      }, 200);
    };

    const announce = (message) => {
      if (!liveRegion) return;
      liveRegion.textContent = message;
      setTimeout(() => {
        liveRegion.textContent = '';
      }, 2000);
    };

    shareTriggers.forEach((button) => {
      button.addEventListener('click', async (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (preferNativeShare) {
          try {
            await navigator.share({ title: shareTitle, text: shareText, url: shareUrl });
            return;
          } catch (error) {
            if (error && error.name === 'AbortError') {
              return;
            }
          }
        }

        openModal();
      });
    });

    if (shareScrim) {
      shareScrim.addEventListener('click', (event) => {
        if (Date.now() - modalOpenedAt < 250) {
          event.preventDefault();
          return;
        }
        closeModal();
      });
    }

    if (shareCloseBtn) {
      shareCloseBtn.addEventListener('click', closeModal);
    }

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && shareModal && !shareModal.hasAttribute('hidden')) {
        closeModal();
      }
    });

    copyButtons.forEach((button) => {
      button.addEventListener('click', async (event) => {
        event.preventDefault();
        const url = button.getAttribute('data-share-copy-url') || shareUrl;

        if (navigator.clipboard && navigator.clipboard.writeText) {
          try {
            await navigator.clipboard.writeText(url);
            announce(button.getAttribute('aria-label') || 'Link copied');
            setTimeout(closeModal, 800);
            return;
          } catch {
          }
        }

        const input = document.createElement('input');
        input.type = 'text';
        input.value = url;
        input.setAttribute('aria-hidden', 'true');
        input.style.position = 'fixed';
        input.style.left = '-9999px';
        document.body.appendChild(input);
        input.select();

        try {
          document.execCommand('copy');
          announce(button.getAttribute('aria-label') || 'Link copied');
          setTimeout(closeModal, 800);
        } catch {
          announce('Copy not supported in this browser');
        } finally {
          document.body.removeChild(input);
        }
      });
    });

    const fontControls = document.querySelector('[data-font-size-controls]');
    const article = document.querySelector('.prose-article');
    if (!fontControls || !article) return;
    const rail = fontControls.querySelector('.jw-font-size-rail');

    const LEVELS = ['small', 'default', 'large'];
    const STORAGE_KEY = 'jw-article-font-size-level';
    const LEVEL_STYLES = {
      small: {
        size: 'var(--md-sys-typescale-body-medium-size)',
        line: 'var(--md-sys-typescale-body-medium-line-height)',
      },
      default: {
        size: 'var(--md-sys-typescale-body-large-size)',
        line: 'var(--md-sys-typescale-body-large-line-height)',
      },
      large: {
        size: 'calc(var(--md-sys-typescale-body-large-size) * 1.15)',
        line: 'calc(var(--md-sys-typescale-body-large-line-height) * 1.05)',
      },
    };

    const getInitialLevel = () => {
      try {
        const stored = sessionStorage.getItem(STORAGE_KEY);
        if (stored && LEVELS.includes(stored)) {
          return stored;
        }
      } catch {
      }
      return 'default';
    };

    const applyLevel = (level) => {
      if (!LEVELS.includes(level)) return;

      article.setAttribute('data-font-size-level', level);
      article.style.fontSize = LEVEL_STYLES[level].size;
      article.style.lineHeight = LEVEL_STYLES[level].line;

      try {
        sessionStorage.setItem(STORAGE_KEY, level);
      } catch {
      }

      fontControls.querySelectorAll('[data-font-size-level]').forEach((el) => {
        const elLevel = el.getAttribute('data-font-size-level');
        el.setAttribute('aria-pressed', elLevel === level ? 'true' : 'false');
      });
    };

    fontControls.querySelectorAll('[data-font-size-level]').forEach((button) => {
      button.addEventListener('click', (event) => {
        event.preventDefault();
        const level = button.getAttribute('data-font-size-level');
        if (level) {
          applyLevel(level);
        }
      });
    });

    const scrollUpButton = fontControls.querySelector('[data-scroll-action="up"]');
    const scrollDownButton = fontControls.querySelector('[data-scroll-action="down"]');
    const contentBlocks = article.querySelectorAll('p, h2, h3, ul, ol, blockquote, figure, .wp-block-image');
    const articleFirstBlock = contentBlocks[0] || article;
    const articleLastBlock = contentBlocks[contentBlocks.length - 1] || article;
    const getViewportTopOffset = () => {
      const header = document.getElementById('site-header');
      const adminBar = document.getElementById('wpadminbar');
      const headerBottom = header ? Math.round(header.getBoundingClientRect().bottom) : 0;
      const adminBottom = adminBar ? Math.round(adminBar.getBoundingClientRect().bottom) : 0;
      return Math.max(headerBottom, adminBottom) + 16;
    };

    if (scrollUpButton) {
      scrollUpButton.addEventListener('click', (event) => {
        event.preventDefault();
        const viewportOffset = getViewportTopOffset();
        const top = Math.max(
          0,
          Math.round(articleFirstBlock.getBoundingClientRect().top + window.scrollY - viewportOffset)
        );
        window.scrollTo({ top, behavior: 'smooth' });
      });
    }

    if (scrollDownButton) {
      scrollDownButton.addEventListener('click', (event) => {
        event.preventDefault();
        const viewportOffset = getViewportTopOffset();
        const rect = article.getBoundingClientRect();
        const top = Math.max(
          0,
          Math.round(rect.bottom + window.scrollY - (window.innerHeight - viewportOffset) + 16)
        );
        window.scrollTo({ top, behavior: 'smooth' });
      });
    }

    const setStickyMetricsOnce = () => {
      if (!desktopMql.matches) {
        fontControls.style.removeProperty('--jw-reading-toolbar-top');
        fontControls.style.removeProperty('--jw-reading-toolbar-height');
        return;
      }
      const header = document.getElementById('site-header');
      const adminBar = document.getElementById('wpadminbar');
      const headerBottom = header ? Math.round(header.getBoundingClientRect().bottom) : 72;
      const adminBottom = adminBar ? Math.round(adminBar.getBoundingClientRect().bottom) : 0;
      const topOffset = Math.max(headerBottom, adminBottom) + 16;
      fontControls.style.setProperty('--jw-reading-toolbar-top', `${topOffset}px`);
      if (rail) {
        fontControls.style.setProperty('--jw-reading-toolbar-height', `${Math.ceil(rail.offsetHeight)}px`);
      }
    };

    applyLevel(getInitialLevel());
    setStickyMetricsOnce();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();
