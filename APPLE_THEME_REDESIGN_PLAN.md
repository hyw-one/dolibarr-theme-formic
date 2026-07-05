# Apple-Inspired Dolibarr Theme Redesign Plan

## Goal

Redesign the iForm Dolibarr theme into a cohesive Apple-inspired visual system across light mode, dark mode, all accessible modules, and all common UI states.

The direction is not decorative glassmorphism or a marketing-style redesign. The goal is a quiet, precise, high-density business UI with Apple-like restraint: clean surfaces, subtle depth, consistent typography, clear interaction states, and carefully controlled semantic color.

## Design Principles

- Keep Dolibarr efficient for repeated ERP/CRM work.
- Use a small, systematic color scale instead of page-specific fixes.
- Make light mode and dark mode equally intentional.
- Use soft but visible borders in light mode.
- Use low-glare borders and shadows in dark mode.
- Preserve semantic color meaning for status, warning, success, danger, and module families.
- Do not recolor real images, logos, avatars, flags, document previews, or product photos.
- Make icon size, alignment, and color depend on context: top menu, left menu, page title, table action, module card, badge/status.
- Prefer common component rules over one-off page patches.

## Visual System

### Color Tokens

- Page background
- Primary surface
- Secondary surface
- Elevated surface
- Separator line
- Soft separator line
- Primary text
- Secondary text
- Muted text
- Accent blue
- Accent hover
- Focus ring
- Success background, border, text, icon
- Warning background, border, text, icon
- Danger background, border, text, icon
- Neutral background, border, text, icon

### Light Mode

- Background should be light gray rather than pure white.
- Cards, tables, dropdowns, and modals should sit on white or near-white surfaces.
- Borders must be visible enough to define shape.
- Shadows should be soft and shallow.
- Disabled states should be readable but clearly inactive.

### Dark Mode

- Background should be near black but not pure black.
- Surfaces should step up gradually.
- Borders should be visible without becoming bright outlines.
- Shadows should be dark and subtle.
- Semantic colors should remain recognizable without becoming neon.

## Component Coverage

### Navigation

- Top menu icons, text, selected state, hover state, and dropdowns.
- Left menu titles, nested entries, selected state, hover state, and settings/tools icons.
- Mobile menu and collapsed sidebar.

### Buttons

- Primary actions.
- Secondary actions.
- Destructive actions.
- Refused/disabled actions.
- Icon buttons.
- Dropdown action buttons.
- Login button.
- POS top navigation buttons.

### Forms

- Text inputs.
- Password inputs.
- Select fields.
- Textareas.
- Required fields.
- Disabled and readonly fields.
- Focus state.
- Select2 controls.
- Date picker.
- Search fields.

### Tables And Lists

- `table.liste`
- `table.noborder`
- `table.border`
- `tableforfield`
- `tagtable`
- Half-column dashboard boxes.
- Empty tables.
- Header rows.
- Filter rows.
- Hover rows.
- Selected rows.
- DataTables pagination and controls.

### Cards And Panels

- Dashboard cards.
- Statistics boxes.
- Module cards.
- Kanban cards.
- Info boxes.
- Section blocks.
- Help/warning/info panels.

### Feedback And Overlays

- Toast / jnotify.
- Modal dialogs.
- Confirmation dialogs.
- Overlay scrim.
- Tooltips.
- Popovers.
- Dropdown menus.
- Select2 dropdowns.
- Date picker overlays.

### Badges And Status

- Status badges.
- Text badges.
- Notification counters.
- Semantic chips.
- Disabled chips.
- Badge icon alignment.

### Icons

- Top menu icons.
- Left menu icons.
- Page title icons.
- Table action icons.
- Module icons.
- Status icons.
- Weather and special icons.
- POS and website icons.
- Real image assets must remain untouched.

## Module Coverage

The redesign should cover all currently enabled modules and all modules that can be safely enabled in the test environment:

- Home and dashboard.
- Third parties and contacts.
- Members.
- Products and services.
- Stock, batches, variants, BOM, MRP, workstations.
- Projects and tasks.
- Commercial proposals, sales orders, shipments, contracts, interventions.
- Suppliers, supplier proposals, purchase orders, receptions.
- Invoices, payments, bank, cash, direct debit, bank transfer.
- Accounting.
- Taxes, salaries, loans, donations, margins.
- HR, holidays, expense reports, recruitment.
- Agenda and resources.
- ECM and documents.
- Tickets and knowledge base.
- Website, web portal, OpenSurvey.
- TakePOS.
- Import, export, module builder, barcode, data policy.
- Notifications, mailing, email collector.
- API, LDAP, OAuth, WebDAV, Webhook.
- Cron, syslog, blocked log.

External side effects such as real sending, payment, synchronization, printing, or scheduled job execution may be avoided during validation even if modules are enabled.

## State Coverage

- Default.
- Hover.
- Active.
- Selected.
- Checked.
- Disabled.
- Readonly.
- Focus and focus-visible.
- Dropdown open.
- Select2 open.
- Date picker open.
- Modal open.
- Tooltip open.
- Toast visible.
- Empty state.
- Error state.
- Warning state.
- Success state.
- Loading state where present.

## Implementation Phases

1. Freeze current baseline and confirm branch state.
2. Refactor theme tokens for Apple-inspired light and dark palettes.
3. Update global typography, spacing, borders, and shadows.
4. Rework navigation, buttons, forms, tables, badges, cards, overlays, and toasts.
5. Rework login, website, OpenSurvey, TakePOS, and other special pages.
6. Sync theme to the Dolibarr test environment.
7. Refresh Dolibarr CSS revision after each major sync.
8. Run browser-based verification in light and dark modes.
9. Fix visual regressions found during verification.
10. Produce final coverage report.

## Verification Requirements

- Use browser automation and computed style checks.
- Verify both `THEME_DARKMODEENABLED=0` and `THEME_DARKMODEENABLED=2`.
- Verify real rendered icon dimensions and colors.
- Verify real borders, backgrounds, text colors, and shadows.
- Verify common open states: dropdown, select2, date picker, modal, toast.
- Verify login page in both modes.
- Verify no real image assets are recolored.
- Keep a list of modules/pages not safely covered and why.

## Deliverables

- Updated theme CSS.
- This plan document.
- Test environment synced with final theme.
- CSS revision refreshed.
- Summary of modified files.
- Summary of visual system changes.
- Coverage list.
- Verification results.
- Remaining risks.

