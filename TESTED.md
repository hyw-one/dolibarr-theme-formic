# iForm Theme Test Record

Date: 2026-07-04

Target: Dolibarr ERP/CRM 23.0.0

Theme id: `iform-dolibarr`

## Rename Pass

This pass finalizes the Eldy-based iForm theme under the theme id `iform-dolibarr`.

- Theme directory is now `iform-dolibarr`.
- `style.css.php` now uses `$theme = 'iform-dolibarr'`, so images and includes resolve under `/theme/iform-dolibarr/`.
- The install/test URL is now `?theme=iform-dolibarr`.
- The previous tab and button fixes are preserved.
- Dolibarr's official `THEME_DARKMODEENABLED` strategy was checked against Dolibarr 23 source.
- The iForm dark skin layer is included and follows the same official switch.

## Final Checks

- PHP syntax checks passed for `style.css.php`, `theme_vars.inc.php`, and `manifest.json.php`.
- CSS endpoint returned HTTP 200 for `/theme/iform-dolibarr/style.css.php`.
- Package root is `iform-dolibarr/`.
- Duplicate copied resources matching `* 2.*` count: 0.
- Top-menu selected tab triangle is disabled.
- Document generation button is blue with white text and no leftover gray gradient.
- Disabled/refused action buttons are gray with readable text and no blue focus frame.
- Destructive buttons are light red with red text and no blue focus frame.
- Dark mode CSS endpoint returned HTTP 200 with `THEME_DARKMODEENABLED=1`, using `@media (prefers-color-scheme: dark)`.
- Dark mode CSS endpoint returned HTTP 200 with `THEME_DARKMODEENABLED=2`, using a forced dark output block.
- Dark desktop checks passed for third-party list, invoice list, invoice card, and third-party card.
- Dark mobile invoice list check passed at 390 x 844.
- Dark page samples reported `color-scheme: dark`, iForm dark root variables, and 0 sampled light backgrounds.

## QA Artifacts

- `outputs/iform-dolibarr-qa/`
