# Formora 主题参数接入系统设置执行计划

## 目标

让 Formora 主题尽可能完整地接入 Dolibarr 现有「设置 -> 显示 -> 皮肤和颜色」页面中的系统参数。主题仍然保留 Formora 的视觉风格，但不再在 CSS 生成阶段强行覆盖用户已经保存的系统设置。

## 范围

本轮工作限定在主题仓库内完成，不修改 Dolibarr 核心文件。原因是 Dolibarr 23 的皮肤设置页面由核心函数 `showSkins()` 渲染，主题目录本身没有可靠的 PHP hook 可以直接新增设置行。

因此本轮完整执行的范围是：

- 复用 Dolibarr 已有的 `THEME_*` / `THEME_ELDY_*` / `MAIN_*` 系统设置。
- 移除 Formora 对这些设置的运行时强制覆盖。
- 将 Formora 内部 `--formora-*` CSS 变量改为由系统设置和 Formora 默认值共同派生。
- 保留 Formora 深色模式，但让它也能优先读取系统设置或派生值。
- 建立集中映射层，降低后续维护成本。

不在本轮直接实现的范围：

- 在 Dolibarr 设置页中新增 `THEME_FORMORA_*` 专属字段。
- 修改 Dolibarr 核心 `admin/ihm.php` 或 `core/lib/usergroups.lib.php`。
- 创建 companion module 来承载 Formora 专属设置 UI。

如果未来需要亮色/暗色两套完全独立的 Formora 专属参数，应单独做一个 companion module 或给 Dolibarr 核心增加通用 hook。

## 现状问题

当前 Formora 在 `style.css.php` 中存在两个核心问题：

1. 强制关闭 `THEME_ELDY_ENABLE_PERSONALIZED`。
2. 在 CSS 生成阶段把多个 `THEME_ELDY_*` 常量重新写成 Formora 默认值。

这会导致系统设置页面虽然能保存值，但 Formora 输出 CSS 时仍使用主题写死的值。用户看到设置项，却无法稳定影响主题。

此外，Formora 的 `:root` 和深色模式中仍有大量写死的 `--formora-*` 变量，例如：

- `--formora-accent`
- `--formora-bg`
- `--formora-surface`
- `--formora-line`
- `--formora-text`
- `--formora-muted`
- `--formora-danger`
- `--formora-shadow-*`
- `--formora-radius-*`

这些变量需要分层处理：能映射到现有系统设置的优先映射；属于 Formora 视觉衍生值的集中派生；确实没有系统设置对应的保留默认值。

## 现有系统设置映射表

| 系统设置 | Formora 使用方式 |
| --- | --- |
| `THEME_DARKMODEENABLED` | 控制深色模式输出逻辑 |
| `THEME_TOPMENU_DISABLE_IMAGE` | 交给 Eldy/global 处理顶部菜单图标/文字模式 |
| `MAIN_SHOW_LOGO` | 交给 Eldy/global 处理菜单 LOGO |
| `THEME_MENU_COLORLOGO` | 交给菜单图标颜色层处理 |
| `THEME_ELDY_USEBORDERONTABLE` | 控制表格边框是否启用 |
| `THEME_ELDY_BORDER_RADIUS` | 派生 `--formora-table-radius` 和通用半径 |
| `THEME_SHOW_BORDER_ON_INPUT` | 交给 Eldy/global 控制输入框边框 |
| `THEME_ELDY_TOPMENU_BACK1` | 映射顶部菜单/顶部 shell 背景 |
| `THEME_ELDY_VERMENU_BACK1` | 映射左侧菜单背景 |
| `THEME_ELDY_BACKBODY` | 映射页面工作区背景 |
| `THEME_ELDY_TEXTTITLENOTAB` | 映射页面标题/强调色 |
| `THEME_ELDY_BACKTITLE1` | 映射表头背景、分组标题背景 |
| `THEME_ELDY_TEXTTITLE` | 映射表头文字色 |
| `THEME_ELDY_TEXTTITLELINK` | 映射表头链接色 |
| `THEME_ELDY_LINEIMPAIR1` | 映射奇数行背景 |
| `THEME_ELDY_LINEPAIR1` | 映射偶数行背景 |
| `THEME_ELDY_TEXTLINK` | 映射链接色、主强调色 |
| `THEME_ELDY_USE_HOVER` | 映射 hover 背景 |
| `THEME_ELDY_USE_CHECKED` | 映射选中背景 |
| `THEME_ELDY_BTNACTION` | 映射主操作按钮色 |
| `THEME_ELDY_TEXTBTNACTION` | 映射主操作按钮文字色 |

## 执行步骤

1. 新增 Formora 设置映射层
   - 新建 `formora/formora_settings.inc.php`。
   - 提供颜色读取、归一化、CSS 输出和派生色辅助函数。
   - 统一维护 Formora 默认亮色、默认深色和系统设置映射。

2. 移除强制覆盖
   - 删除 `style.css.php` 中强制设置 `THEME_ELDY_ENABLE_PERSONALIZED = 0` 的逻辑。
   - 删除强制把 `THEME_ELDY_*` 重写为 Formora 默认值的逻辑。
   - 保留“如果系统设置不存在，则使用 Formora 默认值”的 fallback。

3. 接入亮色变量
   - 将 `:root` 中能映射的 `--formora-*` 改为 PHP 输出。
   - 主色优先读取 `THEME_ELDY_TEXTLINK`，按钮色优先读取 `THEME_ELDY_BTNACTION`。
   - 页面背景、菜单背景、表头背景、行背景优先读取系统设置。
   - 线条、柔和背景、hover、focus ring 由基础色派生。

4. 接入暗色变量
   - 保留 `THEME_DARKMODEENABLED` 的三种模式。
   - 深色变量优先读取可用系统设置，再使用 Formora 深色默认值。
   - 暗色专属的 surface、line、muted、toast、status 等保持集中默认，避免散落硬编码。

5. 收敛圆角
   - `--formora-table-radius` 继续读取 `THEME_ELDY_BORDER_RADIUS`。
   - `--formora-radius-sm`、`--formora-radius`、`--formora-radius-lg` 从系统圆角派生。
   - 无边框表格模式下不强制新增外观结构。

6. 验证
   - PHP lint：`php -l formora/style.css.php` 与全目录 PHP lint。
   - JS lint：`node --check formora/formora.js`。
   - diff 检查：`git diff --check`。
   - 同步本地 Dolibarr 测试目录。
   - 生成亮色、暗色 CSS 并检查关键变量。
   - 用 URL 参数或数据库常量改变设置，确认 CSS 输出变化。

## 验收标准

- 修改系统设置页现有颜色字段后，Formora 输出 CSS 中对应变量发生变化。
- 深色模式仍能正常输出，并且不出现大片白底漏出。
- 表格圆角和边框由系统设置决定。
- 输入框边框由系统设置决定。
- Formora 不再强行覆盖 `THEME_ELDY_*` 保存值。
- 主题仍保留 Formora 默认视觉风格；未设置任何参数时输出稳定。

## 后续增强建议

如果需要比 Dolibarr 原生设置更细的控制，例如单独配置 Formora 的 soft hover、focus ring、toast、状态色、图标色、阴影强度、暗色专属调色板，建议新增 companion module：

- 模块负责设置页 UI 和保存 `THEME_FORMORA_*` 常量。
- 主题继续只读取常量并输出 CSS。
- 这样可以避免直接修改 Dolibarr 核心，也能把 Formora 专属设置集中维护。

## 本次执行记录

已完成：

- 新增 `formora/formora_settings.inc.php`，集中维护 Formora 对 Dolibarr 系统设置的映射与派生值。
- 移除 `formora/style.css.php` 中强制关闭 `THEME_ELDY_ENABLE_PERSONALIZED` 的逻辑。
- 移除 `formora/style.css.php` 中强制把 `THEME_ELDY_*` 重写为 Formora 默认值的逻辑。
- 将原本“仅个性化时可编辑”的 `THEME_ELDY_BACKTABCARD1`、`THEME_ELDY_BACKTABACTIVE`、`THEME_ELDY_TEXT` 等 fallback 改为仅在未设置时补默认值，避免覆盖系统全局配置。
- 将亮色 `:root` 的 Formora 变量改为由映射表输出。
- 将深色模式 `:root` 的 Formora 变量改为由映射表输出。
- 将 `THEME_ELDY_BTNACTION` 与 `THEME_ELDY_TEXTBTNACTION` 接入 `--butactionbg` / `--textbutaction`，同时为暗色模式保留独立的按钮默认色。
- 调整 `formora/theme_vars.inc.php` 默认值，让亮色模式默认呈现“左侧菜单浅灰、右侧页面白色”的结构。
- 调整 CSS 入口加载顺序：`main.inc.php` 不再自动载入菜单，Formora 在 session 初始化后手动载入菜单，避免 stylesheet 请求中出现 `leftmenuopened` warning。
- 为 stylesheet 请求补齐 `$conf->browser->layout` 与 `$conf->browser->name` 默认值，避免无页面上下文生成 CSS 时出现 browser warning。
- 保留 Formora 图标、细节色、阴影等视觉风格，并把可维护的主题参数收敛到映射层。
- 增加“实际元素生效”修复层，不只检查 CSS 变量输出：
  - 主操作按钮改为读取 `--butactionbg` / `--textbutaction`，不再被 `THEME_ELDY_TEXTLINK` 链接色误驱动。
  - 登录页主按钮、普通动作按钮、`buttongen`、POS/takepos 主动作按钮统一接入按钮设置。
  - 顶部菜单背景改为读取 `THEME_ELDY_TOPMENU_BACK1`，移动/折叠菜单中的 menu hider 与登录区也保持同一背景来源。
  - 顶部菜单图标/文字模式增加最终绑定规则，避免 Formora 响应式布局把 `THEME_TOPMENU_DISABLE_IMAGE=4` 的“仅图标”顶回“图标+文字”。
  - 页面标题改为读取 `THEME_ELDY_TEXTTITLENOTAB`。
  - 表头背景、表头文字、表头链接分别读取 `THEME_ELDY_BACKTITLE1`、`THEME_ELDY_TEXTTITLE`、`THEME_ELDY_TEXTTITLELINK`。
  - 普通表格行与单元格重新接入 `THEME_ELDY_LINEIMPAIR*`、`THEME_ELDY_LINEPAIR*`、`THEME_ELDY_USE_HOVER`、`THEME_ELDY_USE_CHECKED`。
- 调整暗色变量策略：
  - 未显式修改系统颜色时，暗色模式继续使用 Formora 暗色默认面板、左侧菜单、标题和行色。
  - 一旦管理员显式修改某个 Eldy 系统设置，该设置在强制暗色模式下也会进入对应实际元素。
  - Formora 专属 `THEME_FORMORA_DARK_*` 常量仍可作为未来 companion module 的更高优先级暗色覆盖入口。

已验证：

- `php -l formora/style.css.php`
- `php -l formora/formora_settings.inc.php`
- `find formora -name '*.php' -print0 | xargs -0 -n1 php -l`
- `node --check formora/formora.js`
- `git diff --check`
- 已同步到本地 Dolibarr 测试目录：
  `/Users/andyhuang/Documents/Codex/2026-07-04/iform-dolibarr/work/dolibarr-23.0.0/htdocs/theme/formora/`
- 已通过本地服务生成亮色与暗色 CSS：
  - 亮色 CSS：`/tmp/formora-light.css`
  - 暗色 CSS：`/tmp/formora-dark.css`
- 当前测试环境输出中，系统设置里的表格圆角已进入 Formora 变量：
  `--formora-table-radius: 20px`
- 当前亮色输出中，Formora 默认结构为：
  - `--formora-workspace: #ffffff`
  - `--colorbackvmenu1: #f5f5f7`
- 当前强制暗色输出中，Formora 暗色结构为：
  - `--formora-workspace: #1c1c1e`
  - `--colorbackvmenu1: #0f1012`
- 已通过数据库常量矩阵测试，覆盖以下组合：
  - 默认亮色 Formora 变量。
  - 强制暗色 Formora 变量。
  - Eldy 自定义颜色到 Formora 亮色变量的映射。
  - 暗色模式未自定义时保持 Formora 暗色默认。
  - 暗色模式下，已显式自定义的系统设置仍能影响对应实际元素。
  - 表格圆角 `0/4/6/8/10/20`。
  - 关闭表格边框时 `--formora-table-radius: 0px`。
  - 输入框边框开关在亮色、跟随浏览器、强制暗色下生成正常。
  - 顶部菜单图标/文字、Logo、彩色图标组合在亮暗色下生成正常。
- 已通过真实页面 computed style 探针测试，覆盖亮色与强制暗色：
  - 修改 `THEME_ELDY_TEXTLINK` 后，链接色变量变化，但主按钮保持按钮色。
  - 修改 `THEME_ELDY_BTNACTION` / `THEME_ELDY_TEXTBTNACTION` 后，主按钮背景和文字实际变色。
  - 修改 `THEME_ELDY_TOPMENU_BACK1` 后，`#id-top` 实际背景变色。
  - 修改 `THEME_ELDY_VERMENU_BACK1` 后，`#id-left` 实际背景变色。
  - 修改 `THEME_ELDY_BACKBODY` 后，`#id-right` 实际背景变色。
  - 修改 `THEME_ELDY_TEXTTITLENOTAB` 后，页面 `.titre` 实际文字色变色。
  - 修改 `THEME_ELDY_BACKTITLE1` / `THEME_ELDY_TEXTTITLE` 后，`.liste_titre` 实际背景和文字色变色。
  - 修改 `THEME_ELDY_LINEIMPAIR*` / `THEME_ELDY_LINEPAIR*` 后，普通数据行实际背景变色。
  - 设置 `THEME_TOPMENU_DISABLE_IMAGE=4` 后，顶部菜单文字在实际布局中不可见，仅保留图标。
- 已登录本地 Dolibarr 设置页 `admin/ihm.php?mode=template` 验证：
  - 当前主题 `formora` 已选中。
  - 系统设置页默认颜色显示为 Formora 默认值，例如 `ffffff`、`f5f5f7`、`eef1f5`、`0071e3`、`fcfcfd`。
