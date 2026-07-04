# Dolibarr Theme: iform

Eldy-based iForm skin for Dolibarr ERP/CRM 23.0.0.

## Install

Copy the directory:

```text
iform
```

to:

```text
/path/to/dolibarr/htdocs/theme/iform
```

Then select `iform` in Dolibarr theme settings, or test directly with:

```text
?theme=iform&dol_resetcache=1&dol_nocache=1
```

If an older `iform` folder exists, fully replace it instead of merging files.

## Approach

- Built from Dolibarr 23.0.0 Eldy theme files.
- Keeps Eldy's layout, spacing, menu widths, table behavior, pagination structure, tabs, cards, mobile breakpoints, and include structure.
- Uses its own theme id and resource path: `iform`.
- Changes are limited to iForm-oriented colors and light visual styling.
- Top-menu selected tabs keep Eldy's size but remove the selected-state triangle.
- Buttons keep Eldy's placement and dimensions while using distinct iForm states: blue primary, gray disabled/refused, and red destructive.

## Checked

- Customer invoice list.
- Customer invoice detail.
- Third-party/company index.
- Third-party card with destructive action.
- Document generation button.
- Long jNotify toast.
- Mobile 390 x 844 checks from the Eldy-based rebuild pass.
