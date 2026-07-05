# Formora List Layout Refactor Plan

## Objective

Rebuild the Dolibarr list-page layout rules in the Formora theme so title actions, mass actions, filters, wide tables, and responsive widths have clear ownership and no longer rely on overlapping patches.

This plan applies to regular Dolibarr list pages only:

- `body#mainbody.bodyforlist:not(.poslist)`
- `#id-right .fiche form#searchFormList`
- `table.table-fiche-title`
- `.liste_titre_bydiv`
- `.div-table-responsive > table.liste`

It must not change TakePOS pages, modal iframes, or non-list card pages unless a scoped selector explicitly targets a shared component inside a verified list context.

## Current Problem

Dolibarr's native list structure is simple:

- `table.table-fiche-title` owns the title row.
- `.col-center` owns mass actions.
- `.col-right` owns pagination, view mode buttons, and create buttons.
- `.liste_titre_bydiv` owns filters.
- `.div-table-responsive` owns wide-table horizontal scrolling.
- `table.liste` owns the real table width.

Formora currently mixes these responsibilities:

- The title toolbar is rewritten in multiple CSS blocks.
- Mass-action visibility is controlled by broad `:has(.checkforselect:checked)` rules.
- Filter and table width are synchronized by JavaScript but also overridden by CSS.
- `.fiche` width uses breakpoint-specific magic numbers.
- Desktop, narrow desktop, tablet, and mobile rules override each other in non-obvious order.

The result is fragile behavior: fixing an action button can reveal width gaps; fixing width can break desktop overflow; fixing mass actions can move Select2 arrows.

## Target Layout Contract

1. `.fiche` fills the right content area with only the theme's intended page gutters.
2. `table.table-fiche-title` is a viewport/content toolbar, not part of the wide-table scroll track.
3. `.col-right` always remains available for pagination, view mode, and create actions.
4. `.col-center:has(#massaction)` is a separate mass-action row when at least one row is checked.
5. The mass-action row never pushes the whole page horizontally.
6. `.liste_titre_bydiv` and `.div-table-responsive` share one horizontal table track.
7. Wide tables scroll inside the list area, not the document body.
8. Select2 arrows, dropdowns, and hidden native selects must not create offscreen width.
9. Breakpoints are explicit:
   - Wide desktop: `>=1181px`
   - Narrow desktop with side menu: `930px-1180px`
   - Collapsed/tablet: `768px-929px`
   - Mobile: `<=767px`
10. POS pages remain excluded.

## Refactor Phases

### Phase 1: Consolidate List Layout CSS

Goal: Move scattered list-title, mass-action, and width rules into one coherent list section.

Tasks:

- Identify all rules for `table.table-fiche-title`, `.col-picto`, `.col-title`, `.col-center`, `.col-right`, `#massaction`, and `#select2-massaction-container`.
- Remove older duplicate rules that are fully superseded by the final toolbar contract.
- Keep selector boundaries under `body#mainbody.bodyforlist:not(.poslist) #id-right .fiche`.
- Preserve existing non-list table and card-page styling.

Validation:

- CSS parse: `php -l formora/style.css.php`
- Whitespace: `git diff --check`
- No visual regression on customer invoice list at 1531, 1022, 951, 782, 390.

Regression risk:

- Old `@media max-width:767px` rules may still override the consolidated block.
- Duplicate mass-action hiding rules may hide the row unexpectedly.

### Phase 2: Rebuild Title Toolbar

Goal: Give the title toolbar stable grid behavior independent of table width.

Desktop grid:

- Row 1: `picto title actions`
- Row 2: `mass mass mass` only when checked

Mobile grid:

- Row 1: `picto title`
- Row 2: `actions actions`
- Row 3: `mass mass` only when checked

Rules:

- `.col-right` must remain visible whether checked or unchecked.
- `.col-right` may wrap internally, but individual icon buttons must not resize on hover.
- `.btnTitlePlus`, view mode buttons, and pagination controls must stay inside the toolbar.
- The title may wrap, but must not overlap actions.

Validation:

- Check checked and unchecked states.
- Verify plus button visible at 1531, 1022, 951, 782, 390.
- Verify no document/body overflow.
- Verify toolbar height is stable enough and does not jump excessively.

Regression risk:

- Pages with long translated titles can push actions if `minmax(0, auto)` is used incorrectly.

### Phase 3: Rebuild Mass Action Row

Goal: Make mass actions predictable and independent from `.col-right`.

Rules:

- Hidden when there are selectable rows but none checked.
- Visible when `.checkforselect:checked` exists.
- Select2 container uses a bounded width:
  - Desktop/narrow desktop: `min(360px, calc(100% - confirm-button-width - gap))`
  - Mobile: full available width minus confirm button, wrapping when necessary.
- Confirm button remains visible and usable.
- Select2 arrow is positioned relative to `.select2-selection`.
- Native hidden select must not create layout width.

Validation:

- Check mass-action row after selecting one row.
- Verify Select2 arrow inside selection.
- Open mass-action dropdown and verify it stays aligned.
- Verify no hidden select contributes to `scrollWidth`.

Regression risk:

- Select2 global styling can accidentally affect filter dropdowns.
- POS mass actions must remain excluded.

### Phase 4: Rebuild Filter/Table Scroll Track

Goal: Make `.liste_titre_bydiv` and `.div-table-responsive` move as one wide-table track without involving the title toolbar.

Rules:

- `form#searchFormList` can be the horizontal scroll owner only for filter+table content.
- `table.table-fiche-title` remains sticky/visible and uses the content width, not table width.
- `.liste_titre_bydiv` width equals the table track width.
- `.div-table-responsive` width equals the table track width inside the same scroll owner.
- The document body must not horizontally scroll.

Implementation options:

- Keep `syncListFilterTrackWidth()` but rename/document it around "filter table track".
- Measure table and filter widths after layout, mutation, resize, and table setting changes.
- Avoid setting the title toolbar width to the track width.

Validation:

- Product/service list, customer invoice list, third-party list, BOM list.
- Scroll horizontally and verify filter row and table columns remain aligned.
- Confirm title toolbar does not move with the table track.

Regression risk:

- Some Dolibarr lists use `div-table-responsive-no-min` or no filter row.
- Some pages insert bulk-action controls between title and filters.

### Phase 5: Normalize `.fiche` Width

Goal: Stop right-side gaps and document overflow by defining `.fiche` width per breakpoint.

Rules:

- Wide desktop keeps normal right gutter and avoids body overflow.
- `930px-1180px` fills the right side when the side menu is visible.
- `768px-929px` follows collapsed-menu layout.
- `<=767px` uses mobile full-width behavior.

Validation:

- `#id-right.right - .fiche.right` should be intentional:
  - Wide desktop: existing theme gutter is acceptable.
  - Narrow desktop: around single-digit px after margins.
  - Mobile/tablet: no large unused right gutter.
- No body/document horizontal overflow.

Regression risk:

- Left menu width can vary by theme config and language.
- Browser zoom and responsive mode can expose fractional rounding issues.

### Phase 6: Clean Select2 and Dropdown Side Effects

Goal: Ensure list toolbar changes do not break Select2, limit dropdowns, selected fields menus, or dark mode popovers.

Rules:

- Limit dropdown is scoped to `bodyforlist > .select2-container.limit.select2-container--open`.
- Mass-action Select2 is scoped to `#select2-massaction-container`.
- Filter Select2 remains inside `.liste_titre_bydiv`.
- Selected-fields dropdown dark mode and clipping are checked separately.

Validation:

- Open page-size dropdown.
- Open mass-action dropdown.
- Open selected-fields dropdown.
- Open filter Select2 dropdowns where available.
- Verify dark mode backgrounds, hover states, shadows, and clipping.

Regression risk:

- Select2 dropdowns are appended to `body`, so overly local selectors may not style open dropdowns.

### Phase 7: Cross-Page Regression Matrix

Must verify at least these pages:

- `/compta/facture/list.php?leftmenu=customers_bills`
- `/fourn/facture/list.php?leftmenu=suppliers_bills`
- `/product/list.php?mainmenu=products`
- `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`
- `/bom/bom_list.php?leftmenu=bom`
- `/compta/bank/list.php?mainmenu=bank&leftmenu=&search_status=opened`

For each page:

- Light mode: `THEME_DARKMODEENABLED=0`
- Dark mode: `THEME_DARKMODEENABLED=2`
- Cache reset: `theme=formora&dol_resetcache=1&dol_nocache=1`
- Widths: `1531`, `1022`, `951`, `782`, `390`
- States:
  - unchecked
  - one row checked
  - mass-action dropdown open
  - limit dropdown open
  - selected-fields dropdown open if present

Required browser checks:

- `document.documentElement.scrollWidth <= clientWidth`
- `document.body.scrollWidth <= clientWidth`
- plus/create button visible and inside title toolbar
- mass-action arrow inside Select2 selection
- filter/table track aligned
- no hover-induced layout shift for title action buttons

## Verification Commands

Run after every phase:

```sh
php -l formora/style.css.php
git diff --check
curl -I -s 'http://127.0.0.1:18090/theme/formora/style.css.php?theme=formora&THEME_DARKMODEENABLED=0&dol_resetcache=1&dol_nocache=1'
curl -I -s 'http://127.0.0.1:18090/theme/formora/style.css.php?theme=formora&THEME_DARKMODEENABLED=2&dol_resetcache=1&dol_nocache=1'
```

Sync before browser validation:

```sh
rsync -a formora/ /Users/andyhuang/Documents/Codex/2026-07-04/iform-dolibarr/work/dolibarr-23.0.0/htdocs/theme/formora/
```

## Commit Strategy

Use one commit per phase:

1. `docs: add list layout refactor plan`
2. `refactor: consolidate list layout rules`
3. `fix: stabilize list title toolbar`
4. `fix: stabilize list mass actions`
5. `fix: align list filter table track`
6. `fix: normalize list page widths`
7. `fix: harden list dropdown states`

Do not combine unrelated POS or modal fixes into these commits.

## Stop Conditions

Stop and reassess before committing if:

- A fix creates body/document horizontal overflow at any required width.
- A fix hides create/view/pagination controls in checked state.
- A fix moves mass-action Select2 arrows outside their selection box.
- A fix requires broad global selectors like `button`, `.select2`, `table`, `.fa`, or `input`.
- A fix improves customer invoices but breaks product, third-party, BOM, or supplier invoice lists.

