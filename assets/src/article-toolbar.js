(function () {
  'use strict';

  const mql = window.matchMedia('(min-width: 768px)');

  const article = document.querySelector('.prose-article');
  if (!article) return;

  const shareModal = document.querySelector('[data-share-modal]');
  const shareScrim = document.querySelector('[data-share-modal-scrim]');
  const shareCloseBtn = document.querySelector('[data-share-modal-close]');
  const shareTriggers = document.querySelectorAll('[data-share-trigger]');
  const shareRoot = document.querySelector('[data-share-root]');
  const copyButtons = document.querySelectorAll('[data-share-copy]');
  const liveRegion = document.querySelector('[data-share-message]');

  const shareTitle = shareRoot ? shareRoot.getAttribute('data-share-title') || document.title : document.title;
  const shareText = shareRoot ? shareRoot.getAttribute('data-share-text') || shareTitle : shareTitle;
  const shareUrl = shareRoot ? shareRoot.getAttribute('data-share-url') || window.location.href : window.location.href;

  const supportsNativeShare = typeof navigator !== 'undefined' && !!navigator.share;

  const openModal = () => {
    if (!shareModal) return;
    shareModal.removeAttribute('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
      shareModal.classList.add('is-open');
    });
    if (shareCloseBtn) {
      shareCloseBtn.focus();
    }
  };

  const closeModal = () => {
    if (!shareModal) return;
    shareModal.classList.remove('is-open');
    setTimeout(() => {
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

  if (shareTriggers.length > 0) {
    shareTriggers.forEach((button) => {
      button.addEventListener('click', async (event) => {
        event.preventDefault();
        if (supportsNativeShare) {
          try {
            await navigator.share({
              title: shareTitle,
              text: shareText,
              url: shareUrl,
            });
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
  }

  if (shareScrim) {
    shareScrim.addEventListener('click', closeModal);
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
          setTimeout(() => {
            closeModal();
          }, 800);
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
        setTimeout(() => {
          closeModal();
        }, 800);
      } catch {
        announce('Copy not supported in this browser');
      } finally {
        document.body.removeChild(input);
      }
    });
  });

  const fontControls = document.querySelector('[data-font-size-controls]');
  if (!fontControls || !mql.matches) return;

  const LEVELS = ['small', 'default', 'large'];
  const STORAGE_KEY = 'jw-article-font-size-level';

  const getInitialLevel = () => {
    const stored = sessionStorage.getItem(STORAGE_KEY);
    if (stored && LEVELS.includes(stored)) {
      return stored;
    }
    return 'default';
  };

  const applyLevel = (level) => {
    if (!LEVELS.includes(level)) return;
    article.setAttribute('data-font-size-level', level);
    sessionStorage.setItem(STORAGE_KEY, level);

    fontControls.querySelectorAll('[data-font-size-level]').forEach((el) => {
      const elLevel = el.getAttribute('data-font-size-level');
      el.setAttribute('aria-pressed', elLevel === level ? 'true' : 'false');
    });
  };

  const changeLevelByStep = (delta) => {
    const current = article.getAttribute('data-font-size-level') || getInitialLevel();
    const index = LEVELS.indexOf(current);
    if (index === -1) {
      applyLevel('default');
      return;
    }
    let nextIndex = index + delta;
    if (nextIndex < 0) nextIndex = 0;
    if (nextIndex > LEVELS.length - 1) nextIndex = LEVELS.length - 1;
    applyLevel(LEVELS[nextIndex]);
  };

  const sizeButtons = fontControls.querySelectorAll('[data-font-size-level]');
  sizeButtons.forEach((button) => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      const level = button.getAttribute('data-font-size-level');
      if (!level) return;
      applyLevel(level);
    });
  });

  const decreaseButton = fontControls.querySelector('[data-font-size-action="decrease"]');
  const increaseButton = fontControls.querySelector('[data-font-size-action="increase"]');

  if (decreaseButton) {
    decreaseButton.addEventListener('click', (event) => {
      event.preventDefault();
      changeLevelByStep(-1);
    });
  }

  if (increaseButton) {
    increaseButton.addEventListener('click', (event) => {
      event.preventDefault();
      changeLevelByStep(1);
    });
  }

  const updatePosition = () => {
    if (!mql.matches) return;
    const headerImg = document.querySelector('header figure');
    if (!headerImg) return;

    const rect = headerImg.getBoundingClientRect();
    const scrollY = window.scrollY || window.pageYOffset;
    const top = rect.bottom + scrollY + 24;

    fontControls.style.top = `${top}px`;
  };

  const handleScroll = () => {
    if (!mql.matches) return;
    requestAnimationFrame(updatePosition);
  };

  window.addEventListener('scroll', handleScroll, { passive: true });
  window.addEventListener('resize', updatePosition, { passive: true });

  applyLevel(getInitialLevel());
  updatePosition();
})();
