# Formora 主题系统性审查报告

审查对象：Dolibarr 23.0.0 自定义主题 `formora`  
仓库：`/Users/andyhuang/GitHub/dolibarr-theme-iform`  
主题目录：`/Users/andyhuang/GitHub/dolibarr-theme-iform/formora`  
审查日期：2026-07-08  
审查方式：只读审查，未修改主题代码，未 commit，未 push。

## 1. 审查方法

本次审查覆盖两个维度：

1. 写法/代码质量：静态阅读 `formora/` 下 PHP/CSS/JS 片段，并对比 Eldy 原始主题。
2. 实际渲染效果：将仓库主题同步到本地 Dolibarr 23.0.0 测试实例后，用 Playwright 在真实 Chromium 中截图并读取 `getComputedStyle`。

参考 Eldy 源码：

```text
/Users/andyhuang/Documents/Codex/2026-07-04/iform-dolibarr/work/dolibarr-23.0.0/htdocs/theme/eldy/
```

本地测试实例：

```text
http://127.0.0.1:18090
账号：admin
密码：admin
```

同步命令已执行：

```bash
rsync -a --delete /Users/andyhuang/GitHub/dolibarr-theme-iform/formora/ /Users/andyhuang/Documents/Codex/2026-07-04/iform-dolibarr/work/dolibarr-23.0.0/htdocs/theme/formora/ --exclude=.DS_Store
```

Playwright 临时目录：

```text
/tmp/formora-audit-cVuq5Y/
```

截图与计算样式快照：

```text
/tmp/formora-audit-cVuq5Y/out/
```

共生成 88 组主抽样页面截图和 JSON 快照，另对邮件布局相关页面做了补测。

## 2. 覆盖范围

主抽样页面均覆盖亮色/暗色、桌面/手机：

- 桌面：`1440x950`
- 手机：`390x900`，`isMobile: true`，`deviceScaleFactor: 3`
- 暗色参数：`THEME_DARKMODEENABLED=2`
- 缓存规避：`theme=formora&dol_resetcache=1&dol_nocache=1`

抽样模块和页面类型：

| 模块 | 列表页 | 新建/编辑页 | 详情页 |
| --- | --- | --- | --- |
| 首页 | `/index.php?mainmenu=home` | - | - |
| 合作方 | `/societe/list.php` | `/societe/card.php?action=create` | - |
| 用户 | `/user/list.php` | - | `/user/card.php?id=1` |
| 产品/服务 | `/product/list.php?type=0` | `/product/card.php?action=create&type=0` | - |
| 客户发票 | `/compta/facture/list.php` | `/compta/facture/card.php?action=create` | - |
| 销售订单 | `/commande/list.php` | `/commande/card.php?action=create` | - |
| 报价单 | `/comm/propal/list.php` | `/comm/propal/card.php?action=create` | - |
| 合同 | `/contrat/list.php` | `/contrat/card.php?action=create` | - |
| 项目 | `/projet/list.php` | `/projet/card.php?action=create` | - |
| 服务工单 | `/ticket/list.php` | `/ticket/card.php?action=create` | - |
| 会员 | `/adherents/list.php` | `/adherents/card.php?action=create` | - |
| 日程 | `/comm/action/list.php` | - | - |
| 邮件/群发补测 | `/admin/mails_templates.php` | `/comm/mailing/card.php?action=create` | - |

## 3. 总体结论

当前 `formora` 的常用页面视觉状态总体健康：未发现大面积破版、整页横向滚动、暗黑模式大片白底漏出，移动端创建表单也未复现此前“每字段双分隔线”的问题。

主要风险集中在：

- `style.css.php` 末尾 Formora 覆盖层过强，尤其通用 table reset 和大量 `!important`。
- 个别 CSS 仍在控制显示/隐藏，和“主题只做样式”的目标存在边界冲突。
- 少量动态场景或非主页面组件仍有暗色/回滚风险，例如 PWA manifest、邮件布局选择器、Chart.js/ACE 运行中暗转亮。
- 一些高特异性 `:is()`/`:has()` 选择器和重复移动端规则会增加后续维护成本。

## 4. 写法问题

### W1. 通用 table/tagtable reset 过强，且覆盖前面的边框语义

严重程度：需要和我确认设计意图后再改  
问题类型：维护性风险、选择器优先级风险  

相关位置：

- `formora/style.css.php:2528`
- `formora/style.css.php:10265`
- `formora/style.css.php:10275`

问题描述：

`style.css.php` 前面已经对 `table.liste`、`table.formdoc`、`table.tagtable.liste` 设置了 `border-color: var(--formora-line) !important`。但文件末尾的通用规则又对 `#id-right :is(td, th, .tagtd)` 统一设置：

```css
border-width: 0 0 1px 0 !important;
border-color: var(--formora-line-soft) !important;
```

由于末尾规则源顺序更晚，且同样使用 `!important`，`#id-right` 内大量列表/详情表格最终会被 `--formora-line-soft` 接管。

复现方式：

1. 访问任意列表页，例如：
   `/product/list.php?type=0&theme=formora&dol_resetcache=1&dol_nocache=1`
2. 在表格单元格上读取 `getComputedStyle(td).borderBottomColor`。
3. 可见实际结果更接近末尾通用 reset，而不是前面列表专用规则。

影响：

当前视觉是统一的，但维护者很难判断哪些场景应该用 `--formora-line`，哪些应该用 `--formora-line-soft`。未来新增插件表格或 Dolibarr 新表格形态默认都会被剥掉外框/阴影，只能继续追加更晚、更强的例外规则。

建议：

先确认设计意图：是否希望所有 `#id-right` 内表格默认只保留行分隔线。如果是，建议删除或弱化前面近似失效的 `table.liste` 边框色规则；如果不是，建议把末尾 reset 从“全局 denylist”改为更明确的 allowlist/组件层规则。

### W2. 仍有 CSS 直接控制显示/隐藏

严重程度：需要和我确认设计意图后再改  
问题类型：职责边界风险  

相关位置：

- `formora/style.css.php:9455`
- `formora/style.css.php:9510`
- `formora/style.css.php:9752`
- `formora/style.css.php:9785`
- `formora/style.css.php:9995`
- `formora/style.css.php:10126`

问题描述：

主题目标是“只做样式，不控制显示/隐藏”。但当前后置覆盖层仍包含多处 `display: none !important`，包括：

- 隐藏 topbar 中 `.aversion` 所在元素。
- 隐藏 topbar 用户名。
- 隐藏 TAKEPOS 的时间/仓库/首页链接等元素。
- 按 inline style 字符串匹配 `[style*="display: none"]`，再次强制隐藏 input 或移动端表格行。
- 隐藏空单元格。

复现方式：

静态搜索：

```bash
rg -n "display:\\s*none" formora/style.css.php
```

运行页观察：

- 桌面非 collapsed topbar：`/index.php?mainmenu=home&theme=formora&dol_resetcache=1&dol_nocache=1`
- TAKEPOS 相关页面若启用 POS 后可观察 topbar 裁剪。
- 手机创建表单中空行/隐藏行会被 Formora CSS 二次处理。

影响：

部分规则可能是为了尊重 Dolibarr 原本隐藏状态，或修复移动端空行；但也有一些是在主题层主动减少 UI 内容。它们和“显示/隐藏交还 Dolibarr 原生处理”的目标不完全一致。

建议：

逐条分类：

- 保留但补注释：只是在压过第三方组件或 inline style 的“尊重原生隐藏”。
- 移除或改为纯视觉处理：隐藏版本号、用户名、TAKEPOS 元素等主动改变信息呈现的规则。

### W3. 超长选择器把 ID 放进 `:is()`，导致分支整体获得 ID 级特异性

严重程度：小瑕疵  
问题类型：特异性风险  

相关位置：

- `formora/style.css.php:2100`
- 同类规则集中在 `formora/style.css.php:2100-2177`
- 列表页同类规则还出现在 `formora/style.css.php:7202` 附近

问题描述：

例如：

```css
form:is(#searchFormList, :has(...))
```

根据 CSS 规则，`:is()` 的特异性取其参数中最高者。因此即使匹配的是 `:has(...)` 分支，也会因为 `#searchFormList` 这个参数获得 ID 级特异性。

复现方式：

1. 访问列表页，例如：
   `/societe/list.php?mainmenu=companies&theme=formora&dol_resetcache=1&dol_nocache=1`
2. 检查标题按钮或分页按钮相关样式。

影响：

当前渲染正常，但后续如果想局部覆盖某个列表页标题按钮，很容易需要继续堆 ID 或依赖更晚源码顺序。

建议：

将 ID 分支和非 ID 分支拆开写，或用 `:where()` 包装不需要贡献特异性的部分。

### W4. 移动端表单 flex 化规则重复，维护时容易漏改

严重程度：小瑕疵  
问题类型：重复规则、移动端高风险区域  

相关位置：

- `formora/style.css.php:9722`
- `formora/style.css.php:9737`
- `formora/style.css.php:9763`
- `formora/style.css.php:9774`

问题描述：

移动端创建表单中，对两种结构分别写了几乎相同声明：

- `form .tabBar > table.border ...`
- `form > table.border ...`

两套规则都设置 `display: block/flex`、`flex-direction: column`、`gap`、`padding`、`width` 等。

复现方式：

手机宽度访问：

- `/societe/card.php?action=create&theme=formora&dol_resetcache=1&dol_nocache=1`
- `/product/card.php?action=create&type=0&theme=formora&dol_resetcache=1&dol_nocache=1`
- `/compta/facture/card.php?action=create&theme=formora&dol_resetcache=1&dol_nocache=1`

实际结果：

当前视觉正常，字段分隔线也没有双线问题。但这一区域历史上已经出现过移动端边框回归，重复分支会提高以后漏改概率。

建议：

如果后续要改，可考虑用一个公共选择器变量/分组规则集中声明相同部分，只把结构差异放在少量补充规则里。

### W5. 标题按钮移动端尺寸规则重复

严重程度：小瑕疵  
问题类型：重复规则  

相关位置：

- `formora/style.css.php:2166`
- `formora/style.css.php:2254`
- `formora/style.css.php:7612`

问题描述：

多个 `max-width: 767px` 块重复设置：

```css
--formora-title-action-icon-size: 20px;
--formora-title-action-size: 34px;
gap: 4px;
```

复现方式：

手机宽度访问列表页或带“查看对话/列表”按钮的详情页。

影响：

视觉当前一致，但同一类按钮的移动端尺寸逻辑分散在多处，后续改尺寸或间距时容易出现一处改、一处漏。

建议：

抽成更靠前的公共规则，特殊页面只保留必要例外。

### W6. `#topmenu-login-dropdown` 下划线/边框 reset 重复

严重程度：小瑕疵  
问题类型：重复规则、特异性风险  

相关位置：

- `formora/style.css.php:623`
- `formora/style.css.php:8138`
- 相关补救注释：`formora/style.css.php:10210`

问题描述：

两处都对 `#topmenu-login-dropdown > a.dropdown-toggle` 及其 hover/focus/open/show 状态重置：

```css
border-bottom: 0 !important;
box-shadow: none !important;
text-decoration: none !important;
```

选择器中还包含：

```css
:is(.atoplogin, .atoploginusername, #dropdown-icon-down, #dropdown-icon-up)
```

因此整组包含 ID 级特异性。

复现方式：

访问任意桌面页面，检查 topbar 用户下拉触发器。

影响：

当前视觉正常，但重复块未来可能漂移。后续头像 ring 还需要专门用更强选择器补回来，说明这段特异性已经形成维护债。

建议：

合并为一处，并避免把 `#dropdown-icon-down/#dropdown-icon-up` 放进同一个 `:is()`，或改用 `:where()`。

### W7. `formora.js` 暗色应用没有浅色回滚路径

严重程度：需要和我确认设计意图后再改  
问题类型：运行态状态管理风险  

相关位置：

- `formora/formora.js:121`
- `formora/formora.js:256`
- `formora/formora.js:347`
- `formora/formora.js:392`

问题描述：

`formora.js` 会在暗色模式下修改 Chart.js defaults、Chart 实例和 ACE editor 主题。但在 `!colors.dark` 时基本直接 return，没有把此前写入的暗色 Chart 默认值、Chart 实例颜色、ACE `one_dark` 主题或 `formora-ace-dark` class 回滚。

复现方式：

1. 使用 `THEME_DARKMODEENABLED=1`，让页面跟随系统 `prefers-color-scheme`。
2. 打开含 Chart.js 或 ACE editor 的页面。
3. 在浏览器 DevTools/Playwright 中切换 dark/light media。
4. 观察图表或 ACE 是否保持暗色补丁。

影响：

如果用户实际只在页面加载时确定主题，此问题影响较低。如果支持运行中跟随系统主题切换，则会出现暗转亮残留。

补充观察：

脚本没有发现直接 `.show()`、`.hide()`、`.toggle()` 或写布局尺寸，剩余 JS 基本符合“只保留 Chart.js/ACE 配色脚本”的目标。

建议：

确认是否需要支持运行中暗/亮切换。如果需要，应保存原始 Chart/Ace 设置并在浅色时回滚；如果不需要，建议在注释中明确“只处理页面加载时暗色”。

### W8. 全文档 MutationObserver 可能产生额外开销

严重程度：小瑕疵  
问题类型：性能风险  

相关位置：

- `formora/formora.js:392`
- `formora/formora.js:393`

问题描述：

当前 observer 监听 `document.documentElement` 的 subtree 任意 childList 变化，并调度 ACE/Chart 处理。动态列表、Select2、看板、异步 dashboard 等频繁 DOM 变更页面可能触发额外 `chart.update()` 尝试。

复现方式：

打开 dashboard 首页或含 Select2/动态列表的页面，频繁操作筛选、下拉或刷新图表。

影响：

未在本轮测试中观察到明显卡顿，但这是潜在运行态开销。

建议：

限制 observer 作用域，或只在页面存在 `.ace_editor`/`canvas` 时启用，并对 Chart 更新做更强节流。

### W9. 仍强绑定 `THEME_ELDY_*` 配置名

严重程度：小瑕疵  
问题类型：命名一致性、配置耦合  

相关位置：

- `formora/style.css.php:212`
- `formora/style.css.php:255`
- `formora/style.css.php:259`
- `formora/style.css.php:391`

问题描述：

Formora 仍大量读写 `THEME_ELDY_*` 配置，例如：

```php
$conf->global->THEME_ELDY_ENABLE_PERSONALIZED = 0;
$formoraMenuCollapseWidth = (int) getDolGlobalString('THEME_ELDY_WITDHOFFSET_FOR_REDUC3', ...);
```

复现方式：

静态阅读即可。

影响：

这来自 Eldy 继承，短期合理。但从维护角度看，Formora/Eldy 设置语义混在一起，排查个性化配置和断点时不直观。

建议：

不建议立即大改。后续若做配置整理，可逐步引入 `THEME_FORMORA_*` 别名，并兼容旧 `THEME_ELDY_*`。

### W10. 组件片段仍保留 Eldy 浅色/硬编码规则，靠后置 CSS 二次覆盖

严重程度：小瑕疵  
问题类型：双份维护  

相关位置示例：

- `formora/dropdown.inc.php:402`
- `formora/dropdown.inc.php:545`
- `formora/badges.inc.php:292`
- `formora/badges.inc.php:309`
- `formora/info-box.inc.php:390`
- `formora/info-box.inc.php:425`
- `formora/timeline.inc.php:57`
- `formora/timeline.inc.php:109`

问题描述：

大多数组件片段与 Eldy 基本一致，内部仍有 `#fff`、`#212529`、固定语义色和大量 `!important`。Formora 后置层已经覆盖了常见页面，所以实际渲染大体正常。

复现方式：

静态对比 Eldy 与 Formora，或检查暗色页面 dropdown、badge、info-box 的 computed style。

影响：

当前常见页面风险不高，但新增状态、带 `$cssPrefix` 的 badge、非 `#mainbody` 场景或 include 顺序变化时，可能漏出 Eldy 浅色规则。

建议：

暂不需要大范围改片段。后续如果要降低维护成本，可以逐步把组件片段本身变量化，减少靠 `style.css.php` 最后补丁覆盖。

### W11. 少量空规则或兼容性小问题

严重程度：小瑕疵  
问题类型：清理项  

相关位置：

- `formora/progress.inc.php:143`
- `formora/progress.inc.php:212`
- `formora/info-box.inc.php:212`
- `formora/info-box.inc.php:457`
- `formora/timeline.inc.php:211`
- `formora/btn.inc.php:320`

问题描述：

存在空规则块或只有注释的块，例如：

```css
.progress-group .progress-text {
    /* font-weight: 600; */
}
```

另有：

```css
background-color: rgb(0, 0, 0, 0.15);
```

这是现代 CSS Color 写法的一种变体，但兼容性不如 `rgba(0, 0, 0, 0.15)` 或 `rgb(0 0 0 / 0.15)` 直观。

复现方式：

静态阅读即可。

影响：

低风险，主要是噪音和维护洁净度问题。

建议：

后续清理时删除空块；`rgb(0, 0, 0, 0.15)` 可改为 `rgba(0, 0, 0, 0.15)`。

## 5. 渲染问题

### R1. PWA manifest 暗色模式仍输出亮色壳层颜色

严重程度：明显 bug  
问题类型：暗色模式漏适配  

相关位置：

- `formora/manifest.json.php:85`
- `formora/manifest.json.php:90`

问题描述：

暗色模式下普通页面已经使用 Formora 暗色变量，但 manifest 仍输出：

```json
{
  "theme_color": "#0969da",
  "background_color": "#ffffff"
}
```

复现方式：

```bash
curl -sS 'http://127.0.0.1:18090/theme/formora/manifest.json.php?theme=formora&dol_resetcache=1&dol_nocache=1&THEME_DARKMODEENABLED=2' | jq '{theme_color,background_color,display,name}'
```

实际结果：

```json
{
  "theme_color": "#0969da",
  "background_color": "#ffffff",
  "display": "minimal-ui",
  "name": "Dolibarr"
}
```

影响：

影响 PWA 安装后启动页、浏览器壳层颜色、移动端地址栏/任务切换视觉；不影响普通网页主体渲染。

建议：

让 manifest 在 `THEME_DARKMODEENABLED=2` 时输出 Formora 暗色背景，例如接近 `--formora-bg` 或 `--formora-surface` 的值；同时考虑 `MAIN_MANIFEST_APPLI_*` 自定义配置的优先级。

### R2. 邮件布局选择器默认隐藏，但暗色边界样式仍不一致

严重程度：需要和我确认设计意图后再改  
问题类型：暗色模式边界组件风险  

相关位置：

- `formora/emaillayout.inc.php:12`
- `formora/emaillayout.inc.php:13`
- `formora/emaillayout.inc.php:41`
- `formora/emaillayout.inc.php:67`

问题描述：

`.template-container`、`#template-selector` 和 AI 模板按钮仍有浅色硬编码：

```css
background: #f5f5f5;
border: 1px solid #d3d3d3;
background: #c5f7c5;
```

补测页面：

```text
/comm/mailing/card.php?action=create&theme=formora&dol_resetcache=1&dol_nocache=1&THEME_DARKMODEENABLED=2
```

计算样式观察：

`.template-selector.email-layout-container.hidden` 当前是隐藏状态，`w/h = 0`，但仍可读到 `border: 1px solid rgb(211, 211, 211)`。

影响：

默认隐藏时用户不可见；如果某个交互或配置展开该布局选择器，暗色模式下可能出现亮色边框或过亮 AI 绿色块。

建议：

确认该布局选择器是否仍在生产流程中使用。如果使用，应将背景/边框改为 Formora 变量；如果废弃，可考虑清理片段。

### R3. 状态 badge 的自动低对比告警经人工复核后暂不列为明显 bug

严重程度：小瑕疵/观察项  
问题类型：可访问性待复核  

相关位置：

- `formora/style.css.php:5004`
- `formora/style.css.php:5031`
- `formora/style.css.php:5045`
- `formora/style.css.php:7814`

观察结果：

Playwright 简化对比算法把暗色列表页中大量 badge 标为低对比，例如产品列表的“可销售”、发票列表的“已付款”、日程列表的“NA”。但该算法直接用 `rgba(...)` 背景和文字色计算，没有把透明背景叠加到真实深色父背景上，因此存在误判。

复现页面：

- `/product/list.php?type=0&theme=formora&THEME_DARKMODEENABLED=2`
- `/compta/facture/list.php?theme=formora&THEME_DARKMODEENABLED=2`
- `/comm/action/list.php?theme=formora&THEME_DARKMODEENABLED=2`

人工截图复核：

状态 badge 在暗色截图中可读，没有发现明显无法辨认的问题。

建议：

暂不作为 bug 修。若后续要做无障碍审查，应使用真实背景合成后的 WCAG 对比计算，而不是直接比较透明 `rgba`。

### R4. 主抽样页面未发现整页横向滚动，但宽列表依赖内部滚动容器

严重程度：小瑕疵/观察项  
问题类型：响应式行为确认  

复现页面：

手机宽度 `390px`：

- `/societe/list.php?theme=formora&dol_resetcache=1&dol_nocache=1`
- `/product/list.php?type=0&theme=formora&dol_resetcache=1&dol_nocache=1`
- `/compta/facture/list.php?theme=formora&dol_resetcache=1&dol_nocache=1`
- `/comm/action/list.php?theme=formora&dol_resetcache=1&dol_nocache=1`

观察结果：

文档级 `scrollWidth` 均等于 viewport 宽度，没有整页横向滚动。脚本检测到一些元素局部超过 viewport，但它们位于列表内部横向滚动区域，属于 Dolibarr 宽表格常见行为。

建议：

暂不处理。后续如果希望列表页在手机端不依赖横向滚动，需要产品级设计决策，而不是单点 CSS bug。

## 6. 已验证正常的重点项

以下项目本轮抽样未发现明显问题：

- 亮色/暗色首页 dashboard 主体背景和图表暗色适配基本正常。
- 合作方、产品、发票、订单、报价单、合同、项目、工单、会员、用户等创建表单在手机端未出现整页横向溢出。
- 手机端创建表单字段分隔线未出现每个字段两条线的回归。
- 列表页 top action 按钮、筛选按钮、分页输入、Select2 输入在暗色模式下整体可读。
- `table` 与 `tagtable` 两套表格系统在抽样页面均被 Formora 后置规则覆盖。
- 暗色模式主页面未发现大片硬编码白底。

## 7. 建议处理顺序

1. 先确认主题边界：哪些 `display: none` 属于“尊重原生隐藏”，哪些应该移除。
2. 修 `manifest.json.php` 暗色输出，这是明确渲染 bug，改动范围小。
3. 决定 table reset 策略：保留全局 denylist，还是改成更局部的 allowlist。
4. 清理重复 topbar/标题按钮/移动端表单规则，降低后续维护成本。
5. 处理 `formora.js` 暗转亮回滚，前提是确认需要支持运行中系统主题切换。
6. 后续逐步变量化组件片段，减少 `style.css.php` 末尾补丁层的压力。

## 8. 附：本轮没有直接改代码的原因

本轮按要求只做审查。若进入修复阶段，建议按上面的处理顺序拆成小批量改动，每批改完都重新执行：

```bash
rsync -a --delete /Users/andyhuang/GitHub/dolibarr-theme-iform/formora/ /Users/andyhuang/Documents/Codex/2026-07-04/iform-dolibarr/work/dolibarr-23.0.0/htdocs/theme/formora/ --exclude=.DS_Store
```

并至少复测：

- 亮色/暗色首页
- 手机创建合作方/产品/发票
- 桌面产品/发票/日程列表
- PWA manifest 输出
- 涉及修改的组件页面
