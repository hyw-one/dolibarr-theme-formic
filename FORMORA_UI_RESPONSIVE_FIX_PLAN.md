# Dolibarr Formora 主题 UI / 响应式修复执行方案

制定日期：2026-07-06  
依据报告：`FORMORA_UI_RESPONSIVE_AUDIT.md`  
当前确认方向：

- 1024x768 归入平板布局，不默认固定左栏占位。
- 手机 Kanban/card 视图采用单列卡片列表。
- 顶部菜单在手机和平板下继续横向滚动，但必须提供清晰滚动提示。
- 审查截图和 `audit-data.json` 后续可以移除，不进入正式修复提交。

## 总体原则

本次修复不建议从单个页面的局部样式开始。报告显示当前主要风险来自全局断点、表单行模型、列表工具栏、Kanban/card 容器和交互状态规则的叠加。执行顺序应先稳定基础布局，再统一页面族，最后做特殊页面和微交互打磨。

每个阶段完成后都应跑对应页面回归；最终跑完整矩阵：18 个重点页面 × 2 种主题 × 8 个视口，共 288 个状态。

## 阶段 0：清理审查产物与建立修复基线

目标：

- 保留中文审查报告作为依据。
- 移除大体积审查数据和截图，避免正式修复提交包含 16MB JSON 和截图目录。
- 建立后续自动化回归矩阵。

具体动作：

- 保留 `FORMORA_UI_RESPONSIVE_AUDIT.md`。
- 后续修复分支中移除 `formora-ui-audit-screenshots/`。
- 可从当前审查分支继续，或新切 `codex/formora-responsive-fixes`。
- 准备自动化检查脚本或临时采集流程，至少输出：
  - URL
  - 主题模式
  - viewport
  - `document.scrollWidth / window.innerWidth`
  - `#id-left`、`.side-nav`、`#id-right`、`.fiche` 几何信息
  - 可疑页面截图

验收标准：

- 正式修复提交不包含审查截图和大 JSON 数据。
- 有可重复的回归矩阵检查方式。

## 阶段 1：基础断点与主布局稳定

目标：

- 将 1024x768 纳入平板布局。
- 390、430、768、900、1024 下左栏不固定占位。
- 1280 及以上保持桌面固定左栏。
- 解决 1024x768 “左栏已固定、顶部菜单仍处于移动横滚态”的混合状态。

涉及文件：

- `formora/global.inc.php`
- `formora/formora.js`

重点选择器：

- `#id-left`
- `.side-nav`
- `#id-right`
- `.fiche`
- `#tmenu_tooltip`
- `li.tmenu`
- `.formora-tmenu-is-scrollable`
- `.formora-tmenu-can-scroll-left`
- `.formora-tmenu-can-scroll-right`

具体动作：

- 统一手机、平板、窄桌面、桌面的断点定义。
- 调整左栏显示断点，使 1024x768 不固定占位。
- 调整 `#id-right` 和 `.fiche` 在 1024 及以下的宽度和 margin。
- 保证 1280 以上仍沿用桌面侧栏布局。

验收标准：

- 390/430/768/900/1024 下：
  - `document.scrollWidth <= window.innerWidth`
  - `#id-left` 不占有效布局宽度
  - `.side-nav` 不固定占位
  - `#id-right` 占满内容宽度
- 1280/1440/1728 下：
  - 左栏固定显示稳定
  - 内容区不被异常推出

## 阶段 2：顶部菜单横向滚动体验

目标：

- 保留手机和平板下顶部菜单横向滚动。
- 让用户明显知道顶部菜单可以左右滑动。
- 亮色和暗色模式都要有清晰反馈。

涉及文件：

- `formora/global.inc.php`
- `formora/formora.js`

重点选择器：

- `#tmenu_tooltip`
- `ul.tmenu`
- `li.tmenu`
- `.formora-tmenu-is-scrollable`
- `.formora-tmenu-can-scroll-left`
- `.formora-tmenu-can-scroll-right`

具体动作：

- 利用现有 JS 状态类补 CSS 视觉提示。
- 在可向右滚动时显示右侧渐隐、阴影或边缘提示。
- 在可向左滚动时显示左侧渐隐、阴影或边缘提示。
- 暗色模式下使用低干扰但可见的提示色。
- 不改成抽屉，不隐藏全量菜单。

验收标准：

- 390/430/768/900/1024 下顶部菜单仍可横向滚动。
- 右侧还有菜单项时提示明显。
- 滚动到中间或右侧后，左侧提示正确出现。
- TakePOS 和登录页不被顶部菜单提示规则污染。

## 阶段 3：移动端表单行模型

目标：

- 修复 P1 表单重叠问题。
- 手机下创建页表单成为稳定单列布局。
- select2、日期框、help icon、CKEditor 不再与 label/input 互相覆盖。

涉及文件：

- `formora/global.inc.php`

重点页面：

- `/societe/card.php?action=create`
- `/product/card.php?action=create&type=0`
- `/projet/card.php?action=create`

重点选择器：

- `.tableforfield`
- `.tableforfieldcreate`
- `.titlefield`
- `.titlefieldcreate`
- `.valuefield`
- `.maxwidthonsmartphone`
- `.select2-container`
- `.pictofixedwidth`
- `.cke`

具体动作：

- 在手机断点下统一表单行显示模型。
- 避免 `td` 已变为 block，但 `tr` 仍保持旧高度。
- 让 label、value、辅助图标变成明确的单列或两行结构。
- 给字段组设置稳定 `gap`、`line-height`、`min-height:auto`。
- select2 和日期框宽度使用容器宽度，不使用会撑布局的固定宽度。
- CKEditor 工具栏在手机下继续精简，但编辑器外框和 label 间距要稳定。

验收标准：

- 合作方、产品、项目创建页在 390/430 下无 label/input/icon 重叠。
- 768/1024 下表单不出现异常空白或过度压缩。
- 暗色模式同样可读。
- 不引入 document 级横向溢出。

## 阶段 4：列表页 toolbar 与筛选区统一

目标：

- 统一列表页标题、分页、视图切换、新增按钮、筛选区、批量操作区。
- 手机和平板下减少拥挤、重叠和过度截断。
- 保留表格内部滚动能力，但让滚动边界更清楚。

涉及文件：

- `formora/global.inc.php`
- `formora/btn.inc.php`

重点页面：

- `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`
- `/contact/list.php`
- `/product/list.php?type=0`
- `/projet/list.php?leftmenu=projects`
- `/comm/propal/list.php?leftmenu=propals`
- `/commande/list.php?leftmenu=orders`
- `/compta/facture/list.php?leftmenu=customers_bills`

重点选择器：

- `.table-fiche-title`
- `.paginationafterarrows`
- `.btnTitle`
- `.liste_titre_filter`
- `.selectmassaction`
- `.div-table-responsive`
- `table.liste`

具体动作：

- 手机下标题独占一行。
- 分页、视图切换、创建按钮分组换行。
- 筛选区使用 grid/flex，不继续依赖多个 `maxwidth*` 类互相挤压。
- 批量操作区独立成行，避免与表头混杂。
- 对内部横向滚动的表格补边缘提示。

验收标准：

- 390/430/768/1024 下列表工具栏不重叠。
- 筛选框最小宽度可读。
- 批量复选框、表头、筛选行层级清楚。
- 所有列表页亮暗模式表现一致。

## 阶段 5：Kanban/card 单列卡片

目标：

- 手机下产品 Kanban 和订单 Kanban 改为单列卡片列表。
- 不再出现半张卡片被裁切。
- 价格、状态灯、复选框、按钮区域保持可读。

涉及文件：

- `formora/info-box.inc.php`
- `formora/global.inc.php`

重点页面：

- `/product/list.php?mode=kanban&contextpage=productlist&type=0&search_type=0`
- `/commande/list.php?mode=kanban&contextpage=orderlist`

重点选择器：

- `.box-flex-container`
- `.box-flex-item`
- `.kanban`
- `.kanbanlabel`
- `.div-table-responsive`
- `.info-box-status.amount`

具体动作：

- 手机下让 Kanban 脱离普通表格表头宽度。
- 将卡片容器切换为一列 grid/flex。
- 卡片宽度使用 `100%` 或 `minmax(0, 1fr)`。
- 价格、状态灯、复选框、标题、摘要建立固定区域。
- 768/1024 可根据空间使用一列或两列，但不得继承大表格横向宽度。

验收标准：

- 390/430 下产品和订单 Kanban 单列显示。
- 不出现内部横向裁切作为主要浏览方式。
- 价格和状态灯可见，不互相覆盖。
- 亮色和暗色下卡片边界一致。

## 阶段 6：模块页与 TakePOS 特殊处理

目标：

- 模块页跟随统一 toolbar/filter 规则，再做局部微调。
- TakePOS 只在 `.bodytakepos` 下处理，避免污染普通页面。

涉及文件：

- `formora/global.inc.php`
- `formora/info-box.inc.php`
- `formora/formora.js`
- 必要时新增或调整 `.bodytakepos` 限定规则

重点页面：

- `/admin/modules.php?mainmenu=home&leftmenu=setup`
- `/admin/modules.php?mode=commonkanban`
- `/takepos/index.php?idmenu=65&mainmenu=takepos&leftmenu=`

具体动作：

- 模块页筛选块减少突兀灰底，统一控件间距和换行。
- 模块列表和模块 Kanban 继承卡片阶段的响应式规则。
- TakePOS 手机优化顶部栏、数字键盘、操作按钮、分类 tab。
- TakePOS 桌面暗色优化商品格、图片占位、空白格。
- TakePOS 规则必须限定在 `.bodytakepos` 下。

验收标准：

- 模块页 390/430/768/1024 控件不拥挤。
- TakePOS 390 下不横向溢出，触控按钮仍有足够点击面积。
- TakePOS 1024 暗色下商品格不显得大面积异常空白。
- 普通列表/表单页面不受 TakePOS 规则影响。

## 阶段 7：hover/focus/active 与视觉统一

目标：

- 恢复可见 focus。
- 统一按钮、链接、图标按钮、tabs、输入框、select2 的交互反馈。
- 收敛圆角、边框、背景层级。

涉及文件：

- `formora/btn.inc.php`
- `formora/global.inc.php`
- `formora/search-input.inc.css`
- `formora/dropdown.inc.php`

重点选择器：

- `.btnTitle`
- `.butAction`
- `.butActionNew`
- `.ui-btn`
- `.ui-focus`
- `input:focus`
- `.select2-container--focus`
- `a.tab`
- `.dropdown-menu`

具体动作：

- 定义统一 focus 样式，例如明确 outline 或 box-shadow。
- 移除或覆盖导致 focus 不可见的 `outline: none`。
- hover/active 使用统一 token，亮暗模式都要可见。
- selected、disabled、reposition 状态不能吞掉 hover/focus 反馈。
- 微调圆角、边框、背景，避免模块页、列表页、卡片页风格割裂。

验收标准：

- 键盘 Tab 导航可见焦点。
- 亮色和暗色 hover/focus/active 都有反馈。
- 图标按钮、文字按钮、链接按钮反馈一致。
- 不靠新增大量 `!important` 完成覆盖。

## 最终回归矩阵

最终必须覆盖以下页面：

- `/index.php`
- `/societe/index.php?mainmenu=companies&leftmenu=`
- `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`
- `/societe/card.php?action=create`
- `/contact/list.php`
- `/product/list.php?type=0`
- `/product/list.php?mode=kanban&contextpage=productlist&type=0&search_type=0`
- `/product/card.php?action=create&type=0`
- `/projet/list.php?leftmenu=projects`
- `/projet/card.php?action=create`
- `/comm/propal/list.php?leftmenu=propals`
- `/commande/list.php?leftmenu=orders`
- `/commande/list.php?mode=kanban&contextpage=orderlist`
- `/compta/facture/list.php?leftmenu=customers_bills`
- `/compta/facture/card.php?id=160&save_lastsearch_values=1`
- `/admin/modules.php?mainmenu=home&leftmenu=setup`
- `/admin/modules.php?mode=commonkanban`
- `/takepos/index.php?idmenu=65&mainmenu=takepos&leftmenu=`

最终必须覆盖以下 viewport：

- 390x844
- 430x932
- 768x1024
- 900x900
- 1024x768
- 1280x900
- 1440x900
- 1728x1117

最终必须覆盖以下主题：

- 亮色：`THEME_DARKMODEENABLED=0`
- 暗色：`THEME_DARKMODEENABLED=2`

最终验收指标：

- `document.scrollWidth <= window.innerWidth`
- 390/430/768/900/1024 下左栏不固定占位
- 1280/1440/1728 下桌面左栏稳定
- 手机创建页无表单重叠
- 手机 Kanban/card 为单列卡片列表
- 顶部菜单横向滚动提示可见
- 列表页 toolbar 和筛选区不拥挤、不重叠
- TakePOS 不受普通列表/表单规则副作用影响
- hover/focus/active 在亮暗模式下都有可见反馈

