# Template Parts Naming Map

This theme now uses intent-based naming for template parts.

## Folder conventions

- `template-parts/sections/` = full page sections (home/archive/shared blocks)
- `template-parts/cards/` = reusable post card components by context
- `template-parts/cards/home/` = home-page specific cards
- `template-parts/cards/archive/` = archive/search/author list cards
- `template-parts/cards/hero/` = hero/featured cards
- `template-parts/cards/lists/` = compact/list/ranked cards
- `template-parts/cards/related/` = related-post cards
- `template-parts/cards/shared/` = generic shared cards

## Renamed files

- `template-parts/category-3up.php` -> `template-parts/sections/archive/featured-three-column.php`
- `template-parts/home/top-split.php` -> `template-parts/sections/home/featured-lead-with-secondary.php`
- `template-parts/home/first-grid.php` -> `template-parts/sections/home/latest-stories-grid.php`
- `template-parts/home/hero-slider.php` -> `template-parts/sections/home/featured-main-no-slider.php`
- `template-parts/home/top-featured.php` -> `template-parts/sections/home/featured-main-with-side-overlays.php`
- `template-parts/home/hero-featured.php` -> `template-parts/sections/home/single-featured-story.php`
- `template-parts/section-grid.php` -> `template-parts/sections/shared/category-post-grid.php`
- `template-parts/latest-list.php` -> `template-parts/sections/shared/latest-posts-grid.php`
- `template-parts/cards/card-bento.php` -> `template-parts/cards/home/secondary-feature.php`
- `template-parts/cards/card-categories-below-hero.php` -> `template-parts/cards/archive/featured-secondary.php`
- `template-parts/cards/card-categories.php` -> `template-parts/cards/archive/post-grid-item.php`
- `template-parts/cards/card-list-item.php` -> `template-parts/cards/lists/ranked-post-item.php`
- `template-parts/cards/card-overlay.php` -> `template-parts/cards/hero/slider-overlay.php`
- `template-parts/cards/card-home.php` -> `template-parts/cards/home/latest-story.php`
- `template-parts/cards/card-default.php` -> `template-parts/cards/shared/post-default.php`
- `template-parts/cards/card-related.php` -> `template-parts/cards/related/related-story.php`
- `template-parts/cards/card-hero.php` -> `template-parts/cards/hero/lead-story.php`
- `template-parts/cards/card-compact-overlay.php` -> `template-parts/cards/home/side-overlay-feature.php`
- `template-parts/cards/card-compact.php` -> `template-parts/cards/lists/compact-post-item.php`

## Migration note

Old template file names were removed to keep the theme structure clean.
If you have custom code that still includes old paths, update it using the rename map above.
