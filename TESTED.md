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
- Dark refinement checks passed for single-layer top-menu selected state, 1px status badges, single-layer invoice status pills, and subdued left-menu separators.
- Light refinement checks passed for the same top-menu, badge, and left-menu separator rules.

## QA Artifacts

- `outputs/iform-dolibarr-qa/`

## Dark Chrome Refinement Pass 2

- PHP syntax checks passed for `style.css.php` and `btn.inc.php`.
- Admin/card tabs (`.tab`, `.tabactive`, `.tabunactive`, `.tabsElem a`) no longer get a boxed border in dark mode; only the active tab shows a single bottom underline, matching Eldy's native tab shape.
- `.fiche`, `.fichecenter`, `.tabBar` and related page-wrapper selectors no longer force a distinct `--iform-surface` background in dark mode, so the page content blends with the body background instead of showing a separate panel layer.
- `.side-nav` (left sidebar wrapper) no longer keeps its native light `#ECECEC` border/`#eee` box-shadow in dark mode.
- `btn.inc.php` dark-mode variables (`--btncolortext`, `--btncolorbg`, `--btncolorborder`, `--butactionbg`, `--butactiondeletebg`, `--textbutaction`) now reuse the shared iForm dark palette instead of the previous mismatched/incomplete values; `.btnTitle.refused` no longer shows a light diagonal-stripe background in dark mode.

## Dark Chrome Refinement Pass 3

- Top-menu selected/hover tab (`#id-top li.tmenusel div.tmenucenter`) no longer stacks a full border plus an inset box-shadow line; kept the single bordered highlight and dropped the redundant shadow line, in both light and dark mode.
- Fixed remaining Eldy-native hardcoded light dividers that survived dark mode due to higher selector specificity + `!important`: `.trforbreak td`, `div.tableforcontact form.tagtr:last-of-type div.tagtd`, `.ui-mobile fieldset`, `.paginate_button`/`.paginate_active`, `table.jPicker`.
- Introduced dedicated `--iform-btn-primary-bg` (`#1f6feb`) / `--iform-btn-primary-hover-bg` (`#2568c7`) dark-mode variables so primary action buttons (`butAction`/`buttongen`) use white text on a darker blue, consistent with light mode's white-on-blue styling, instead of the previous lighter accent blue with black text. Contrast checked: `#1f6feb` with white text ≈ 4.6:1, hover `#2568c7` ≈ 5.4:1 (WCAG AA for normal text).

## Dark Chrome Refinement Pass 4

- Fixed a corner-miter artifact on the active card/admin tab (`.tabactive`): mixing a 1px transparent border on three sides with a 3px colored `border-bottom` made browsers render a mitered/notched trapezoid instead of a clean underline. Both the dark-mode override and light mode's native Eldy tab (which reuses the auto-computed top-menu contrast color, black on this theme's white top menu) now zero out the top/left/right border explicitly on the active tab, leaving a clean single bottom line in both modes.
- Confirmed the separate, longer divider below the tab row is `.tabBar`'s own native `border-top` (content-card top border, independent from the active tab's underline) — present in both light and dark mode by original Eldy design; left as-is per user's decision to keep it in both modes.

## Dark Chrome Refinement Pass 5

- Real DOM inspection (via browser devtools) showed the "podium" shape: Dolibarr's fiche-head tabs render as `<div class="tabsElem tabsElemActive"><div class="tab tabactive"><a class="tab ...">label</a></div></div>` — the active-tab border rule's selector list included both the wrapping `.tabactive` div and the nested `.tabsElem.tabsElemActive a` anchor, so both nested elements independently drew their own 3px colored bottom border, stacked with the div's own box, producing a stepped/podium silhouette. Split the rule: `color` still applies to both (so the anchor's own text color, which is set with `!important` by the base `.tab` rule, is correctly overridden), but the `border`/`box-shadow` treatment now applies only to `.tabactive` (the wrapping element), leaving a single clean underline.

## Dark Chrome Refinement Pass 6

- Tab layout stability: base tab rule now always reserves a 3px transparent bottom border (both light and dark mode) instead of switching between 1px-all-sides (inactive) and 0/3px (active); only `border-bottom-color` changes between states, so switching selection/hover no longer shifts tab position.
- Redesigned primary action buttons (`butAction`/`buttongen`, not delete/refused) in dark mode from a solid opaque fill to the same translucent tinted-background + soft border "chip" treatment already used by `.butActionDelete`/`.butActionRefused`/status badges, for visual consistency across all action buttons. Removed the now-unused `--iform-btn-primary-bg`/`--iform-btn-primary-hover-bg` variables; `btn.inc.php`'s fallback vars updated to match.
- Dark-adapted the user-menu dropdown's `.user-header` (avatar/name block) and `.user-footer`/`.bookmark-footer` (button row), which were previously left at their native light `#f9f9f9` background — only the middle `.user-body` section was covered before.
- Neutralized several native light box-shadows that had no dark override: `div.fiche div.info/warning/neutral` (message banners), `.firstcolumn/.secondcolumn table.noborder` (dashboard box shadow option), `.shadow`/`.boxshadow` utility classes, and `table.jPicker` (color picker popup).
- Confirmed the blank pill in a linked-objects mini-table's status column is Dolibarr core's label-less/tooltip-only status display (hover shows the label), not a theme bug. Since a labelled-badge's padding collapses to a flat bar with no text, added an unconditional (light + dark) `:empty` rule so any label-less status badge renders as a small round dot instead.

## Dark Chrome Refinement Pass 7

- User-menu dropdown footer buttons (`.user-footer .button-top-menu-dropdown`, e.g. the round icon buttons in the avatar dropdown) and generic `.dropdown-item` entries (used by bookmark/quick-create/search dropdown lists) were still using native hardcoded light colors (`#f4f4f4`/`#ddd` background/border, `#212529` text, white `#fff` hover background) with no dark override at all; added dark-mode coverage for both the resting and `:hover`/`.active`/`:focus` states.
- Fixed a second layout-shift bug, same root cause as the tab underline: the top-menu selected/hover pill (`#id-top li.tmenusel div.tmenucenter` / `:hover`) only got a `border: 1px solid` when selected/hovered, with nothing reserving that space beforehand — so hovering added 2px to the box height and visibly pushed the icon+label down. Both light and dark mode now always reserve a 1px transparent border on every top-menu item; hover/selected states only swap the border color, not the width.

## Dark Chrome Refinement Pass 8

- `.info-box` (home dashboard stat cards) had a native `box-shadow: 1px 1px 20px rgba(192, 192, 192, 0.2)` with no dark override — showed as a bright halo around each stat card. Added `box-shadow: none !important` for `.info-box`/`.box`/`.boxstats`.
- Root-caused the "empty but colored" dashboard blocks via live DOM inspection: the actual element was `div.box.divboxtable.boxdraggable`, sized exactly 40px tall (`.box`'s native `min-height: 40px`). `.box` and `.box-flex-item` are pure structural/drag-and-drop layout wrappers with **no native background of their own** (transparent by design — the real dashboard cards like `.boxstats`/`.info-box` already carry their own background). Our surface-background bucket had included `.box`/`.box-flex-item` alongside real card elements, forcibly painting these normally-invisible structural spacers with the dark surface color, which is why they only appeared in dark mode. Removed `.box`/`.box-flex-item` from that rule (same root cause and fix pattern as the earlier `.fiche`/`.tabBar` surface-layer bug).

## Dark Chrome Refinement Pass 9

- Live DOM inspection showed select2 single-selection dropdowns' rounded corners were being visually covered: the outer `span.select2-selection--single` (200×28px, has the rounded border) contains an inner `span.select2-selection__rendered` (198×28px — effectively the same box) which our CSS had lumped into the same rule as the select2 results popup, giving it its own opaque `background: var(--iform-surface)` with no border-radius of its own. That inner span's square corners rendered on top of and hid the outer container's rounded corners. Split the rule: the single-selection rendered label now only gets `color`/`background: transparent`, while the actual floating results-list popup (`.select2-results` and friends) keeps its own surface background.

## Dark Chrome Refinement Pass 10

- Same root cause as the earlier `.fiche`/`.tabBar` and `.box`/`.box-flex-item` surface-layer bugs, third occurrence: `.div-table-responsive`/`.div-table-responsive-no-min` (the wrapper Dolibarr puts around most list/line-item tables, e.g. `div.fiche>div.tabBar>form>div.div-table-responsive`) natively has no background of its own and carries a `min-height` (392px for full-page tables) purely to keep short tables from looking cramped. Our surface-background bucket had forced a `var(--iform-surface)` background onto this wrapper too, so whenever the actual table content (rows + total row) was shorter than the reserved min-height, the leftover reserved space showed up as a visibly different-colored panel below the real content — this is the "shadow"/colored patch the user saw under the totals row and generally under table blocks. Removed `.div-table-responsive`/`.div-table-responsive-no-min` from the forced-background rule; the actual table/rows already carry their own background via a separate rule and are unaffected.

## Dark Chrome Refinement Pass 11

- Fourth occurrence of the same surface-layer bug, this time on `table.border`/`.border` (the plain key-value table Dolibarr uses for simple create/edit forms, e.g. "新建发票"). Confirmed via code: native Eldy only gives `table.noborder` (list-style tables) its own background, via `background: var(--colorbacktabcard1)` — a CSS variable that's already correctly dark-adapted (`--colorbacktabcard1: #1c1f22` in our dark `:root`). `table.border` has no native background at all — it's meant to stay transparent and blend with the (already-fixed, transparent) `.fiche` page. Our dark bucket had lumped `table.border`/`.border` in with `table.noborder`/`.liste` and forced the same surface background onto both, which is redundant for `.noborder` (same color either way) but wrong for `.border` — it's what painted the whole create-invoice form as a big block distinct from the page background. Split the rule: `table.noborder`/`.liste`/etc keep the surface background; `table.border`/`.border` now get `background: transparent` while keeping their border-color/text-color dark adaptation.

## Dark Chrome Refinement Pass 12 — full audit

Instead of continuing to react one screenshot at a time, grepped every hardcoded light `background`/`background-color` (`#fff`, `#eee`, `#f4f4f4`, etc.) across `global.inc.php`, `dropdown.inc.php`, `badges.inc.php`, `info-box.inc.php`, `btn.inc.php`, `timeline.inc.php`, `progress.inc.php` and cross-checked each against dark-mode coverage. Fixed the confirmed high-visibility misses (skipped niche/rarely-used module widgets like kanban board colors, iCal rendering, CKEditor dialog chrome):

- `.dropdown-search`, `.dropdown-bookmark`, `.dropdown-quickadd` (topmenu quick-search/bookmark/quickadd panels) and `.dropdown-content` (generic "more actions" dropdown attached to buttons) all had native `background: #fff` with light borders/shadows and zero dark coverage.
- `.dropdown-body::-webkit-scrollbar-thumb` used a hardcoded `#aaa` regardless of mode.
- `.divadvancedsearchfieldcompinput` (advanced search composite field), `.integrated-radio` (toggle-style radio buttons), `.flex-item-uploadfile` (drag-and-drop upload zone), user-avatar placeholders (`span.userimg div.userphoto`, `img.userphotosmall`, Gravatar fallback), and disabled pagination links/spans were all hardcoded light with no dark override.
- Note: the empty highlighted row seen in a select2 dropdown (below the search box, above the real results) is very likely Dolibarr auto-highlighting a blank "no selection" placeholder option — its highlight color is already correctly dark-adapted via our existing `.select2-results__option--highlighted` rule, it just has no visible label. Flagged to the user as probably not a color bug, pending confirmation.

## Local test environment

Set up a real local Dolibarr 23.0.0 instance (PHP 8.5 built-in server + local MySQL, `.claude/launch.json` config `dolibarr-test`, code under the session scratchpad, theme copied into `htdocs/theme/iform-dolibarr`) to verify fixes directly via the browser preview tools instead of relying solely on user screenshots. Confirmed via live inspection: select2 dropdown box-shadow fix, admin tab underline (no podium shape), top-menu hover (no layout shift), third-party card action buttons (chip-style primary/danger colors match computed values), user-menu dropdown (fully dark), dashboard boxes/info-box shadow (no stray colored panels).

## Dark Chrome Refinement Pass 13

- Root-caused via live testing (forcing a table to overflow inside a dashboard box) the "short strip glued to the bottom of dashboard box widgets" the user reported: `.box`/`.boxtable` carry native `overflow-x: auto`, and when real (longer) content in a box's table causes horizontal overflow, a scrollbar renders across the box's bottom edge. Our existing `::-webkit-scrollbar` rules apply an inconsistent look here; added the standard `scrollbar-color` property (Firefox and modern Chromium both honor it, sometimes taking precedence over `::-webkit-scrollbar`) and specifically made `.box`/`.boxtable`'s scrollbar track transparent (only the thumb shows a muted grey when actually scrollable), so the track no longer renders as a mismatched pale strip when a box's content is close to or exceeds its column width.

## Dark Chrome Refinement Pass 14

- User pointed at the real culprit via the live picker: empty dashboard grid-placeholder divs, e.g. `<div class="box divboxtable boxdraggable" id="boxto_B"><br></div>` — these are Dolibarr's drag-and-drop dashboard padding out an uneven widget grid, with no real content at all. Our `.box`/`.boxtable`/`.info-box` box-shadow rule (added in Pass 8/9) was still applying to bare `.box`, so every one of these empty placeholders rendered as a floating "ghost card" with a shadow but nothing inside. Moved the shadow from `.box` to `.boxtable` (the actual `<table>` that only exists when a box has real content) and set `.box` itself to `box-shadow: none !important`. Verified directly: injected the exact empty `<div class="box divboxtable boxdraggable"><br></div>` markup into the live test page — computed `box-shadow` is now `none` and it's fully invisible, while a real box's `.boxtable` still shows the shadow.

## Full light/dark sweep (Pass 15)

Did a systematic pass across both color schemes (`preview_resize colorScheme: light/dark`, `THEME_DARKMODEENABLED=1` follow-browser) over: dashboard, admin theme settings (all 5 sub-tabs: language, skin/colors, kanban, login page, CSS style), third-party list/card (tabs, action buttons, badges), invoice create form, select2 single-select dropdown. All of the previously-fixed areas held up correctly in both modes with no regressions.

Found and fixed two previously-untouched third-party JS widgets that had **zero** dark-mode coverage:

- **CKEditor** (rich text fields, e.g. login-page message, "今日消息"): the toolbar/chrome (`.cke_chrome`, `.cke_top`, `.cke_bottom`, buttons, format/font combos, path bar) is normal DOM and is now themed via new rules in `style.css.php`. The **editable content area is a same-origin iframe** with its own document — parent-page CSS cannot reach into it, so `ckeditor/config.js` now detects dark mode via `getComputedStyle(document.documentElement).colorScheme === 'dark'` at editor-init time and adds a new `ckeditor/dark-contents.css` stylesheet (via `config.contentsCss`) that darkens the iframe's `html`/`body`/links/tables/blockquotes. Verified via direct DOM inspection (`iframe.contentDocument`, computed styles) that both the chrome and the iframe body report the correct dark colors.
- **ACE editor** (the raw-CSS textarea under 主题 → CSS 样式): completely unstyled — white background, light-grey line-number gutter. Added dark rules for `.ace_editor`/`.ace_scroller` (background/text), `.ace_gutter` (gutter background/text), `.ace_cursor`, `.ace_active-line`, `.ace_selection`. Verified via computed styles that all report the correct dark values.

Tooling caveat: for both CKEditor's chrome and the ACE gutter, `preview_screenshot` intermittently rendered a stale/white frame even though `getComputedStyle` on the live DOM confirmed the correct dark colors were applied (confirmed reproducible across reloads/resizes) — likely a compositing/paint-timing quirk of the screenshot tool with these specific widgets, not a CSS bug. A second CKEditor instance on a different page (kanban tab's "今日消息" editor) *did* screenshot correctly dark, which supports this being a one-off rendering glitch rather than a real defect.

**Reverted per user decision**: user wants CKEditor and the ACE CSS-code editor to keep their native/original (light) appearance rather than being dark-adapted, even under the theme's dark mode. Removed the `.cke_*`/`.ace_*` dark rules from `style.css.php`, reverted `ckeditor/config.js`'s dark-mode `contentsCss` detection, and deleted `ckeditor/dark-contents.css`. Verified via computed styles that both widgets are back to their original white/light-grey appearance regardless of dark mode.

## Dark Chrome Refinement Pass 16

- Top-menu bottom border was invisible under the top-right icon cluster (print/help/version/admin dropdown): `.login_block` is `position: absolute`, `height: 51px` (content-box) — exactly matching `#id-top`'s full outer height including its `border-bottom: 1px`. Since children paint over their ancestor's border in normal stacking order, `.login_block`'s own opaque background covered that 1px border row for the width it occupies, making the border look like it "stopped" before reaching the right edge. Added `border-bottom: 1px solid var(--iform-line-soft) !important` directly on `.login_block` so it draws its own matching border segment instead of relying on (and covering) the parent's.
- jNotify toast used the plugin's native `position: fixed; top: 0`, meaning it renders starting at the very top of the viewport — directly overlapping the fixed top-menu bar and its icon cluster, which produced the "grey frame around the icons" look the user saw (the toast's own bordered box spatially overlapping the persistent top-right icons before it faded out). Reproduced by calling `jQuery.jnotify(msg, "ok", true, {remove:function(){}})` directly (bypasses the plugin's auto-hide so it can be inspected at leisure) to confirm the notification box's own border (`1px solid var(--iform-line)`) and position. Added `top: 60px !important` to `.jnotify-container` so toasts render below the top menu instead of overlapping it.

## Dark Chrome Refinement Pass 17

- Fixed a clipped-shadow artifact below the toast: `.jnotify-container` has native `overflow: hidden auto` and sizes itself tightly to just the notification's own box (no extra buffer for shadow blur). Our dark-mode shadow was `0 14px 34px rgba(0,0,0,0.42)` — a shadow that needs ~48px of space below the notification to render its full soft gradient, but the container only reserves the notification's own height, so the gradient got hard-clipped partway through, leaving a thin flat-colored band that doesn't match either the toast or the page background. Reduced to `0 4px 14px rgba(0,0,0,0.4)`, small enough to render fully within the tight container.
- Fixed all jNotify types rendering identically: our `.jnotify-background`/`.jnotify-message` dark rules used a 2-class selector (`.jnotify-notification-warning .jnotify-background`), but the plugin's own native per-type rule (`.jnotify-container .jnotify-notification-warning .jnotify-background`) is 3 classes — higher specificity wins regardless of source order, so every toast fell back to the plugin's native (light-mode-only) warning/error colors. Diagnosed by walking `document.styleSheets` (recursively through `@media` blocks — an initial non-recursive check falsely suggested our rules weren't loaded at all) to compare the actual competing selectors/specificity. Prefixed our rules with `.jnotify-container` to match, and gave each type its own tint reusing the theme's established chip palette: green for `ok`, amber for `warning`, red for `error`. Verified via computed styles that all three now render distinct dark-appropriate colors.

## Dark Chrome Refinement Pass 18

- Company logo in the top menu was getting the same hover-highlight box as real navigation tabs. Found that Dolibarr's own markup already flags the logo `<li>` as `class="tmenu tmenucompanylogo nohover"` — a `nohover` marker specifically meant to opt out of hover styling — but our `#id-top li.tmenu:hover ...` rules (12 occurrences across light/dark blocks) never checked for that class, so the highlight box applied anyway. Changed all of them to `li.tmenu:not(.nohover):hover`. Verified via `element.matches('li.tmenu:not(.nohover)')`: the logo (`class="... nohover"`) no longer matches so its hover rule can never fire, while a real tab like 主页 (`#mainmenutd_home`, no `nohover` class) still matches and keeps its hover highlight.

## Dark Status Badge Refinement Pass 19

- Root-caused the sales-order list's dark-mode "已交付" badge color mismatch: Dolibarr renders delivered customer orders as `status6`, and `theme_vars.inc.php` defines `badgeStatus6` as neutral grey, but the dark-mode override had grouped `status6` with the amber/warning statuses.
- Realigned the dark-mode status groups with the theme variables: neutral `0/2/5/6/9`, warning `1/1b/3`, success `4/4b/7/11`, danger `8/10`. This returns "已交付" to a grey badge while keeping "发货单已创建" (`status4`) green.
- PHP syntax check passed for `style.css.php`.

## Dark File Preview Refinement Pass 20

- File preview thumbnails use Dolibarr's `.documentpreview` class around previewable media. The dark-mode upload/file bucket had treated `.documentpreview` like a regular dark panel, which made transparent PDF/image preview backgrounds inherit the dark surface color.
- Removed `.documentpreview` from the generic dark upload/file container rule and added a targeted white canvas only on the preview media itself (`.documentpreview img/object` and `modulepart=apercu...` document thumbnail images), so file content keeps its native light paper/background while the surrounding thumbnail slot stays dark.
- PHP syntax check passed for `style.css.php`.

## Dark Tooltip Refinement Pass 21

- Product hover/freeze tooltips still showed a bright light border in dark mode because `global.inc.php` sets `div.ui-tooltip.mytooltip { border: 1px solid #bbb !important; }` and `tooltips.inc.css` sets `div.ui-tooltip.mytooltip-hover-tooltip { border: solid 1px #BBBBBB; box-shadow: 0 0 4px grey; }`, both more specific than the generic dark `.mytooltip` bucket.
- Added explicit dark overrides for `div.ui-tooltip.mytooltip` and `div.ui-tooltip.mytooltip-hover-tooltip` so tooltip panels use the iForm dark surface, subdued dark border, dark shadow, and dark text color.
- PHP syntax check passed for `style.css.php`.

## Dark Datepicker Refinement Pass 22

- Datepicker popups still had a bright halo in dark mode because Eldy's base `global.inc.php` sets `div#ui-datepicker-div { box-shadow: 2px 5px 15px #aaa; }`.
- Added a dark-mode override for `div#ui-datepicker-div` / `.ui-datepicker.ui-widget.ui-widget-content` so the popup keeps the dark surface and border while using a black translucent shadow instead of the native light grey shadow.
- PHP syntax check passed for `style.css.php`.

## Dark Third-Party Form Refinement Pass 23

- The third-party create form's customer/prospect/supplier selector is rendered as `span.spannature` chips (`prospect-back`, `customer-back`, `vendor-back`) with JS swapping unchecked chips to `nonature-back`; the native light `nonature-back` surface made unchecked options appear as bright white pills in dark mode.
- Added dark-mode chip styling for `spannature` / `nonature-back`, semantic dark tints for selected prospect/customer/vendor/user/member chips, and an explicit unchecked fallback using `:has(input:not(:checked))` so the initial pre-JS class state cannot flash a light chip.
- Added scoped dark-mode icon overrides for field-prefix Font Awesome icons inside `table.border` forms and for contact/address icons (`phone`, `mobile`, `fax`, `at`, `globe/map`, info), plus a muted filter for direct `img.pictofixedwidth` form icons.
- PHP syntax check passed for `style.css.php`.

## Dark Quick-Add/Search Menu Refinement Pass 24

- The top quick-add dropdown uses `a.dropdown-item.quickadd-item` rows and the global-search dropdown uses `button.dropdown-item.global-search-item` rows; both render their icons through `img_picto()`, which already carries semantic object colors in the generated span/classes.
- Removed the overly broad dark topbar icon coloring from dropdown descendants and scoped the hover/focus fix to quick-add plus global-search rows, so menu text stays readable while icon pseudo-elements inherit the original `img_picto()` semantic color instead of being forced to black or white.
- PHP syntax check passed for `style.css.php`.

## Dark Icon Refinement Pass 25

- Eldy's native dropdown hover selector applies both `color` and `background` to `.dropdown-item:hover span::before`, which could leave a light block behind a quick-add/search icon even after the icon color itself was preserved. Added scoped background/border/shadow resets for those dropdown icon spans and pseudo-elements so hover only changes the row, not the icon canvas.
- Reworked the third-party form icon override so field prefix icons are no longer all forced to `--iform-muted`; common icons now keep or receive dark-friendly semantic colors by category (company/contact purple, location neutral, phone/fax amber, website/email violet, product/barcode ochre, user brown).
- PHP syntax check passed for `style.css.php`.

## Dark Module Card Refinement Pass 26

- The module administration page's enabled module cards use `.info-box-sm .info-box-module-enabled`, whose background comes from `--infoboxmoduleenabledbgcolor`; Dolibarr's native dark-mode value was a gradient with three pure-black stops (`#000, #000, #000`) and only a green tail.
- Overrode `--infoboxmoduleenabledbgcolor` in the iForm dark root with a dark-surface gradient plus a subtle green status tint, and added scoped enabled-module card rules for border and text/description contrast so enabled modules read as active without becoming black blocks.
- PHP syntax check passed for `style.css.php`.

## Dark Icon Audit Pass 27

- Confirmed the browser test server on `127.0.0.1:18090` was serving a different Dolibarr checkout than the repo workspace: active PHP cwd was `/Users/andyhuang/Documents/Codex/2026-07-04/iform-dolibarr/work/dolibarr-23.0.0/htdocs`. Synced the latest repo theme into that running `htdocs/theme/iform-dolibarr` directory before browser verification.
- Added dark-mode coverage for remaining low-contrast semantic icons found by automated viewport scanning: list/table phone/mobile/fax icons, module-card and standalone `.pictomodule` portal/web/social icons, holiday/phone/business-time/print/cogs module icons, member/expense icons, and warning pictos. Colors stay semantic (amber/purple/neutral/brown), not forced white.
- Raised dark-mode opacity only for Font Awesome icons using Dolibarr's very low-opacity helpers (`.opacityhigh`, module toggle `.opacitymedium/.opacitymediumonly`) so filter/toggle icons remain readable on dark surfaces while preserving their muted state.
- Re-ran browser scans in the live test environment with computed-color contrast checks on these pages: module administration (8 scroll positions), contact list, third-party list, third-party create, contact create, product list, product create, sales-order list, customer-invoice list, admin setup, admin menus, admin tools. No visible low-contrast icon candidates remained in the scanned viewports.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Dark List Border Refinement Pass 28

- User noticed that list/table borders looked inconsistent between the top filter block and the lower table rows on the quotation list page. Live computed-style inspection showed `div.liste_titre_bydiv` used `--iform-line` (`rgb(58, 64, 72)`) while the real table borders and cell separators used `--iform-line-soft` (`rgb(44, 50, 56)`).
- Added a dark-mode override for `div.liste_titre_bydiv` / `div.liste_titre_bydiv_nothingafter` so the top rounded filter frame uses the same soft border color as `table.liste`, table cells, and the empty-row separator.
- Verified on `/comm/propal/list.php?leftmenu=propals` that the top frame border, table side borders, header separator, and "没有找到记录" row separator all compute to `rgb(44, 50, 56)`.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Sidebar Border Refinement Pass 29

- User noticed a near-overlapping double vertical line at the right edge of the left sidebar in both light and dark mode. Live DOM inspection showed `.side-nav` and its child `#id-left` were both painting a right border one pixel apart (`.side-nav` at x=261/262 depending viewport, `#id-left` just inside it).
- Removed the inner `#id-left` side borders and kept `.side-nav` as the single sidebar/content separator. This is intentionally outside the dark-mode block so the duplicate line is removed in both light and dark mode.
- Verified on `/comm/propal/list.php?leftmenu=propals` that `#id-left` computes to `0px` left/right borders while `.side-nav` keeps the single right border.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Dark Dialog Close Button Refinement Pass 30

- User noticed the overlay/dialog close button stayed as a light jQuery UI button in dark mode. Live inspection showed the close button is `button.ui-dialog-titlebar-close` with a nested sprite icon `.ui-icon-closethick`, inheriting native jQuery UI `rgb(246, 246, 246)` background and `rgb(197, 197, 197)` border.
- Added a scoped dark-mode override for `.ui-dialog .ui-dialog-titlebar-close` only, covering resting, hover, focus, and jQuery UI `ui-state-hover` / `ui-state-focus` states. The rule does not target ordinary `.ui-dialog button` confirmation buttons.
- Added a scoped brightness adjustment for the nested `.ui-icon-closethick` sprite so the close glyph remains visible on the dark button without changing PDF/preview content.
- Verified on an invoice dialog that the close button computes to dark background/border (`rgb(36, 40, 45)` / `rgb(58, 64, 72)`) and the close sprite has `filter: brightness(1.85)`.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Dark Dialog Buttonpane Refinement Pass 31

- User noticed the confirmation overlay's footer buttons ("是" / "否") still used native light jQuery UI button styling after the close button fix.
- Added a scoped dark-mode override for `.ui-dialog .ui-dialog-buttonpane .ui-button`, including hover/focus and jQuery UI state classes, so dialog footer buttons use the dark surface, border, text, and focus ring without changing ordinary page buttons outside dialogs.
- Verified on the invoice validation dialog that both footer buttons compute to dark background/border (`rgb(36, 40, 45)` / `rgb(58, 64, 72)`) with light text (`rgb(237, 242, 247)`).
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Dark Payment Autofill Icon Refinement Pass 32

- User noticed the arrow icon next to the payment amount input was still black on the dark payment form. Source inspection showed the payment page renders this as a `.AutoFillAmount` button containing `img_picto("Auto fill", "rightarrow.png")`, so the existing Font Awesome dark icon rules did not apply.
- Added a scoped dark-mode filter for `.AutoFillAmount img` / `rightarrow.png`, tinting the black PNG to the same blue accent used by the button, with a slightly brighter hover/focus state.
- Verified on `/compta/paiement.php?facid=12&action=create` that the autofill arrow computes to the new filter and the parent `.AutoFillAmount` color is `rgb(78, 163, 255)`.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Light/Dark Coverage Audit Pass 33

- Synced the repo theme into the active Dolibarr test server directory (`htdocs/theme/iform-dolibarr`) and verified the browser was loading fresh `style.css.php` revisions with explicit `THEME_DARKMODEENABLED=2` and `THEME_DARKMODEENABLED=0` URLs.
- Ran a safe-open browser audit across 55 pages covering home/setup/admin tools, module administration, users, third parties, contacts, products/services, quotations, sales orders, shipments, contracts, customer invoices, payment entry, bank/cash, VAT, supplier lists, projects, expenses, holidays, members, tickets, create forms, detail pages, and the invoice confirmation dialog. Destructive submits, deletes, validations, enables/disables, document generation, and saves were not executed.
- Fixed remaining dark-mode findings from the audit: `object_calendarday.png`, `edit_add.png`, and semantic Font Awesome icons for phone, email, external link, link, clock, and language. Icons keep category colors instead of being forced white. Also replaced the remaining jQuery UI dialog light-grey shadow with a dark shadow.
- Fixed light-mode low-contrast icon findings found during the same audit by deepening neutral view-mode/edit/location icons, bank/payment icons, contract/service icons, RSS, and lock icons while preserving their semantic color families.
- Opened hidden component states in both modes: left-menu select2 search dropdown, datepicker popup, user dropdown, and invoice confirmation dialog. The current test build did not render a top quick-add plus entry, so that specific menu could not be opened in this environment.
- Final automated computed-style audits found no actionable bright surfaces, bright white shadows, low-contrast icons/text, or unadapted theme PNG icons in either light or dark mode. Ignored acceptable non-defects were anonymous user avatar content images and blue focus rings.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Top Menu Icon Scope Pass 34

- User clarified that menu-bar icons should not receive semantic color adaptation unless they were explicitly part of a reported dark/light-mode issue.
- Narrowed top-menu color rules so selected/hover states only recolor the text label (`a.tmenulabel` / `.mainmenuaspan`), not the `a.tmenuimage` Font Awesome icon.
- Added explicit `#id-top a.tmenuimage` icon exceptions after both light and dark semantic icon rules so top-menu icons do not inherit content-area colors such as bank/contract/module semantic colors.
- Verified on `/user/card.php?id=1` in both forced dark and forced light modes: top-menu icons compute to neutral menu colors while menu labels still compute to the expected selected/default colors.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Dark Component Border/Icon Refinement Pass 35

- User reported five remaining dark-mode misses: date-filter calendar icons disappeared into blue blocks, selected view-mode buttons had a bright outline, warning/info boxes kept a grey left stripe, admin security content boxes used bright white borders, and stats/chart cards had a bright outer border.
- Replaced the dark-mode calendar PNG filter with a transparent inline SVG calendar via `content: url(...)`, because `object_calendarday.png` is an opaque RGB bitmap and filtering it paints the whole icon box.
- Added scoped dark-mode rules for selected `btnTitle` view-mode buttons, `.divsection` content boxes, and stats/chart `table.noborder.centpercent` containers so their borders use iForm dark line colors instead of native light borders.
- Gave `.info`, `.warning` / `.messagewarning`, `.ok` / `.messageok`, and `.error` / `.messageerr` semantic dark-mode border colors, including matching left-stripe colors for blue, amber, green, and red message boxes.
- Synced the theme into the active `127.0.0.1:18090` Dolibarr test checkout and verified computed styles on the affected pages: customer invoice list calendar icons, module administration view buttons and info box, SMS warning box, admin security `.divsection` blocks, and product stats/chart cards.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Action Dropdown and Calendar Icon Pass 36

- User clarified that action dropdown menus should not reuse the primary button color, and asked for the new calendar icon to apply in light mode as well with mode-appropriate colors.
- Added a base light-mode `object_calendarday.png` / `.ui-datepicker-trigger` replacement using the same transparent inline SVG calendar as dark mode, colored with the light accent blue.
- Added scoped action-dropdown exceptions for `.dropdown-content .butAction` / delete / refused items so menu rows use transparent/menu backgrounds, subtle separators, and neutral hover treatment instead of primary button fills.
- Added the same action-dropdown exceptions inside the dark layer so later dark button rules cannot re-promote dropdown rows back into blue buttons, and recolored the dropdown arrow triangle to the dark menu panel color.
- Synced the theme into the active `127.0.0.1:18090` test checkout, verified light and dark calendar icons by computed `content` colors (`#0969da` / `#78b7ff`), and verified the loaded CSSOM contains the action-dropdown overrides after the native dropdown/button rules.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Action Dropdown Final Override Pass 37

- User showed the commercial action dropdown was still blue in light mode. The previous pass verified only CSS rule presence and missed the real opened menu state.
- Added a final post-dark-layer override for action/menu dropdowns so `.dropdown-holder > .dropdown-content > .butAction` and scoped `.tabsAction` / `.tabsActionNoBottom` / `.tabBar` `.dropdown-menu > .dropdown-item` rows cannot inherit primary button fills.
- Verified a real opened dropdown on `/commande/card.php?id=1`: in light mode menu rows compute to transparent backgrounds, normal text color, and soft separators; in dark mode the same menu computes to the dark surface with dark separators and light text.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Top Create Dropdown Box Model Pass 38

- User reported that the top order "Create" action menu separators and hover highlight overflowed the menu panel.
- Configured the local test database to expose the real order action menu: enabled contract/shipping constants, inserted a test order line for order `1`, and marked the order validated so the top `Create` dropdown renders on `/commande/card.php?id=1`.
- Measured the opened menu directly. The enabled `<a class="butAction">` row was still `box-sizing: content-box`, so its 300px width plus horizontal padding overflowed the 300px menu content area by about 20px.
- Added `box-sizing: border-box` and `min-width: 0` to the final action/menu dropdown row override so separators and hover backgrounds stay inside the panel.
- Re-verified the real opened top `Create` dropdown in dark and light modes: every row computes to `box-sizing: border-box` and sits inside the menu (`deltaRight: -1px`) with contained separators.
- PHP syntax check and `git diff --check` passed for `style.css.php` / `TESTED.md`.

## Document Download Dropdown Pass 39

- User reported the row-level PDF/download dropdown from recent documents/orders/invoices still had a bright halo in dark mode, could be clipped near card edges, and showed a white hover background.
- Root-caused on `/compta/index.php`: this is Dolibarr's native `dl.dropdown dd .multichoicedoc > ul.ulselectedfields.open`, not the top action-menu `.dropdown-menu` / `.dropdown-content` covered in previous passes. The native rule kept `box-shadow: 1px 1px 10px #aaa`, `overflow: auto`, and `.dropdown dd ul li:hover { background: #eee; }`.
- Added scoped document-dropdown overrides: menu surface/border/shadow now uses iForm theme tokens, `max-height` and `overflow` no longer clip the two-row document menu, and hover uses the neutral menu hover color instead of white.
- Added scoped parent `:has(...)` overflow exceptions for box/table-responsive containers containing an opened document dropdown so menus near card bottoms can paint outside the card instead of being cut off by the nearest card wrapper.
- Synced the theme into the active `127.0.0.1:18090` test checkout and verified the real opened menu in dark mode computes to `rgb(28, 31, 34)`, border `rgb(58, 64, 72)`, black shadow `rgba(0, 0, 0, 0.56) 0px 14px 28px`, `overflow: visible`, and `z-index: 1000`. Verified light mode still computes to white surface, light border, and a subtle light shadow.

## Agenda Month Dark Surface Pass 40

- User reported that the bottom exposed grey on the agenda month page did not match the upper page grey, and that the agenda calendar still had incomplete dark-mode adaptation.
- Root-caused the grey mismatch to `#id-right` ending at content height while the surrounding `#id-container` retained the darker body background below the calendar.
- Added `#id-container` to the dark shell surface and gave it a viewport-height minimum so the lower exposed page area uses the same dark surface as the content region.
- Added scoped dark-mode rules for agenda month tables: current-month cells, other-month cells, today cells, row borders, day-title cells, event cards, and view-mode icons now use iForm dark surfaces, muted text, accent selected state, and soft dark separators instead of native white/grey calendar styling.
- Synced the theme into the active `127.0.0.1:18090` test checkout and verified `/comm/action/index.php?mainmenu=agenda&leftmenu=agenda&mode=show_month&THEME_DARKMODEENABLED=2`: `#id-container` computes to `rgb(24, 27, 31)`, current cells to `rgb(28, 31, 34)`, other-month cells to `rgb(32, 36, 41)`, event cards to `rgb(36, 40, 45)`, and selected view-mode icons to `rgb(120, 183, 255)`.

## Dark Title Button Hover Pass 41

- User reported that the hover state on invoice card title/view buttons still showed a border that was too white in dark mode.
- Root-caused this to Dolibarr's native `.btnTitle:hover, a.btnTitle:hover { border: 1px solid #bbb; }` rule from `btn.inc.php`, which was still eligible for non-selected title buttons.
- Added a dark-mode `.btnTitle:hover` / `.btnTitle:focus` override so these buttons use the neutral dark hover surface, `var(--iform-line)` border, no shadow, and dark-mode accent text instead of the native bright border.
- Synced the theme into the active `127.0.0.1:18090` test checkout and verified the invoice card title buttons load with dark borders (`rgb(58, 64, 72)`) and the served dark stylesheet contains the later hover override after the native `#bbb` rule. Verified the icon itself keeps its neutral view-mode color rather than being forced to the button text color.

## Dark Neutral Badge Pass 42

- User reported two remaining neutral badge misses: the agenda month "today" date pill and the invoice type "标准" pill both stayed light in dark mode.
- Root-caused both to Dolibarr's `span.badgeneutral` rule from `badges.inc.php`, which uses `#e4e4e4` background and `#666` text and was not covered by the existing `.badge-status` / `.statusref` dark-mode rules.
- Added a scoped dark-mode `.badgeneutral` override with a neutral dark translucent background, soft muted border, muted light text, and no shadow while leaving semantic status badges on their existing colored paths.
- Synced the theme into the active `127.0.0.1:18090` test checkout and verified both pages with forced dark mode: invoice "标准" and agenda "04" badges compute to `rgba(169, 179, 192, 0.14)` background, `rgba(169, 179, 192, 0.28)` border, and `rgb(200, 208, 217)` text.
