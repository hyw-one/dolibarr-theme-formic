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

The copied Eldy source keeps Dolibarr's official dark-mode hooks, but this iForm skin layer is currently tuned as a light palette. A dedicated iForm dark palette should be added as a separate tested pass before enabling dark mode for production users.

## Checked

- Customer invoice list.
- Customer invoice detail.
- Third-party/company index.
- Third-party card with destructive action.
- Document generation button.
- Long jNotify toast.
- Mobile 390 x 844 checks from the Eldy-based rebuild pass.
