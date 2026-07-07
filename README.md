# Formora

**Formora** is an Eldy-based theme for Dolibarr ERP/CRM 23.0.0. It keeps Dolibarr's default Eldy structure, layout behavior, menu logic, responsive breakpoints, and PHP include model, while replacing the visual layer with a cleaner Formora interface for light mode and dark mode.

This repository contains the theme package only. After installation, both the theme directory and the Dolibarr theme id are `formora`.

## Features

- Built from the Dolibarr 23.0.0 Eldy theme.
- Preserves Eldy's top menu, left menu, lists, pagination, cards, tabs, mobile layout, and include structure.
- Adds a Formora-style light theme with a quiet white/gray workspace, blue primary actions, clearer table states, refined focus states, and softer hover feedback.
- Supports Dolibarr's official dark-mode switch and extends it with a complete Formora dark visual layer.
- Styles common Dolibarr UI elements: buttons, forms, select2 controls, tabs, cards, status badges, jNotify toasts, tooltips, dropdowns, dialogs, and scrollbars.
- Adapts embedded editors and complex widgets, including CKEditor toolbars, dropdowns, dialogs, editing iframes, and the ACE editor used by Dolibarr's CSS editor.
- Adjusts Chart.js pie-like charts in dark mode so borders, legends, and transparent fills remain readable.
- Requires no build step and no external frontend dependency.

## Compatibility

The theme is developed and tested against Dolibarr 23.0.0.

Using it with another Dolibarr major version is not recommended without a review pass. Dolibarr theme files, page markup, bundled JavaScript widgets, and editor versions can change between major releases. If you need to port this theme to another Dolibarr version, compare it with that version's Eldy theme first, then verify the main pages manually.

## Installation

Copy the `formora` directory from this repository into Dolibarr's theme directory:

```text
/path/to/dolibarr/htdocs/theme/formora
```

The installed directory should look like this:

```text
htdocs/
  theme/
    formora/
      style.css.php
      theme_vars.inc.php
      formora.js
      img/
      ckeditor/
      tpl/
```

If an older `formora` directory already exists, replace the whole directory instead of merging files into it. The theme contains many inherited assets, and partial merges can leave stale files behind.

## Enabling the Theme

In Dolibarr, open:

```text
Home -> Setup -> Display -> Skin and colors
```

Select `formora`, then save the setting.

## Theme JavaScript

Formora includes `formora.js` for interactive theme behavior, including mobile top-menu centering, scroll state classes, dark-mode widget fixes, editor adjustments, and TakePOS iframe theme propagation.

Dolibarr only loads a theme JavaScript file when the hidden global constant is enabled:

```text
ALLOW_THEME_JS = 1
```

In production, open:

```text
Home -> Setup -> Other setup
```

Add or enable `ALLOW_THEME_JS` with value `1`, then clear Dolibarr's cache and refresh the page.

After setup, a normal Formora page should include a script like:

```html
<script src="/theme/formora/formora.js?layout=classic&version=23.0.0"></script>
```

If this script is missing, Formora's CSS may still load, but the interactive behaviors will not run.

For temporary testing, you can force the theme through URL parameters:

```text
?theme=formora&dol_resetcache=1&dol_nocache=1
```

Example:

```text
http://127.0.0.1:18090/product/index.php?theme=formora&dol_resetcache=1&dol_nocache=1
```

`dol_resetcache=1` and `dol_nocache=1` are useful while developing or reviewing theme changes. They should not be needed in normal production URLs.

## Dark Mode

Dolibarr 23 exposes the official `THEME_DARKMODEENABLED` setting:

```text
0 = disabled
1 = follow the browser or operating-system preference
2 = always use dark mode
```

For testing, you can force dark mode with:

```text
?theme=formora&THEME_DARKMODEENABLED=2&dol_resetcache=1&dol_nocache=1
```

The theme adds a Formora dark layer on top of Dolibarr's official switch. It covers:

- Page backgrounds, content wrappers, cards, lists, tables, and pagination.
- Top navigation, left navigation, selected menu states, separators, hover states, and focus states.
- Inputs, selects, textareas, checkboxes, radios, and select2 controls.
- Primary buttons, secondary buttons, disabled buttons, refused/cancel buttons, and destructive buttons.
- Tabs, status labels, badges, alerts, popups, tooltips, and jNotify toasts.
- CKEditor toolbars, dropdowns, hover frames, dialogs, overlays, form fields, and editing iframe content.
- ACE/CSS editor backgrounds, gutters, cursor, selection, active line, syntax colors, and autocomplete popup.
- Chart.js pie, doughnut, and polar-area chart borders and legend text.

## CKEditor Support

CKEditor-specific theme files live in:

```text
formora/ckeditor/config.js
formora/ckeditor/dark-contents.css
```

In dark mode, `config.js` appends the dark content stylesheet to CKEditor and also installs it into editor iframes and panel iframes after they are created. This lets the theme cover:

- The editor toolbar.
- Format, font, size, and color dropdown panels.
- Image, link, table, special-character, and other CKEditor dialogs.
- WYSIWYG body content, links, tables, and form elements inside the editing iframe.

When changing CKEditor styles, check the normal editing view, source mode, toolbar dropdowns, and at least one dialog.

## CSS Editor Support

Dolibarr's CSS editor page uses ACE. In dark mode, the theme script switches ACE to `ace/theme/one_dark` and adds the `formora-ace-dark` helper class. The CSS layer then finishes the Dolibarr page shell and ACE token styling.

Useful test page:

```text
/admin/ihm.php?mode=css&theme=formora&THEME_DARKMODEENABLED=2&dol_resetcache=1&dol_nocache=1
```

When checking this page, confirm that:

- The editor background is not white.
- The gutter, active line, cursor, and selection remain visible on a dark background.
- CSS comments, selectors, property names, strings, numbers, and keywords are visually distinct.
- Save/cancel buttons and the editor wrapper match the surrounding dark page.

## Development Workflow

This repository does not require a frontend build. After changing files, sync the theme directory into a Dolibarr test installation.

Example:

```bash
rsync -a --delete formora/ /path/to/dolibarr/htdocs/theme/formora/
```

While testing locally, use cache-busting parameters:

```text
&dol_resetcache=1&dol_nocache=1
```

If the browser still serves cached JavaScript or image assets, force refresh the page or append a temporary query parameter:

```text
&_=timestamp
```

## Main Files

```text
README.md                         Project documentation
LICENSE                           Project license declaration
formora/style.css.php      Main Dolibarr theme stylesheet entry point
formora/theme_vars.inc.php Theme variables, graph colors, and base palette
formora/formora.js  Runtime theme script for charts and editor dark-mode support
formora/ckeditor/          CKEditor config and dark content stylesheet
formora/img/               Theme images, icons, and Dolibarr object icons
formora/tpl/               Theme template fragments
formora/*.inc.php          Dolibarr theme component style fragments
```

## Suggested Verification Pages

After theme-level changes, check at least:

- Home page and top menu.
- Third-party list and third-party card.
- Product/service list and product/service card.
- Customer invoice list and invoice card.
- Setup pages, especially display and skin settings.
- Forms using select2.
- Pages with dialogs, confirmation popups, or destructive actions.
- CKEditor pages, including toolbar dropdowns and dialogs.
- The Dolibarr CSS editor page.
- Mobile-width list and card pages.

For dark mode, test both:

```text
THEME_DARKMODEENABLED=1
THEME_DARKMODEENABLED=2
```

Also run a light-mode regression pass so dark styles do not leak into the default appearance.

## Code Checks

Useful static checks:

```bash
php -l formora/style.css.php
node --check formora/formora.js
git diff --check
```

If a local Dolibarr test environment is running, also confirm that the CSS endpoint responds:

```bash
curl -I "http://127.0.0.1:18090/theme/formora/style.css.php?theme=formora&dol_nocache=1"
```

## Design Principles

- Prefer Eldy's existing structure over rewriting Dolibarr's page shell.
- Keep improvements focused on color, borders, shadows, states, icons, and interaction feedback.
- Do not change business flows, move core actions, or alter Dolibarr component semantics.
- Maintain light mode and dark mode together.
- Patch third-party embedded widgets narrowly, only where the theme needs visual integration.

## License

This theme is derived from Dolibarr/Eldy theme files and follows the license used in the inherited source headers: GNU General Public License, version 3 or later.

See [LICENSE](LICENSE) for the project-level license declaration. Individual source files may also include their own copyright headers.
