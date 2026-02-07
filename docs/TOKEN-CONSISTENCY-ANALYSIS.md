# Theme token consistency analysis

Deep audit of raw values vs token-based usage per project guidelines: **no hex, no hardcoded values, everything role-driven and MD3-compliant.**

**Status (post-implementation):** Layout, z-index, hero heights, slots, focus-ring, skip-link, search motion, and article/single layout now use tokens. DRY layout class `.layout-article-inner` and `tokens/layout.json` are in place. Remaining arbitrary values are viewport-relative (`90vw`, `70vh` in token source) or third-party.

**Excluded from “violations”:**
- `tokens/*.json` and `assets/dist/tokens.css` (token *sources* — hex/rgba here define the system)
- `build-tokens.mjs` (emits tokens)
- Third-party dist files (`assets/dist/slider.css`, Splide)
- Tailwind’s own preflight in `main.css` (e.g. `#0000`, `rgba(59,130,246,.5)`)

**Header motion (MD3-aligned):** Menu (nav) only fades/slides with `duration-expand` (700ms) when search expands; logo/site name have no transition. Search expand/collapse uses `duration-long` (500ms, MD3 container transform).

---

## 1. Shadows — FIXED

Theme uses `shadow-elevation-1`, `shadow-elevation-2`, `shadow-elevation-3` throughout (header, search results, post cards). No raw `shadow-[...rgba(...)]` in templates.

---

## 2. Motion — FIXED

Search uses `duration-medium`, `delay-short`. `#search-wrapper.search-border-fade` uses `var(--md-sys-motion-delay-short)`. No raw `300ms`/`100ms` in theme CSS.

---

## 3. Typography — FIXED

Templates use typescale utilities (`text-label-small`, `text-body-large`, `text-headline-large`, `text-display-small`, etc.). No raw `text-[…rem]` in uncommitted template parts.

---

## 4. Spacing / dimensions — FIXED (layout tokens)

**Layout tokens added** in `tokens/layout.json` and Tailwind extend:
- `layout-content-max` (726px), `layout-article-width` (1046px), `layout-article-offset` (107px), `layout-sidebar-width` (127px), `layout-page-max` (1260px), `layout-wide-max` (1440px), `layout-search-width` (600px)
- Hero: `layout-hero-min-height`, `layout-hero-max-height`, `layout-hero-mobile` / `hero-sm` / `hero-lg` (22/26/30rem)
- Slots: `layout-slot-newsletter-min`, `layout-slot-ad-min`
- Search results: `layout-results-max-height` (80vh)
- Z-index: `z-overlay` (100)

Templates use `max-w-content-max`, `max-w-article-width`, `max-w-page-max`, `max-w-wide-max`, `md:w-sidebar-width`, `md:w-search-width`, `min-h-hero-min`, `max-h-hero-max`, `h-hero-mobile` / `h-hero-sm` / `h-hero-lg`, `min-h-slot-newsletter`, `min-h-slot-ad`, `max-h-results-max`. Article layout uses `.layout-article-inner` (DRY). Remaining arbitrary: `w-[90vw]` in header JS (viewport-relative, kept).

---

## 5. Opacity — FIXED

`.btn-filled:hover` uses `var(--md-sys-button-hover-opacity)` (state token).

---

## 6. Inline styles — FIXED

No inline `style="..."` for layout/typography; single-header uses `tracking-tight`, breadcrumb uses `min-h-12` / layout class.

---

## 7. Z-index — FIXED

`.skip-link:focus` uses `z-overlay` (Tailwind utility from `var(--md-sys-z-overlay)`).

---

## 8. Tailwind config vs tokens

- **Elevation:** Config uses only elevation-0–3 (matches `tokens/elevation.json`). No elevation-4/5.
- **Focus ring:** `.focus-ring` uses `var(--md-sys-focus-ring-width)`.

---

## 9. Remaining arbitrary values (acceptable)

- **Viewport-relative:** `w-[90vw]` in header search expand (JS). Token `layout-hero-max-height` and `layout-results-max-height` are 70vh/80vh in token source — intentional.
- **Prose:** `page.php` may use `max-w-[65ch]` — readable line length; can stay or add a token later.
- **Splide / third-party:** Excluded per doc.

---

## 10. Token additions implemented

- **Motion:** `delay-short` (100ms) in `tokens/motion.json` and Tailwind.
- **State:** `button-hover-opacity` in state tokens; used in main.css.
- **Layout:** `tokens/layout.json` with content-max, article-width, article-offset, sidebar-width, page-max, wide-max, search-width, hero heights, slot mins, results-max-height.
- **Z-index:** `z-overlay` (100) in layout.json and Tailwind.

Theme now follows the “no hex, no hardcoded values” guideline strictly outside token source files and third-party assets.
