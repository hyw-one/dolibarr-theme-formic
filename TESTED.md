# iForm Theme Test Record

Date: 2026-07-04

Target: Dolibarr ERP/CRM 23.0.0

Theme id: `iform`

## Rename Pass

This pass finalizes the Eldy-based iForm theme under the theme id `iform`.

- Theme directory is now `iform`.
- `style.css.php` now uses `$theme = 'iform'`, so images and includes resolve under `/theme/iform/`.
- The install/test URL is now `?theme=iform`.
- The previous tab and button fixes are preserved.

## Final Checks

- PHP syntax checks passed for `style.css.php`, `theme_vars.inc.php`, and `manifest.json.php`.
- CSS endpoint returned HTTP 200 for `/theme/iform/style.css.php`.
- Package root is `iform/`.
- Duplicate copied resources matching `* 2.*` count: 0.
- Top-menu selected tab triangle is disabled.
- Document generation button is blue with white text and no leftover gray gradient.
- Disabled/refused action buttons are gray with readable text and no blue focus frame.
- Destructive buttons are light red with red text and no blue focus frame.

## QA Artifacts

- `outputs/iform-qa/`
