# Formora UI Audit And Remediation Plan

Date: 2026-07-05

Scope: Dolibarr 23 Formora theme UI/frontend audit for light mode, dark mode, POS, regular back-office pages, modals, iframe dialogs, CKEditor, CSS editor, Select2, Colorbox, jQuery UI, toast, menus, tables, forms, buttons, badges, and icons.

Status: Audit only. No theme code has been changed as part of this plan.

## 1. Overall Conclusion

The current Formora theme has several user-visible UI issues that are not limited to isolated screenshots.

- Regular back-office mobile and narrow layouts are not correctly adapted. The desktop top navigation wraps into multiple rows and exposes the username/avatar area inside the menu grid.
- Several list pages have clipped table/filter/action content on mobile. Page-level `scrollWidth` can look normal while inner table content is already inaccessible.
- POS dark-mode Colorbox iframe dialogs do not consistently inherit dark-mode/theme parameters, producing dark shells with light iframe content.
- The served CSS does not fully match the current main-branch source for at least one recent mass-action rule.
- There are remaining high-risk CSS selectors, mode-boundary risks, and small but real code/config issues.

## 2. P0/P1 Required Fixes

| Priority | Area / URL | Affected Modes | Problem | Likely Cause | Recommended Fix Direction | Regression Risk |
| --- | --- | --- | --- | --- | --- | --- |
| P1 | All regular back-office pages, especially `/societe/list.php?leftmenu=thirdparties&mainmenu=companies` | Mobile, narrow desktop, light, dark | At 390px the desktop top menu expands into many rows. The `admin` username, avatar, and dropdown arrow are shown inside the menu block. The header consumes about 178px of vertical space. | `#id-top`, `#tmenu_tooltip`, and `li.tmenu` still use desktop layout rules on small screens. User menu height rules remain active. | Add mobile-specific top-nav rules scoped to `body#mainbody:not(.bodytakepos) #id-top`, `#tmenu_tooltip`, `#id-top li.tmenu`, `.login_block.usedropdown`, and the login/quick-add dropdown toggles. | High. Must verify desktop top menu, user dropdown, quick add, active menu state, and icon alignment. |
| P1 | Regular back-office pages at 820-1180px | Narrow desktop, embedded browser | Top menu already wraps into two rows around 820/900px. At 1030/1180px, left menu plus wide content creates horizontal overflow. | No clear tablet/narrow-desktop breakpoint strategy. | Add intermediate breakpoint behavior instead of relying only on very small mobile rules. | Medium-high. May affect normal desktop navigation density. |
| P1 | `/product/list.php`, `/societe/list.php`, `/compta/facture/list.php`, `/compta/bank/list.php` | Mobile list pages, light, dark | Tables, filter rows, title action areas, and some inputs extend far beyond the visible viewport. Some pages hide this as internal clipping rather than document-level horizontal scroll. | Desktop table widths and filter controls are preserved inside constrained containers. | Provide intentional horizontal scrolling or responsive list layout for `.bodyforlist #id-right .fiche`, `.bodyforlist table.liste`, `.liste_titre_filter`, and `.table-fiche-title`. | High. Do not globally override `table`, `input`, or `.select2`. |
| P1 | `/product/list.php?mainmenu=products` | Regular list pages | Mass-action Select2 remains visible when no row is selected, even though the local source contains a rule intended to hide it. | Served CSS does not include the current local bodyforlist mass-action rule, suggesting deployment/cache/path/conditional-output mismatch. | First verify the runtime theme file and generated CSS output. Then fix selector/cascade if still needed. | High. CSS fixes cannot be trusted until source/runtime mismatch is understood. |
| P1 | POS Colorbox iframe dialogs: customer list, invoice list, payment | POS dark mode, iframe dialogs | Colorbox shell is dark, but iframe content can load light because iframe URLs omit `theme=formora`, `THEME_DARKMODEENABLED=2`, and cache reset parameters. | POS dialog iframe URLs do not propagate theme/mode parameters. | Update POS dialog URL generation or theme parameter propagation. Verify both parent page and iframe document. | Medium-high. Could affect POS navigation, permissions, and cache behavior. |
| P1 | `/takepos/index.php?idmenu=65&mainmenu=takepos&leftmenu=` | POS mobile | Cart/user/topbar controls can sit outside the visible 390px viewport. | POS topbar remains desktop-horizontal on mobile. | Add POS-specific mobile topbar behavior scoped to `.bodytakepos`, separate from regular back-office rules. | High. POS desktop cashier workflow must not regress. |

## 3. P2/P3 Optimization Issues

| Priority | Area | Problem | Recommended Fix Direction |
| --- | --- | --- | --- |
| P2 | Mobile quick-add dropdown | Dropdown is slightly left-clipped and too tall, with weak scroll affordance. | Scope mobile positioning/max-height to `#topmenu-quickadd-dropdown`. |
| P2 | Mobile user dropdown | Menu does not strongly overflow, but it is attached below the broken multi-row header and feels visually misplaced. | Fix together with the mobile top-nav redesign. |
| P2 | Product list mobile title/action row | Title controls and action buttons compress or overflow at 390px. | Add responsive wrapping/stacking for list title action areas only. |
| P2 | CKEditor config | `['Source']` is immediately followed by `['SpecialChar']`, so the toolbar item is not configured as intended. | Fix the missing comma in `formora/ckeditor/config.js`. |
| P2 | Theme PHP config | `rbg(245,130,46)` typo remains. | Correct to `rgb(245,130,46)` in `formora/global.inc.php`. |
| P3 | Repository hygiene | `.DS_Store` exists in the worktree. | Remove and ensure ignore rules cover it. |

## 4. Page-Based Findings

### Dashboard

- URL: `/index.php?mainmenu=home&leftmenu=home`
- Issue: The same mobile/narrow top-nav wrapping behavior appears here.
- Severity: P1.
- Fix direction: Covered by the regular back-office top-nav mobile/narrow breakpoint fix.

### Products / Services

- URLs:
  - `/product/index.php?mainmenu=products&leftmenu=`
  - `/product/list.php?mainmenu=products`
- Issues:
  - Mobile list layout can produce page-level or internal horizontal overflow.
  - New/action controls can be offscreen at narrower widths.
  - Mass-action Select2 visibility does not match intended no-selection behavior.
- Severity: P1 for mass-action/runtime mismatch, P1/P2 for responsive layout.

### Third Parties

- URL: `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`
- Issues:
  - This page reproduces the screenshot problem: full desktop top menu wraps into multiple rows on mobile and exposes `admin` in the header grid.
  - List table/filter row remains far wider than the viewport.
- Severity: P1.

### Bank Accounts

- URL: `/compta/bank/list.php?mainmenu=bank&leftmenu=&search_status=opened`
- Issues:
  - Mobile table/filter controls extend outside the viewport.
  - At least one filter input can be positioned beyond visible bounds.
- Severity: P1/P2 depending on target mobile support level.

### Invoices

- URL: `/compta/facture/list.php`
- Issues:
  - Mobile list tables are too wide and can be clipped inside containers.
  - POS-source invoice list inside iframe inherits the POS iframe mode problem when opened from POS.
- Severity: P1.

### User Card

- URL: `/user/card.php?id=1`
- Issues:
  - Mobile header problem persists.
  - History/action tables are not fully responsive.
- Severity: P2.

### Theme CSS Editor

- URL: `/admin/ihm.php?mode=css&mainmenu=home&leftmenu=setup`
- Issues:
  - Mobile/narrow top-nav problem persists.
  - Editor-specific dark-mode cursor/line-number contrast should be rechecked after nav fixes.
- Severity: P2.

### CKEditor Test Page

- URL: `/admin/fckeditor.php?theme=formora`
- Issues:
  - Mobile/narrow top-nav problem persists.
  - Toolbar configuration has a syntax-valid but semantically wrong item boundary.
- Severity: P2.

### POS Main Interface

- URL: `/takepos/index.php?idmenu=65&mainmenu=takepos&leftmenu=`
- Issues:
  - Desktop POS is comparatively stable.
  - Mobile POS topbar/cart/user controls can be outside the visible viewport.
  - Dialog iframe URLs do not consistently inherit theme/dark-mode parameters.
- Severity: P1.

## 5. Component-Based Findings

### Top Navigation

- Regular back-office top navigation is the highest-risk component.
- Desktop-style menu items wrap into multiple rows at mobile and narrow widths.
- Username/avatar dropdown remains visible in the wrong layout context.
- Active, hover, and icon styling need regression checks after any breakpoint changes.

### Left Menu

- Interacts badly with wide content at narrow desktop widths.
- Must be tested with both short and long menus after the top-nav fix.

### Tables

- List tables preserve desktop width and can be clipped by parent containers.
- Internal overflow must be tested, not only `document.documentElement.scrollWidth`.
- Filters, checkboxes, sort arrows, badges, pagination, and empty states need mobile verification.

### Forms

- Filter inputs can extend out of view in mobile list pages.
- Date picker and disabled/readonly states still require targeted runtime verification.

### Select2

- Mass-action Select2 remains visible in a no-selection state in the current runtime.
- Hidden native selects appear to be offscreen without causing document scroll, but should remain part of regression checks.
- Do not globally override `.select2`.

### Buttons

- List title action buttons can be offscreen in narrow layouts.
- POS large buttons need separate mobile rules from regular `.button` or `.butAction` styles.

### Icons

- No sampled FontAwesome/mask icon became a square during the latest check.
- Top-nav icon sizing and color inheritance must be retested after mobile layout changes.

### Dropdowns

- User dropdown is visually tied to the broken top header.
- Quick-add dropdown can be clipped and too tall on mobile.

### Modal / Colorbox / iframe

- POS iframe dialogs are the most important mode-consistency problem.
- Parent Colorbox and iframe document must be checked independently.

### CKEditor

- Toolbar config has a concrete issue.
- Dialogs, tabs, inputs, selects, and toolbar dropdowns still need manual runtime verification.

### CSS Editor

- Needs focused verification for ACE/editor contrast, cursor visibility, toolbar alignment, and line-number colors.

### POS

- Mobile topbar and iframe dialogs require priority fixes.
- Desktop grid/category/product behavior should be preserved.

## 6. High-Risk CSS Selectors / Areas

- `body#mainbody #id-top`
- `body#mainbody #id-top li.tmenu`
- `body#mainbody #id-top li.tmenusel`
- `body#mainbody #id-top div.tmenucenter`
- `.login_block.usedropdown`
- `#topmenu-login-dropdown`
- `#topmenu-quickadd-dropdown`
- `body#mainbody.bodyforlist:has(.checkforselect)`
- `.bodyforlist table.liste`
- `.bodyforlist .liste_titre_filter`
- `.bodyforlist .table-fiche-title`
- `.bodytakepos` topbar/cart/customer/user controls

Avoid broad fixes such as global `button`, `.fa`, `.select2`, `table`, `input`, or blanket `overflow-x: hidden`.

## 7. Recommended Fix Order

1. Verify why served CSS does not include the current local bodyforlist mass-action rule.
2. Fix regular back-office mobile/narrow top navigation, including the username/avatar area.
3. Fix POS iframe URL theme/dark-mode propagation.
4. Fix mobile list/table/filter/action responsive behavior.
5. Fix POS mobile topbar/cart/user overflow.
6. Fix quick-add and user dropdown mobile positioning/scrolling.
7. Fix CKEditor toolbar config, `rbg` typo, and repository cleanup.

## 8. Required Regression Matrix

### Viewports

- 390px
- 768px
- 820px
- 900px
- 1030px
- 1180px
- 1280px
- 1440px

### Modes

- Light: `THEME_DARKMODEENABLED=0`
- Dark: `THEME_DARKMODEENABLED=2`
- Always add `dol_resetcache=1&dol_nocache=1`

### Pages

- `/index.php?mainmenu=home&leftmenu=home`
- `/product/index.php?mainmenu=products&leftmenu=`
- `/product/list.php?mainmenu=products`
- `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`
- `/compta/bank/list.php?mainmenu=bank&leftmenu=&search_status=opened`
- `/compta/facture/list.php`
- `/user/card.php?id=1`
- `/admin/ihm.php?mode=css&mainmenu=home&leftmenu=setup`
- `/admin/fckeditor.php?theme=formora`
- `/takepos/index.php?idmenu=65&mainmenu=takepos&leftmenu=`

### States

- Initial viewport
- Hover
- Focus
- Active/click
- User dropdown open
- Quick-add dropdown open
- Select2 open
- Mass action unchecked and checked
- Table row hover
- Form field focus
- Colorbox open
- iframe document loaded
- POS customer list
- POS invoice list
- POS payment dialog

### Technical Checks

- `document.documentElement.scrollWidth > document.documentElement.clientWidth`
- `document.body.scrollWidth > document.body.clientWidth`
- Inner table/container bounding boxes versus viewport
- Icon `::before` content/display/size/color/mask styles
- Hover before/after bounding boxes
- Hidden Select2/native select positions
- Dark-mode computed background/color/border-color
- Parent modal and iframe document styles independently

## 9. Mandatory UI Fix Verification Gate

This section is mandatory for every Formora UI/theme fix before commit. It was added after a regression where a selected-fields dropdown fix targeted the wrong class (`ul.ulselectedfields.open`) while the real Dolibarr runtime menu used `ul.selectedfieldsleft.open`, leaving dark-mode colors and clipping unfixed on BOM/list pages.

Do not mark a UI fix complete, and do not commit it, unless all applicable checks below have been executed against the synced test environment.

### Required Runtime Proof

- Reproduce the exact page, viewport, mode, and interaction state that exposed the issue.
- Open the real menu, dropdown, modal, iframe, Select2 panel, hover state, focus state, or selected state. Static CSS inspection alone is not enough.
- Capture the actual runtime DOM identity of the target element: tag, id, class list, and relevant parent chain.
- Verify proposed selectors with `element.matches()` or equivalent direct selector matching on the actual target element.
- Read computed style on the actual target element after the fix, including `background-color`, `color`, `border-color`, `box-shadow`, `z-index`, `display`, `position`, `overflow`, `width`, and `height` as applicable.
- Inspect parent overflow/clipping chain from the target element up to `body`, especially `table.liste`, `.div-table-responsive`, Colorbox containers, iframe bodies, and POS wrappers.
- Check both document-level horizontal overflow values: `document.documentElement.scrollWidth > clientWidth` and `document.body.scrollWidth > clientWidth`.
- Check target and ancestor bounding boxes against the viewport and relevant scroll containers.
- For hover/focus issues, verify the actual hover/focus target and compare computed styles before and after the state.

### Required Regression Scope

- Sync the local theme to the Dolibarr test environment before runtime verification.
- Re-test the exact original URL and state after syncing.
- Re-test at least one same-component page that uses a different Dolibarr DOM variant. For example, selected-fields dropdowns must be checked on both a standard list such as `/societe/list.php` and another module list such as `/bom/bom_list.php`.
- Re-test both light and dark modes when the fix touches color, shadows, borders, backgrounds, icon color, or contrast.
- Re-test mobile and desktop widths when the fix touches overflow, clipping, menus, table/list wrappers, pagination, title areas, or POS layout.
- Re-test parent and iframe documents separately for Colorbox/iframe fixes.

### Prohibited Shortcuts

- Do not treat "the CSS rule exists in generated CSS" as proof that a component is fixed.
- Do not treat CSS output order as proof unless the selector is shown to match the runtime element.
- Do not submit a UI fix based only on grep, curl, DOM source, or an unopened hidden element.
- Do not use a broad global selector to compensate for an unverified component selector.
- Do not call a state verified if automation failed to open or hover it. Say it is unverified and continue with a better runtime strategy.

### Commit Gate

Every UI fix commit summary should include:

- URL(s), mode(s), viewport(s), and interaction state(s) verified.
- Actual target selector and confirmation that it matched the runtime element.
- Key computed style values before/after or after-fix values.
- Overflow/clipping result.
- Same-component regression page(s).

## 10. Current Unknowns

- Toast success/warning/error/info states still need stable runtime triggers.
- jQuery UI date picker needs a concrete date field page to trigger and inspect.
- CKEditor dialogs need manual opening of link/image/table dialogs.
- The served-CSS mismatch for the mass-action rule must be diagnosed before trusting any further CSS-only conclusion.
