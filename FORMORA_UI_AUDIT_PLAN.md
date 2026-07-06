# Formora UI Layout Audit And Remediation Plan

Date: 2026-07-06

Scope: Dolibarr 23 Formora theme layout audit after the current list-page redesign work. The audit covered regular back-office pages, POS-related pages, list pages, card/create forms, statistics pages, accounting pages, light mode, dark mode, mobile, tablet, and desktop widths.

Status: Audit plan with first two remediations completed.

Latest remediation update:

- The P0 Formora-only list filter overflow was fixed in `formora/style.css.php`.
- Verified no page-level horizontal overflow on the affected third-party and contact list pages in light and dark mode.
- Regression smoke covered 34 list/POS-related combinations, including mobile `390x844`, tablet `768x1024`, desktop `1440x900`, and POS partner list `1435x920`.
- The P1 mobile card/create form overflow was fixed in `formora/style.css.php`.
- Verified 42 card/create combinations and 36 list/POS regression combinations across mobile, tablet, desktop, light mode, and dark mode.

## 1. Audit Method

The browser audit visited 283 internal Dolibarr page templates collected from visible menus, left menus, title buttons, and several manual high-risk entries.

Each page was checked in six combinations:

- Light mode: `THEME_DARKMODEENABLED=0`
- Dark mode: `THEME_DARKMODEENABLED=2`
- Mobile: `390x844`
- Tablet: `768x1024`
- Desktop: `1440x900`

Total checks: 1698.

Clean checks: 1590.

Flagged checks: 108.

Browser/navigation errors: 0.

The audit checked for:

- Page-level horizontal overflow.
- Visible controls outside the viewport.
- Main content or `.fiche` being abnormally narrow.
- List tables not being contained by an internal scroll container.
- POS iframe/list pages reserving a hidden sidebar width.
- Duplicate sidebar separator lines.
- Mobile top menu scroll-state classes missing.

## 2. Executive Summary

The whole system is not broadly broken, but there are real layout issues in several families of pages.

Two issues are high priority because they are caused by the current Formora list-page work and do not reproduce in Eldy:

- Third-party list pages overflow at tablet width because a Select2 filter keeps a 300px width.
- Contact list pages overflow at desktop width because a Select2 filter keeps a 500px minimum width.

Several other issues also exist, but Eldy already has the same underlying layout behavior. Formora still should adapt them later, but they are not evidence that the current list-page redesign alone broke the whole system.

No audit flags were found for:

- POS partner list becoming narrow again.
- Sidebar double separator lines.
- Missing mobile top-menu scroll hint state.
- Ordinary invoice/order/proposal list tables escaping their intended internal scroll area.

## 3. Findings By Root Cause

### P0 - Formora List Filter Overflow

Status: Fixed and verified on 2026-07-06.

Affected pages:

- `/societe/list.php?mainmenu=companies&leftmenu=thirdparties`
- `/societe/list.php?contextpage=thirdpartylist`
- `/societe/list.php?mode=common&contextpage=thirdpartylist`
- `/societe/list.php?mode=kanban&contextpage=thirdpartylist`

Affected widths:

- Tablet `768px`

Affected modes:

- Light and dark

Observed result:

- Document width becomes `831px` on a `768px` viewport.
- First offending visible element is a Select2 container with width `300px`, positioned from about `530.9px` to `830.9px`.

Eldy comparison:

- Eldy does not overflow on the same page and viewport.

Likely cause:

- The current `.liste_titre_bydiv` responsive rules cap `max-width`, but do not fully defeat Dolibarr/Select2 `min-width` classes inside flex items.

Fix direction:

- Scope the fix to list filter bands only:
  - `body#mainbody.bodyforlist #id-right .fiche form#searchFormList > .liste_titre_bydiv`
  - `.divsearchfield`
  - `.select2-container`
  - visible filter inputs/selects/textareas
- Ensure filter flex items can shrink with `min-width: 0`.
- Convert fixed/min-width Select2 controls to `width: 100%` inside their filter cell while preserving useful minimums on wider desktop.
- Do not apply a global `.select2-container` override.

Validation:

- Recheck the third-party list at `390`, `768`, `1024`, `1280`, and `1440`.
- Verify both common and kanban URLs.
- Verify light and dark modes.

### P0 - Contact List Desktop Select2 Overflow

Status: Fixed and verified on 2026-07-06.

Affected pages:

- `/contact/list.php?leftmenu=contacts&type=c`
- `/contact/list.php?leftmenu=contacts&type=f`
- `/contact/list.php?leftmenu=contacts&type=o`
- `/contact/list.php?leftmenu=contacts&type=p`

Affected widths:

- Desktop `1440px`

Affected modes:

- Light and dark

Observed result:

- Document width becomes `1539px` to `1634px` on a `1440px` viewport.
- First offending visible element is `.select2-container.minwidth500`.

Eldy comparison:

- Eldy does not overflow on the same page and viewport.

Likely cause:

- Same root cause as the third-party list issue, but with `minwidth500` instead of 300px.

Fix direction:

- Fix together with the P0 list filter overflow.
- The rule must specifically allow oversized Select2 filters to shrink inside the list filter band.
- Confirm that desktop contact list filters remain usable and readable after shrinking.

Validation:

- Recheck all four contact list type variants at desktop `1440px`.
- Also check tablet `768px` and mobile `390px` to avoid moving the problem to smaller widths.

## 4. Eldy-Existing Issues To Adapt Later

These issues also reproduce in Eldy. They are still real UX problems, but they should be handled after the Formora-only regressions above.

### P1 - Mobile Card/Create Forms Overflow

Status: Fixed and verified on 2026-07-06.

Affected families:

- Third-party create: `/societe/card.php?action=create`
- POS third-party create: `/societe/card.php?action=create&type=t&contextpage=poslist&optioncss=print&place=0`
- Product/service create: `/product/card.php?action=create&type=0` and `type=1`
- Bank account create: `/compta/bank/card.php?action=create`
- Supplier order create: `/fourn/commande/card.php?action=create`
- Warehouse create: `/product/stock/card.php?action=create`

Observed examples at `390px`:

- Third-party create: document width about `551px`.
- Product create: document width about `457px`.
- Bank account create: document width about `408px`.
- Supplier order create: document width about `461px`.
- Warehouse create: document width about `479px`.

Likely cause:

- Dolibarr card forms use table-based field layouts (`table.border`, `tableforfieldcreate`, `centpercent`) and fixed/min-width form controls.

Fix direction:

- Create a separate mobile card-form adaptation layer.
- Scope to non-list card pages, not `bodyforlist`.
- At small widths, let table rows become stacked label/control blocks where safe.
- Reduce or override field/control minimum widths inside card form tables only.
- Avoid changing invoice/order line item tables until they are tested separately.

Validation:

- Mobile `390px` and tablet `768px`.
- Light and dark.
- Third-party, product, supplier order, bank account, warehouse.
- POS iframe third-party create.

### P1 - Special Desktop List-Like Pages Overflow

Affected pages:

- `/commande/list_det.php?leftmenu=orders`
- `/projet/tasks/time.php?leftmenu=tasks`

Observed at `1440px`:

- Order lines document width about `1702px`.
- Project task time document width about `1605px`.
- `#id-right` and `.fiche` themselves extend past the viewport.

Eldy comparison:

- Eldy also overflows on these pages.

Likely cause:

- These pages are list-like but do not carry the same `bodyforlist` class and therefore do not receive the current list containment rules.

Fix direction:

- Add a second, carefully scoped containment rule for known list-like pages:
  - `.page-list_det`
  - `.page-task_time`
  - Or other stable body classes found during implementation.
- Do not blindly target every `.div-table-responsive`.
- Preserve internal horizontal scroll for wide operational tables.

Validation:

- Desktop `1440px`, tablet `768px`, mobile `390px`.
- Order lines and project task time pages.
- Light and dark.

### P2 - Mobile Statistics Pages Slight Overflow

Affected families:

- Proposal statistics.
- Supplier proposal statistics.
- Customer invoice statistics.
- Supplier invoice statistics.
- Order statistics.
- Supplier order statistics.
- Expense report statistics.
- Field service statistics.
- Project statistics.
- Task statistics.

Observed at `390px`:

- Most pages report document width about `395px`.

Likely cause:

- Small fixed-width chart/table/stat containers.

Fix direction:

- Treat as low-risk polish after list and card issues.
- Add a narrow-screen stats wrapper constraint or internal scroll where needed.

Validation:

- Mobile `390px`.
- Light and dark.

### P2 - Accounting Journal Pages Overflow

Affected pages:

- `/accountancy/journal/bankjournal.php`
- `/accountancy/journal/expensereportsjournal.php`
- `/accountancy/journal/purchasesjournal.php`
- `/accountancy/journal/sellsjournal.php`
- `/accountancy/journal/variousjournal.php`

Observed:

- Mobile journal pages range roughly `436px` to `451px`.
- `variousjournal` is much wider, around `1073px`, and also overflows tablet.

Eldy comparison:

- Eldy also overflows on `variousjournal`.

Fix direction:

- Handle as a dedicated accounting form/table adaptation.
- Do not fold this into the generic card-form fix until the table structure is inspected.

Validation:

- Mobile and tablet.
- Light and dark.

### P2 - Margin Page Form Overflow

Affected page:

- `/margin/index.php?mainmenu=billing&leftmenu=&idmenu=84`

Observed:

- Mobile document width about `514px`.
- Tablet document width about `793px` on `768px`.

Likely cause:

- Form table and Select2 controls.

Fix direction:

- Revisit after the generic list filter and card-form fixes.
- It may be covered by the card/form adaptation if selectors are scoped well.

## 5. Non-Issues Confirmed In This Audit

### POS Partner List

The POS partner list did not reproduce the previous narrow-table bug after the latest fix.

Checked behavior:

- POS context list no longer reserves the hidden left sidebar width.
- `#id-right` uses the available iframe width.
- No POS context sidebar-visible flags were raised.

### Sidebar Separator

No double-sidebar-border flags were found across the matrix.

Current desired behavior:

- `.side-nav` draws the sidebar separator.
- `#id-container::before` does not also draw the same right border.

### Mobile Top Menu Scroll Hint

No missing top-menu scroll-state flags were found.

Expected classes:

- `formora-tmenu-is-scrollable`
- `formora-tmenu-can-scroll-left`
- `formora-tmenu-can-scroll-right`

### Regular Invoice/Order/Proposal Lists

Ordinary invoice, order, and proposal list pages did not reproduce the original full-width list overflow after the current redesign.

Remaining list problems are narrower and mostly tied to oversized filter controls.

## 6. Recommended Fix Order

1. Fix Formora-only list filter Select2 overflow.
   - Targets third-party list tablet and contact list desktop.
   - This should be the next code change.

2. Re-run the reduced regression matrix.
   - Third-party list.
   - Contact list type variants.
   - Invoice/order/proposal list smoke tests.
   - POS partner list.

3. Fix list-like pages not covered by `bodyforlist`.
   - `commande/list_det.php`
   - `projet/tasks/time.php`

4. Design and implement mobile card/create form adaptation.
   - Third-party, product, bank account, supplier order, warehouse.
   - Include POS third-party create iframe.

5. Fix accounting journal and margin pages.

6. Polish statistics pages mobile overflow.

## 7. Regression Matrix For The Next Fix

### Required Viewports

- `390x844`
- `768x1024`
- `1024x768`
- `1280x900`
- `1440x900`

### Required Modes

- Light: `THEME_DARKMODEENABLED=0`
- Dark: `THEME_DARKMODEENABLED=2`
- Always add `theme=formora&dol_resetcache=1&dol_nocache=1`

### Required Pages

- `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`
- `/societe/list.php?contextpage=thirdpartylist`
- `/societe/list.php?mode=common&contextpage=thirdpartylist`
- `/societe/list.php?mode=kanban&contextpage=thirdpartylist`
- `/contact/list.php?leftmenu=contacts&type=c`
- `/contact/list.php?leftmenu=contacts&type=f`
- `/contact/list.php?leftmenu=contacts&type=o`
- `/contact/list.php?leftmenu=contacts&type=p`
- `/compta/facture/list.php?leftmenu=customers_bills&mainmenu=billing`
- `/commande/list.php?leftmenu=orders&mainmenu=commercial`
- `/comm/propal/list.php?leftmenu=propals&mainmenu=commercial`
- `/societe/list.php?type=t&contextpage=poslist&nomassaction=1&place=0`

### Pass Criteria

- `document.documentElement.scrollWidth <= innerWidth + 2` unless the page intentionally scrolls internally.
- Visible filter controls must remain inside `.liste_titre_bydiv`.
- Table overflow must be inside `.div-table-responsive`, not on the whole page.
- POS context pages must not show or reserve `.side-nav` / `#id-left` width.
- No duplicate sidebar separator.
- Mobile top menu scroll hint classes still toggle correctly.

## 8. Implementation Guardrails

- Do not use global `overflow-x: hidden` to hide real layout problems.
- Do not globally override `.select2-container`, `table`, `input`, or `textarea`.
- Keep list fixes scoped to `body.bodyforlist form#searchFormList`.
- Keep card/create fixes separate from list fixes.
- Keep POS context fixes scoped to POS markers such as `contextpage=poslist`.
- Compare against Eldy before treating a page as a Formora regression.
- Run both light and dark checks for every fix.

## 9. Current Worktree Note

At the time of this plan, the only modified theme file is:

- `formora/style.css.php`

Static checks from the audit session:

- `php -l formora/style.css.php`: passed.
- `git diff --check`: passed.
