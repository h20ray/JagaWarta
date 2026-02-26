# Theme Options Map

Ringkasan opsi yang saat ini bisa dikustomisasi pada theme JagaWarta, termasuk lokasi UI dan penggunaan di kode.

## Customizer: JagaWarta Options

- `Hero posts count` (`jagawarta_hero_count`)
  - UI: Customizer > JagaWarta Options > Front page
  - Digunakan di:
    - `front-page.php` (jumlah post area hero)
    - `inc/blocks/register.php` (fallback count block `jagawarta/hero`)

- `Hero as slider` (`jagawarta_front_hero_slider`)
  - UI: Customizer > JagaWarta Options > Front page
  - Digunakan di:
    - `template-parts/sections/home/featured-lead-with-secondary.php`
    - `inc/assets.php` (conditional enqueue hero slider)

- `Show breaking ticker on front page` (`jagawarta_ticker_on_front`)
  - UI: Customizer > JagaWarta Options > Front page
  - Digunakan di:
    - `home.php`
    - `inc/assets.php` (conditional enqueue ticker)

- `Ticker posts count` (`jagawarta_ticker_count`)
  - UI: Customizer > JagaWarta Options > Front page
  - Digunakan di:
    - `home.php`

- `Section grids (categories)` (`jagawarta_section_count`)
  - UI: Customizer > JagaWarta Options > Front page
  - Digunakan di:
    - `front-page.php` (legacy; tidak dipakai untuk mode vertical tabs)

- `Latest posts count` (`jagawarta_latest_count`)
  - UI: Customizer > JagaWarta Options > Front page
  - Digunakan di:
    - `front-page.php` (jumlah post pada latest stories)
    - `inc/query/posts.php` (`jagawarta_get_latest_ids_excluding`, max 30)

- `Category tabs (slugs)` (`jagawarta_front_tab_categories`)
  - UI: Customizer > JagaWarta Options > Front page
  - Format:
    - Comma-separated slug kategori (contoh: `news,tech,sport`)
    - Kosong = semua kategori non-empty
  - Digunakan di:
    - `front-page.php` (resolve daftar kategori untuk vertical tabs)
    - `template-parts/sections/home/category-vertical-tabs.php`

- `Category tabs posts count` (`jagawarta_front_tab_post_count`)
  - UI: Customizer > JagaWarta Options > Front page
  - Digunakan di:
    - `front-page.php` (jumlah post per tab, default 10)
    - `template-parts/sections/home/category-vertical-tabs.php`

- `Primary Brand Color` (`jagawarta_seed_primary`)
  - UI: Customizer > JagaWarta Options > Colors (MD3 Palette)
  - Digunakan di:
    - `inc/customizer.php` (generate & output palette CSS vars)
    - `inc/palette-generator.php`

## WordPress Theme/General Options (Outside Customizer Panel)

- `Logo & Site Identity` (`custom_logo`, title/tagline)
  - UI: Customizer > Site Identity
  - Digunakan di:
    - `template-parts/header.php`
    - `template-parts/footer.php`

- `Menus` (primary, hamburger, footer-categories, footer-links)
  - UI: Appearance > Menus
  - Didaftarkan di:
    - `functions.php` (`register_nav_menus`)

- `Homepage & Posts Page` (`show_on_front`, `page_on_front`, `page_for_posts`)
  - UI: Settings > Reading
  - Digunakan di:
    - `functions.php` (auto setup saat switch theme)
    - `inc/helpers.php` (helper URL/behavior terkait halaman blog/front)

## Taxonomy Option (Per Category)

- `Category Chip Colors` (term meta `jagawarta_chip_color`, `jagawarta_chip_color_hex`)
  - UI: Posts > Categories (add/edit form)
  - Digunakan di:
    - `inc/category-color.php` (admin UI, save, CSS vars)
    - `inc/helpers.php` (`jagawarta_get_category_colors`, output chip style)

## Admin Bar Shortcuts

Theme menambahkan menu admin bar `Theme Options` untuk akses cepat:
- Front Page Live Preview (Customizer + preview halaman home)
- Front Page Settings
- Brand Colors
- Logo & Site Identity
- Navigation Menus
- Category Chip Colors
- Homepage & Posts Page
