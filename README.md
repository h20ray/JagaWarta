# JagaWarta

Tema berita WordPress yang mengutamakan performa. Pakai Tailwind CSS, conditional assets, dan ramah cache.

## Build

```bash
npm install
npm run build
```

- `npm run build` — generate tokens, Tailwind CSS, dan JS ke `assets/dist/`
- `npm run build:tokens` — emit `tokens.css` dari `design/tokens.json` + `tokens/*.json` (sys + role layer)
- `npm run build:css` — Tailwind saja
- `npm run build:js` — bundle JS saja
- `npm run dev` — watch mode (CSS + JS)

## Struktur

- **design/tokens.json** — Single source of truth untuk warna (light/dark). Build emit sys + role layer ke `tokens.css`.
- **tokens/** — Typography, shape, motion, spacing, state, elevation. Digabung dengan design tokens oleh `build-tokens.mjs`.
- **inc/** — Logic saja: `helpers.php`, `assets.php`, `query/posts.php`, `blocks/` (hero, section-grid, related-posts)
- **template-parts/** — UI yang dipakai ulang (header, footer, hero, section-grid, post-card, single-*, related-posts, pagination)
- **assets/src/** — Entry Tailwind (`main.css`), sumber JS (`main.js`, `slider.js`)
- **assets/dist/** — Hasil build CSS/JS; `tokens.css` + `main.css`; di-commit supaya theme jalan tanpa harus run build. Asset slider dan ticker hanya diload saat hero atau breaking-ticker dipakai.
- **assets/images/** — Gambar template (bukan hasil build). Taruh `jwid_default.png` di sini untuk fallback image saat post tidak punya featured image dan tidak ada gambar di konten.

## Design system

UI pakai token ala Material Design 3 saja: tidak ada hard-coded color di class theme. Tailwind memetakan ke CSS variables dari `tokens.css`. Dark theme: set `data-theme="dark"` di `<html>` untuk ganti.

## Conditional loading

- Base: `main.css`, `main.js` (nav) di tiap halaman.
- Slider (Splide): hanya saat hero block ada di halaman atau front page pakai hero.
- Breaking ticker / Alpine: hanya saat block atau komponen ticker ada.

Ganti `screenshot.png` dengan preview theme 1200×900 (atau 1200×675) untuk Appearance → Themes.

## Quality

- `npm run lint:php` — jalankan `php -l` untuk semua file PHP theme.
- `npm run lint:design` — gagal jika ada hard-coded color (hex, rgb, hsl) di luar file yang diizinkan. Lihat [docs/qa.md](docs/qa.md).
- Sebelum rilis: pastikan tidak ada nonce di cached body, tidak ada `update_post_meta` on view; semua heavy assets conditional.

## Nanti / To-do

- **Radio / podcast player** — Opsional sticky player bar atau block; conditional load player JS/CSS. Opsional: instant navigation (fetch + swap content) supaya playback lanjut saat buka post; atau sessionStorage resume setelah full page load.
