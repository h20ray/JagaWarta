/**
 * Dark mode controller: toggle, persistence, system preference detection.
 * Runs immediately with defer to prevent flash of incorrect theme.
 */
(function () {
    'use strict';

    const STORAGE_KEY = 'jw-dark-mode';
    const DATA_ATTR = 'data-theme';
    const THEME_LIGHT = 'light';
    const THEME_DARK = 'dark';

    /**
     * Get user's preferred theme from localStorage or system preference.
     *
     * @return {string} 'light' or 'dark'
     */
    function getPreferredTheme() {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored === THEME_LIGHT || stored === THEME_DARK) {
            return stored;
        }

        // Fall back to system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return THEME_DARK;
        }

        return THEME_LIGHT;
    }

    /**
     * Apply theme to HTML element.
     *
     * @param {string} theme 'light' or 'dark'
     */
    function applyTheme(theme) {
        document.documentElement.setAttribute(DATA_ATTR, theme);
    }

    /**
     * Save theme preference to localStorage.
     *
     * @param {string} theme 'light' or 'dark'
     */
    function saveTheme(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {
            // localStorage might be disabled; fail silently
        }
    }

    /**
     * Toggle between light and dark themes.
     */
    function toggleTheme() {
        const current = document.documentElement.getAttribute(DATA_ATTR);
        const newTheme = current === THEME_DARK ? THEME_LIGHT : THEME_DARK;
        applyTheme(newTheme);
        saveTheme(newTheme);
        updateToggleButton(newTheme);

        // Dispatch custom event for other components
        document.dispatchEvent(new CustomEvent('themechange', { detail: { theme: newTheme } }));
    }

    /**
     * Update toggle button ARIA label and icon visibility.
     *
     * @param {string} theme Current theme
     */
    function updateToggleButton(theme) {
        const button = document.getElementById('dark-mode-toggle');
        if (!button) return;

        const iconSun = button.querySelector('.icon-sun');
        const iconMoon = button.querySelector('.icon-moon');

        if (theme === THEME_DARK) {
            button.setAttribute('aria-label', 'Switch to light mode');
            if (iconSun) iconSun.style.display = 'block';
            if (iconMoon) iconMoon.style.display = 'none';
        } else {
            button.setAttribute('aria-label', 'Switch to dark mode');
            if (iconSun) iconSun.style.display = 'none';
            if (iconMoon) iconMoon.style.display = 'block';
        }
    }

    /**
     * Initialize dark mode on page load.
     */
    function init() {
        const preferredTheme = getPreferredTheme();
        applyTheme(preferredTheme);
        updateToggleButton(preferredTheme);

        // Listen for toggle button clicks
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('dark-mode-toggle');
            if (toggleButton) {
                toggleButton.addEventListener('click', toggleTheme);
            }
        });

        // Listen for system preference changes
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            mediaQuery.addEventListener('change', function (e) {
                // Only auto-switch if user hasn't manually set preference
                if (!localStorage.getItem(STORAGE_KEY)) {
                    const newTheme = e.matches ? THEME_DARK : THEME_LIGHT;
                    applyTheme(newTheme);
                    updateToggleButton(newTheme);
                }
            });
        }
    }

    // Run immediately to prevent flash
    init();
})();
