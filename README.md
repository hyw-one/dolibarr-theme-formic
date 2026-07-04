# Dolibarr Theme: iform-dolibarr

Eldy-based iForm skin for Dolibarr ERP/CRM 23.0.0.

## Install

Copy the directory:

```text
iform-dolibarr
```

to:

```text
/path/to/dolibarr/htdocs/theme/iform-dolibarr
```

Then select `iform-dolibarr` in Dolibarr theme settings, or test directly with:

```text
?theme=iform-dolibarr&dol_resetcache=1&dol_nocache=1
```

If an older `iform-dolibarr` folder exists, fully replace it instead of merging files.

## Approach

- Built from Dolibarr 23.0.0 Eldy theme files.
- Keeps Eldy's layout, spacing, menu widths, table behavior, pagination structure, tabs, cards, mobile breakpoints, and include structure.
- Uses its own theme id and resource path: `iform-dolibarr`.
- Changes are limited to iForm-oriented colors and light visual styling.
- Top-menu selected tabs keep Eldy's size but remove the selected-state triangle.
- Buttons keep Eldy's placement and dimensions while using distinct iForm states: blue primary, gray disabled/refused, and red destructive.

## Dark mode

Dolibarr 23 has an official dark-mode switch through `THEME_DARKMODEENABLED`:

- `0`: disabled.
- `1`: follow the browser or operating-system preference.
- `2`: always use the theme's dark mode.

The theme includes an iForm dark skin layer behind the official switch. It keeps Eldy's structure and adapts the iForm visual layer for dark backgrounds, navigation, side menu, list headers, rows, forms, select2 controls, tabs, cards, status badges, action buttons, destructive buttons, dialogs, tooltips, scrollbars, and jNotify toasts.

## Checked

- Customer invoice list.
- Customer invoice detail.
- Third-party/company index.
- Third-party card with destructive action.
- Document generation button.
- Long jNotify toast.
- Dark mode with `THEME_DARKMODEENABLED=1` and `THEME_DARKMODEENABLED=2`.
- Dark desktop checks for third-party list, invoice list, invoice card, and third-party card.
- Dark mobile invoice list check at 390 x 844.
- Dark refinement pass for top menu selected tabs, card tabs, status badges, and left-menu separators.
- Light refinement check for top menu selected tabs, status badges, and left-menu separators.
- Mobile 390 x 844 checks from the Eldy-based rebuild pass.
- Dark chrome pass: flattened card/admin tabs to a single underline (no boxed double border), removed the mismatched surface-color layer behind `.fiche`/`.tabBar` page wrappers so it blends with the page background, adapted `.side-nav`'s hardcoded light border/shadow, and reworked dark-mode button variables (title buttons, disabled/refused state, destructive buttons) to use the theme's own dark palette instead of the legacy ad hoc colors.
