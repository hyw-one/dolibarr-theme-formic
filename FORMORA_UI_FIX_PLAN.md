# Formora UI 同类问题修复计划

## 背景

本计划基于当前分支 `codex/ui-table-mobile-dark-mode` 上的本地测试和代码审查整理，目标不是只修截图中的四五个点，而是修复同一类组件在整个 Formora 主题中的系统性问题。

测试环境只使用本地地址：

- `http://127.0.0.1:18090`
- 公共参数：`theme=formora&dol_resetcache=1&dol_nocache=1`
- 亮色模式：`THEME_DARKMODEENABLED=0`
- 暗色模式：`THEME_DARKMODEENABLED=2`

后续不得访问或操作线上环境 `erp.hyw.one` / `auth.hyw.one`。

## 已确认的问题范围

### 1. 表格圆角不完整

部分表格本身已有 `border-radius`，但内部 `td` / `th` / `.tagtd` 背景盖住圆角，导致视觉上仍是直角。

已观察到的相关类型：

- 对象详情页字段表格：`table.border.tableforfield`
- 新建页表单表格：`table.border`
- 列表表格：`table.liste`
- 无边框列表：`table.noborder`
- div 模拟表格：`.tagtable`, `.table-border`
- 联系人/地址表格：`.tableforcontact`

初步涉及文件和选择器：

- `formora/global.inc.php`
  - `table.liste`
  - `table.noborder`
  - `table.border`
  - `table.tableforfield`
- `formora/style.css.php`
  - `#mainbody table.liste`
  - `#mainbody table.noborder`
  - `#mainbody table.border`
  - `#mainbody table.tagtable`
  - `#mainbody table.border.tableforfield`

修复原则：

- 不只给 `table` 设置圆角，还要补首行/末行首尾单元格圆角。
- 对 `border-collapse: separate` 的表格统一处理 `border-spacing: 0`。
- 对 `#tablelines`, `#tablelinesservice`, `.paymenttable`, `.margintable`, `.cal_month` 等特殊表格用上下文规则单独处理，避免普通列表规则误伤。

### 2. 移动端动作按钮行被裁切

发票详情页移动端动作按钮行仍是 `flex-wrap: nowrap`，在 390px 宽度下按钮被挤到左侧视口外。

本地复现页面：

- `/compta/facture/card.php?id=160&save_lastsearch_values=1`
- 视口：`390x844`
- 暗色：`THEME_DARKMODEENABLED=2`

实测现象：

- `div.tabsAction` 为 `display: flex`
- `flex-wrap: nowrap`
- “重新开放”“发送电子邮件”等按钮出现负 `x` 坐标，虽然 `document.scrollWidth` 没超出，但按钮已经不可完整操作

初步涉及文件和选择器：

- `formora/btn.inc.php`
  - `div.tabsAction > a.butAction`
  - `.butAction`, `.butActionDelete`, `.butActionRefused`
- `formora/style.css.php`
  - `body#mainbody div.tabsAction`
  - `body#mainbody div.tabsActionNoBottom`
  - `body#mainbody .butAction`

修复原则：

- 移动端允许动作按钮换行：`flex-wrap: wrap`。
- 按钮使用可收缩布局，避免超长中文撑破容器。
- 保留桌面端右对齐按钮行的习惯。
- 下拉动作按钮和拒绝/删除按钮也要纳入同一套间距规则。

### 3. 嵌入式列表表格在移动端过宽

对象详情页内部的附件、联系人、关联文件等列表型表格在移动端实际宽度远大于视口，只是被页面裁切或内部滚动隐藏，不是优雅适配。

本地复现页面：

- `/compta/facture/document.php?id=160`
- `/compta/facture/contact.php?id=160`

实测数据：

- 附件文件表格 `#tablelines.centpercent.liste.noborder.nobottom` 宽度约 `661px`
- 联系人表格 `.tagtable.tableforcontact.centpercent.noborder.nobordertop.allwidth` 宽度约 `670px`
- 联系人空列表 `.tagtable.nobottomiftotal.liste.noborder` 宽度约 `511px`
- 页面整体 `document.scrollWidth` 不一定超出，但表格内容在 390px 视口内不可完整阅读

初步涉及文件和选择器：

- `formora/style.css.php`
  - `.div-table-responsive`
  - `.div-table-responsive-no-min`
  - `table.liste`
  - `.tagtable.liste`
  - `.tableforcontact`
- `formora/global.inc.php`
  - `.tableforcontact`
  - `#tablelines`
  - `.nobottom`
  - `.nobordertop`

修复原则：

- 对详情页内部列表优先提供横向滚动容器，而不是把页面整体撑宽。
- 文件、联系人、关联对象等列表表格保留表格语义，不强行卡片化。
- 滚动容器要有明确边界、圆角和细滚动条。
- 表格宽度可以大于容器，但容器必须限制在 `.fiche` 内。

### 4. 附件/关联/业务卡片式表格外框线不需要

截图中“附件”“已关联的文件”这类嵌入式列表顶部/底部外框线过重。后续用户进一步明确，付款表、利润表、产品明细表、左侧字段详情表等同类卡片式表格也不需要最外圈框线。

本地复现页面：

- `/compta/facture/document.php?id=160`
- `/compta/facture/card.php?id=160&save_lastsearch_values=1`

实测现象：

- 附件文件表格 `#tablelines` 顶部仍有 `1px solid rgb(58, 58, 60)`
- 关联文件空表格顶部也有 `1px solid rgb(58, 58, 60)`
- 发票详情页 `.paymenttable`, `.margintable`, `#tablelines`, `table.border.tableforfield` 等卡片式表格存在最外圈 1px 框线
- 表格内部标题行和数据行仍需要分隔，但最外层上下左右框线应去掉

修复原则：

- 去掉对象详情页嵌入式列表和业务卡片式表格的外层 `border-top` / `border-right` / `border-bottom` / `border-left`。
- 保留标题行底部线和行间线。
- 不影响主列表页 `bodyforlist` 的表格外观。
- 不影响日历、TakePOS 独立页面等特殊表格；需要通过上下文选择器区分。

### 5. 暗色模式菜单覆盖不完整

普通 `.dropdown-menu` 已有暗色覆盖，但标签页更多菜单、旧式弹出 tab、局部菜单可能未命中。

已观察到的相关对象：

- `#moretabsList`
- `#moretabsListaction`
- `div.popuptabset`
- `div.popuptab`
- `.dropdown-menu`
- `.dropdown-content`
- `dl.dropdown dd ul.open`
- Select2 下拉
- 顶部 quickadd / user dropdown

截图问题：

- 移动端点击 `... (3)` / `... (4)` 后，菜单出现白底浅灰文字，不适配暗色模式。

初步涉及文件和选择器：

- `formora/global.inc.php`
  - `div#moretabsList`
  - `div#moretabsListaction`
  - `div.popuptabset`
  - `div.popuptab`
- `formora/dropdown.inc.php`
  - `.dropdown-menu`
  - `.dropdown-item`
  - `.dropdown-search`
  - `.dropdown-bookmark`
  - `.dropdown-quickadd`
- `formora/style.css.php`
  - 暗色层 `THEME_DARKMODEENABLED`
  - Formora 后置覆盖层

修复原则：

- 把“菜单表面”抽象为同一套暗色变量：`--formora-elevated`, `--formora-line`, `--formora-text`, `--formora-neutral-hover-bg`。
- 对 `#moretabsList` / `#moretabsListaction` 单独补规则，不能假设它们一定是 `.dropdown-menu`。
- 保证 hover/focus 状态在亮暗模式都有可见反馈。

### 6. 暗色低强调文字发黑

销售点字段里的 `Takepos - Terminal 1` 在暗色模式下接近黑色，无法阅读。

本地复现页面：

- `/compta/facture/card.php?id=160&save_lastsearch_values=1`

实测定位：

- 文本元素：`span.opacitymediumbycolor.paddingleft`
- 计算色：`rgba(0, 0, 0, 0.4)`
- 源定义：`formora/global.inc.php` 中 `.opacitymediumbycolor { color: rgba(0, 0, 0, 0.4); }`

这不是 TakePOS 单点问题，而是所有 `.opacitymediumbycolor` 在暗色模式下都可能发黑。

初步涉及文件和选择器：

- `formora/global.inc.php`
  - `.opacitymediumbycolor`
  - `.opacitymedium`
  - `.optiongrey`
  - `.opacitylow`
- `formora/style.css.php`
  - 暗色层对 `.opacitymedium`, `.opacitymediumonly`, `.opacitymediumcolor` 已有部分覆盖

修复原则：

- 暗色模式下把 `.opacitymediumbycolor` 映射到 `var(--formora-muted)` 或半透明浅色。
- 不改变亮色模式原本的弱化效果。
- 同时检查 `.opacitylow`, `.opacitymedium`, `.opacitymediumonly`, `.opacitymediumcolor` 是否存在类似问题。

### 7. 日历区域无外框线、保留圆角且完整显示

日历月视图的筛选块和日历表外侧不需要外框线，但整体视觉仍要保留圆角：筛选块保留上方圆角，日历区域保留下方圆角。日历区域本身左上/右上不应再有圆角，同时月视图内容不能被固定高度裁切。

本地复现页面：

- `/comm/action/index.php?idmenu=1&mainmenu=agenda&leftmenu=`

实测定位：

- 筛选块：`form#searchFormList > .liste_titre_bydiv:has(+ .sectioncalendarbymonth)`
- 日历容器：`.sectioncalendarbymonth.maxscreenheightless300`
- 日历表：`table.cal_month.listwithfilterbefore`

修复原则：

- 筛选块：`border: 0`，保留 `border-radius: var(--formora-radius) var(--formora-radius) 0 0`。
- 日历容器和日历表：外框 `border: 0`，保留底部圆角。
- 日历表首行顶部圆角为 `0`，避免和筛选块重复形成圆角。
- 去掉最外侧单元格的左/右/底边线，保留内部网格线。
- 去掉月视图容器的高度裁切，让日历内容完整显示。

## 特殊对象处理策略

这些对象需要谨慎处理，避免通用规则误伤：

- 发票/订单产品明细：`#tablelines`, `#tablelinesservice`，本轮去掉外框线，但保留表头和行间线
- 支付表：`.paymenttable`，本轮去掉外框线，但保留内部结构
- 利润表：`.margintable`，本轮去掉外框线，但保留内部结构
- 日历表：`.cal_month`，不套普通详情表规则，只处理外框线、圆角和高度裁切
- TakePOS 独立页面：`body.bodytakepos`
- 模块管理 Kanban：`/admin/modules.php?mode=commonkanban`
- 产品 Kanban：`/product/list.php?mode=kanban`

本轮不能把嵌入式列表规则无条件套到这些表格上，必须用上下文和排除条件控制影响范围。

## 分阶段修复计划

### 第一阶段：暗色可读性与菜单兜底

目标：

- 修复 `.opacitymediumbycolor` 等低强调文字在暗色模式下发黑。
- 修复 `#moretabsList` / `#moretabsListaction` / `popuptab` 暗色菜单。
- 保证 `.dropdown-menu`, `.dropdown-content`, `dl.dropdown dd ul.open`, Select2 的菜单表面一致。

建议修改：

- 在 `formora/style.css.php` 暗色层中补：
  - `body#mainbody .opacitymediumbycolor`
  - `body#mainbody .optiongrey`
  - 必要时补 `.opacitylow`
- 在 Formora 后置层中补：
  - `#moretabsList`
  - `#moretabsListaction`
  - `#moretabsList a`
  - `#moretabsListaction a`
  - `div.popuptabset`
  - `div.popuptab`

验收：

- 发票详情页“销售点 / Takepos - Terminal 1”在暗色模式可读。
- 移动端更多 tab 菜单不再白底。
- 顶部用户菜单、快速新增菜单、Select2 菜单仍正常。

### 第二阶段：动作按钮响应式

目标：

- 所有详情页动作按钮在移动端不被裁切。
- 按钮可换行，长中文不撑破页面。

建议修改：

- `body#mainbody div.tabsAction`
- `body#mainbody div.tabsActionNoBottom`
- `body#mainbody div.tabsAction > ...butAction...`

移动端策略：

- `flex-wrap: wrap`
- `justify-content: flex-start` 或保留合理右对齐，按实际页面验证决定
- `gap: 8px`
- 按钮 `max-width: 100%`
- 长按钮允许正常换行或使用 `white-space: normal`

验收：

- `/compta/facture/card.php?id=160...` 在 `390x844` 下所有动作按钮可见可点。
- `430x932`, `768x1024`, `1280x900` 不退化。
- 删除/拒绝按钮仍有正确颜色和禁用态。

### 第三阶段：详情页嵌入式列表统一

目标：

- 附件、联系人、关联文件、相关项目等对象详情页内部列表不再裁切。
- 保留表格结构，并限制在 `.fiche` 容器内滚动。

建议修改：

- 对非 `bodyforlist` 的详情页增加受控横向滚动：
  - `.tabBar .div-table-responsive`
  - `.tabBar .div-table-responsive-no-min`
  - `.tabBar table.liste`
  - `.tabBar .tagtable.liste`
  - `.tabBar .tableforcontact`
- 需要避免作用到：
  - `.cal_month`
  - `body.bodytakepos`

验收：

- `/compta/facture/document.php?id=160`
- `/compta/facture/contact.php?id=160`
- `/compta/facture/card.php?id=160`
- 手机端页面整体不横向滚动，表格区域可独立横向滚动。

### 第四阶段：表格边框与圆角统一

目标：

- 表格外观一致，圆角真实可见。
- 附件/关联类列表去掉不必要外框线。
- 付款、利润、产品明细、字段详情等卡片式表格去掉最外圈框线。

建议修改：

- 统一 `table.liste`, `table.noborder`, `table.border`, `.tagtable.liste` 的圆角策略。
- 对嵌入式列表和业务卡片式表格增加外框线减法规则。
- 对首行/末行单元格补圆角。

验收：

- 截图中的合作方/联系人编辑表格有圆角。
- 附件表格无突兀外框线。
- 付款、利润、产品明细、左侧字段详情表无外框线，内部行分隔仍可读。
- 主列表页仍保持清晰边界。
- 亮色和暗色模式一致。

### 第五阶段：回归与特殊页面抽样

目标：

- 确认通用规则没有污染特殊页面。

测试页面：

- `/index.php`
- `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`
- `/societe/card.php?action=create`
- `/contact/list.php`
- `/product/list.php?type=0`
- `/product/list.php?mode=kanban&contextpage=productlist&type=0&search_type=0`
- `/commande/card.php?action=create&leftmenu=orders`
- `/compta/facture/list.php?leftmenu=customers_bills`
- `/compta/facture/card.php?id=160&save_lastsearch_values=1`
- `/compta/facture/document.php?id=160`
- `/compta/facture/contact.php?id=160`
- `/admin/modules.php?mainmenu=home&leftmenu=setup`
- `/takepos/index.php?idmenu=65&mainmenu=takepos&leftmenu=`

测试视口：

- `390x844`
- `430x932`
- `768x1024`
- `900x900`
- `1024x768`
- `1280x900`
- `1440x900`
- `1728x1117`

每页记录：

- `document.documentElement.scrollWidth`
- `window.innerWidth`
- 是否整体横向溢出
- `#id-left`, `.side-nav`, `#id-right`, `.fiche` 的位置和宽度
- 主要表格是否被裁切
- 动作按钮是否可见
- 菜单/下拉是否适配亮暗模式

## 当前执行记录

已执行并同步到本地测试环境：

- 分支：`codex/ui-table-mobile-dark-mode`
- 修改文件：`formora/style.css.php`
- 同步目标：`/Users/andyhuang/Documents/Codex/2026-07-04/iform-dolibarr/work/dolibarr-23.0.0/htdocs/theme/formora/style.css.php`

已验证：

- `php -l formora/style.css.php` 通过。
- `node --check formora/formora.js` 通过。
- `git diff --check -- formora/style.css.php formora/formora.js` 通过。
- 发票详情页 `/compta/facture/card.php?id=160...`：字段详情表、付款表、利润表、产品明细表外框均为 `0px`，销售点文字为暗色可读色。
- 附件页 `/compta/facture/document.php?id=163...`：附件表和链接表外框均为 `0px`。
- 发票列表页 `/compta/facture/list.php?leftmenu=customers_bills...`：筛选区下方表格顶部圆角为 `0px`，底部圆角保留，页面无横向溢出。
- 日历页 `/comm/action/index.php?idmenu=1&mainmenu=agenda&leftmenu=...`：筛选块无外框线且保留上方圆角，日历表和容器无外框线且保留下方圆角，月视图未裁切。
- 移动端 `390x844` 发票详情页：动作按钮行 `flex-wrap: wrap`，页面无横向溢出。

## 验证命令

每次修改后至少运行：

```bash
php -l formora/style.css.php
node --check formora/formora.js
git diff --check -- formora/style.css.php formora/formora.js
```

如果只改 CSS PHP，仍建议运行 `php -l` 和 `git diff --check`。

## 交付顺序建议

1. 先修暗色低强调文字和菜单，风险最低，收益明确。
2. 再修移动端动作按钮行，避免详情页关键操作不可见。
3. 再修嵌入式列表表格的滚动容器和外框线。
4. 最后统一表格圆角，避免先改圆角后又被列表容器规则覆盖。
5. 每阶段都在亮色/暗色、手机/桌面各抽样验证。

## 风险控制

- 不要在 `global.inc.php` 里大范围改 Dolibarr 基础规则，优先在 `style.css.php` Formora 覆盖层收口。
- 尽量用 `body#mainbody`、页面类型、容器上下文限制选择器。
- 不为单个文本 `Takepos - Terminal 1` 写规则，应该修 `.opacitymediumbycolor` 这类全局暗色问题。
- 不直接删除所有表格边框，应区分主列表、嵌入式列表、业务明细表。
- 不依赖线上环境验证，所有测试只走 `127.0.0.1:18090`。
