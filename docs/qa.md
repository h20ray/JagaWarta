# QA gates

## Design-system lint

- **Tidak ada hard-coded color**: Jalankan `npm run lint:design`. Akan gagal jika PHP/CSS/JS berisi hex (`#...`) atau `rgb(`, `hsl(`, `rgba(`, `hsla(` di luar file yang diizinkan (`assets/dist/tokens.css`, `build-tokens.mjs`, `design/`, `.md`). Pakai token classes saja (`text-on-surface`, `bg-surface-low`, dll).

## UX checklist (per template)

- **Satu H1 per halaman**: Hanya di single-header (judul artikel). Hero dan cards pakai H2.
- **Urutan heading**: h1 → h2 → h3; tidak boleh loncat level.
- **Focus order**: Urutan tab logis; skip link target `#main`.
- **Focus styles**: Setiap elemen interaktif punya focus yang terlihat (`.focus-ring` atau `focus-visible:outline-*`). Tidak boleh ada elemen interaktif tanpa focus styles.

## Performance

- **LCP**: Hero image di homepage; featured image di single. Keduanya pakai `loading="eager"`, `fetchpriority="high"` di tempat yang sesuai.
- **Slider JS**: Hanya diload saat hero-slider dipakai (`jagawarta_needs_slider()`). Tidak ada script slider di halaman tanpa slider.
- **Images**: Dimensions/sizes di-set sebisa mungkin untuk hindari layout shift.
