# Dolibarr Formora 主题 UI / 响应式审查报告

审查日期：2026-07-06  
审查分支：`codex/formora-ui-responsive-audit`  
测试环境：`http://127.0.0.1:18090`  
主题参数：`theme=formora&dol_resetcache=1&dol_nocache=1`  
亮色模式：`THEME_DARKMODEENABLED=0`  
暗色模式：`THEME_DARKMODEENABLED=2`

## 审查范围与产物

本次审查覆盖用户指定的 18 个重点 URL、2 种主题模式、8 个视口，共 288 个自动化页面状态。自动化采集字段包括 URL、主题模式、viewport、`document.scrollWidth / window.innerWidth`、是否存在 document 级横向溢出、`#id-left`、`.side-nav`、`#id-right`、`.fiche` 的 x/width/display/position，以及列表、表单、Kanban、按钮和菜单相关的抽样 DOM 几何信息。

原始数据：

- `formora-ui-audit-screenshots/audit-data.json`
- `formora-ui-audit-screenshots/audit-progress.json`

截图证据：

- `formora-ui-audit-screenshots/01-*.png` 到 `formora-ui-audit-screenshots/72-*.png`
- 其中 `71-light-390x844-takepos.png`、`72-dark-1024x768-takepos.png` 为 TakePOS 单独补充截图。

本轮没有修改主题 CSS/JS/PHP 业务文件，没有提交，没有推送。

## 总体结论

Formora 当前没有出现 document 级横向溢出的系统性故障：288 个状态中 `document.scrollWidth > window.innerWidth` 为 0。手机、平板下左侧菜单也没有固定占位，`#id-left` 保持 `display: table-cell` 但宽度为 0，`.side-nav` 为 `display: none`，右侧内容区占满视口。

但主题不适合直接进入零散细节修补。建议先整理基础布局规则，再做细节统一。原因是最严重的问题不是单一页面溢出，而是几类全局规则在小屏下叠加：表单表格行在移动端被改成块级布局但 `tr` 高度仍保持旧值，导致 label、输入框、图标和脚本占位相互覆盖；Kanban/card 视图在 `.div-table-responsive` 内部形成横向滚动和内容裁切；顶部菜单在 1024px 及以下普遍可横向滚动，但滚动提示不明显；1024px 刚进入固定左栏布局时内容区偏窄。

结论：主题基础框架可以继续沿用，但第一阶段应先稳定断点、表单行模型、列表/kanban 的内部滚动策略和 focus/hover 可见反馈。局部硬修某个页面容易污染全局。

## 断点矩阵

| 视口范围 | 实测视口 | 左栏行为 | 顶部菜单行为 | 内容区行为 | 结论 |
|---|---:|---|---|---|---|
| 手机 | 390x844、430x932 | `#id-left display: table-cell; width: 0`；`.side-nav display: none` | `#tmenu_tooltip` 内部横向滚动，17/18 页面检测到 `scrollWidth > clientWidth` | `#id-right x=0 width=viewport`；`.fiche` 左右约 9-10px | 没有 document 溢出，左栏未占位；表单和 Kanban 内部布局问题明显 |
| 平板竖屏 | 768x1024 | 左栏仍隐藏，宽度 0 | 顶部菜单仍内部横向滚动，17/18 页面触发 | `.fiche x=13 width=741` | 列表页可用，但筛选/分页/标题密度高 |
| 小桌面/平板横向 | 900x900 | 左栏仍隐藏，宽度 0 | 顶部菜单仍内部横向滚动，17/18 页面触发 | `.fiche x=13 width=873` | 行为更接近平板；顶部菜单提示仍弱 |
| 窄桌面 | 1024x768 | 左栏固定显示：`#id-left width=260`，`.side-nav width=261` | 顶部菜单仍内部横向滚动，17/18 页面触发 | `#id-right x=261 width=763`；首页 `.fiche x=267 width=751` | 固定左栏出现得偏早，内容区被压窄 |
| 桌面 | 1280x900、1440x900 | 左栏固定显示 260px | 顶部菜单不再横向滚动 | 内容区分别约 1019px / 1179px | 基本稳定 |
| 宽桌面 | 1728x1117 | 左栏固定显示 260px | 顶部菜单不再横向滚动 | 内容区约 1467px | 基本稳定 |

代表性实测数据：

- 390x844 首页：`documentScrollWidth=390`，`#id-left width=0`，`#id-right width=390`，`.fiche width=371`。
- 768x1024 合作方列表：`documentScrollWidth=768`，`#id-left width=0`，`#id-right width=768`，`.fiche width=741`。
- 1024x768 首页：`documentScrollWidth=1024`，`#id-left width=260`，`#id-right x=261 width=763`，`.fiche x=267 width=751`。
- 1280x900 首页：`documentScrollWidth=1280`，`#id-left width=260`，`#id-right width=1019`，顶部菜单无内部滚动。

## 问题清单

### P0

未发现 P0。没有页面在本轮矩阵中出现 document 级严重溢出、页面整体被推出显示范围或无法进入的情况。

### P1-1：手机创建页表单行重叠，label、输入框、图标互相覆盖

- 页面 URL：`/societe/card.php?action=create`、`/product/card.php?action=create&type=0`、`/projet/card.php?action=create`
- 主题模式：亮色、暗色均复现
- viewport：390x844、430x932 最明显；768x1024 缓解但仍应回归验证
- 复现步骤：访问创建页，使用手机视口，保持默认页面顶部位置或向下查看表单字段。
- 实际表现：合作方创建页中“客户代码/供应商代码/邮政编码/国家/电话/电子邮件/社交网络”等字段出现纵向覆盖；产品创建页中“标签/状态/条码/描述/仓库/库存预警”等字段堆叠紧贴；项目创建页中日期、预算、描述、标签等区域出现 label 与输入框交叠。截图：`03-light-390x844-societe-card-php-action-create.png`、`44-dark-390x844-societe-card-php-action-create.png`、`05-light-390x844-product-card-php-action-create-type-0.png`、`06-light-390x844-projet-card-php-action-create.png`。
- 期望表现：移动端表单应转换为稳定单列布局；每个 label、输入控件、说明图标、select2、日期控件、富文本编辑器之间应有明确行高和间距，不互相覆盖。
- 初步原因推断：移动端将表单行内单元格改为块级显示后，`tr` 本身仍保留约 40px 高度，子 `td` 实际纵向堆叠超过父行高度，后续行继续按旧高度定位，造成覆盖。对合作方创建页抽样：`.tableforfieldcreate tr` 的 `h=40`，但同一行内 2-4 个 `td` 块分别位于 `y=289.5/333.5/377.5/421.5`，父行高度没有随子项展开。
- 涉及 CSS/JS 文件和大致选择器：`formora/global.inc.php` 中移动端左栏/内容规则 `@media max-width: reduction3` 约 2709-2761；`.titlefield`、`.tableforfield` 约 9221-9228；jMobile/form 控件规则约 8125-8238；CKEditor 小屏规则约 6917-6924；相关选择器包括 `.tableforfieldcreate tr`、`.tableforfield tr`、`td.titlefieldcreate`、`.titlefield`、`.maxwidthonsmartphone`、`.select2-container`、`.pictofixedwidth`。
- 修复建议：先统一移动端表单行模型。不要只调某个页面的 margin；应在手机断点下让表单容器从 table row 语义切换为真正的 block/grid/flex 行模型，或确保 `tr`、`tbody`、`td` 的 display/height 同步展开。给 label、value、help/icon 区域定义稳定的单列栅格和 `min-height:auto`，select2/date/ckeditor 单独纳入同一规则。
- 是否可能影响全局：是。创建/编辑页、详情页内嵌编辑表单、外部字段、select2、日期框、富文本编辑器都可能受影响。应作为第一阶段基础布局修复，不建议页面级硬修。

### P1-2：Kanban/card 视图在手机下变成内部横向滚动，卡片内容被裁切

- 页面 URL：`/product/list.php?mode=kanban&contextpage=productlist&type=0&search_type=0`、`/commande/list.php?mode=kanban&contextpage=orderlist`
- 主题模式：亮色、暗色均复现
- viewport：390x844、430x932 明显；768x1024 仍需优化；900x900 起可用性改善
- 复现步骤：手机视口进入产品 Kanban 或销售订单 Kanban。
- 实际表现：document 不溢出，但 Kanban 区域内部被 `.div-table-responsive` 横向滚动包住；可见区域只显示部分卡片列，价格、状态灯、复选框、右侧卡片列出现裁切。产品 Kanban 抽样：`.div-table-responsive clientWidth=371`，`scrollWidth=1029`；`#tmenu_tooltip clientWidth=274`，`scrollWidth=1240`。截图：`04-light-390x844-product-list-php-mode-kanban-contextpage-productlist-type-0-search-type-0.png`、`53-dark-430x932-product-list-php-mode-kanban-contextpage-productlist-type-0-search-type-0.png`、`07-light-390x844-commande-list-php-mode-kanban-contextpage-orderlist.png`。
- 期望表现：手机下 Kanban/card 应成为单列或可明确横向滚动的卡片列表；标题列和卡片宽度不应制造“半张卡露出”的裁切感，价格/状态灯/复选框不应被压缩到不可读。
- 初步原因推断：Kanban 与列表表头/响应式表格容器混用，`.div-table-responsive` 保留横向滚动；卡片基础宽度在 `formora/info-box.inc.php` 中为 `.box-flex-item width: 290px`，768 以下改 280px，480 以下改 250px，但表头列和卡片列仍按列表宽度参与布局。
- 涉及 CSS/JS 文件和大致选择器：`formora/info-box.inc.php` 约 564-680，`.box-flex-container`、`.box-flex-container-columns`、`.box-flex-item`、`.info-box-module`；`formora/global.inc.php` 中列表/响应式表格相关选择器 `.div-table-responsive`、`table.liste`、`.liste_titre_filter`。
- 修复建议：将 list mode 和 kanban mode 的布局策略分开。手机下 Kanban 不应继承完整列表表头宽度；建议对 `mode=kanban` 的容器使用单列 grid/flex，隐藏或改造表头，只保留筛选和卡片本体；价格/状态灯/操作区使用固定小区域并允许文本换行或省略。
- 是否可能影响全局：是。产品、订单、模块 Kanban 和 dashboard 卡片都可能受 `.box-flex-item`、`.box-flex-container` 调整影响。需要建立卡片断点策略后再改。

### P2-1：顶部主菜单在 1024px 及以下普遍横向滚动，但视觉提示不足

- 页面 URL：除 TakePOS 外的重点页面几乎全部触发，例如 `/index.php`、`/societe/list.php?leftmenu=thirdparties&mainmenu=companies`、`/admin/modules.php?mainmenu=home&leftmenu=setup`
- 主题模式：亮色、暗色均复现
- viewport：390x844、430x932、768x1024、900x900、1024x768
- 复现步骤：在上述视口访问任一普通页面，观察顶部主菜单。
- 实际表现：17/18 页面在每个 1024px 及以下视口都检测到顶部菜单内部 `scrollWidth > clientWidth`；页面 body 有 `formora-tmenu-is-scrollable formora-tmenu-can-scroll-right`，但视觉上滚动提示很弱，用户不容易发现后面还有菜单项。
- 期望表现：若横向滚动是设计选择，应有清晰的左右 fade、箭头、阴影或滚动边界提示；若不是设计选择，应在更早断点隐藏文本或转入菜单抽屉。
- 初步原因推断：`formora/formora.js` 已在 404-449 行附近维护 `formora-tmenu-is-scrollable`、`formora-tmenu-can-scroll-left/right` 状态；CSS 侧对提示的视觉表达不足。顶部菜单 reduction 规则在 `formora/global.inc.php` 约 9032-9219，仍依赖动态菜单数量计算。
- 涉及 CSS/JS 文件和大致选择器：`formora/formora.js` 404-449；`formora/global.inc.php` 3144-3160、3168-3325、9032-9219；选择器 `#tmenu_tooltip`、`ul.tmenu`、`li.tmenu`、`div.tmenucenter`、`.formora-tmenu-*`。
- 修复建议：先明确 390-1024 的顶部导航策略。若保留横向滚动，应补齐 `.formora-tmenu-can-scroll-right/left` 的可见 affordance；若转抽屉，应减少横向菜单长度，并保证快速新增、用户菜单、搜索仍可访问。
- 是否可能影响全局：是。顶部主菜单是全局组件，应避免在单个页面覆盖。

### P2-2：列表页标题、筛选、分页和批量操作区在手机/平板下密度偏高

- 页面 URL：`/societe/list.php?leftmenu=thirdparties&mainmenu=companies`、`/contact/list.php`、`/product/list.php?type=0`、`/projet/list.php?leftmenu=projects`、`/comm/propal/list.php?leftmenu=propals`、`/commande/list.php?leftmenu=orders`、`/compta/facture/list.php?leftmenu=customers_bills`
- 主题模式：亮色、暗色均复现
- viewport：390x844、430x932、768x1024，1024x768 仍偏紧
- 复现步骤：进入任一列表页，观察标题区、分页、视图切换、快速新增按钮、筛选区、表头和批量复选框。
- 实际表现：手机下标题区、页大小选择、页码、上一页/下一页、list/kanban 按钮、加号按钮挤在首屏上方；筛选区多行排列，某些筛选框只剩 75px 左右宽度；表头仍展示多列，内容通过内部横向滚动或裁切处理。合作方列表截图：`02-light-390x844-societe-list-php-leftmenu-thirdparties-mainmenu-companies.png`、`43-dark-390x844-societe-list-php-leftmenu-thirdparties-mainmenu-companies.png`、`17-light-768x1024-societe-list-php-leftmenu-thirdparties-mainmenu-companies.png`。
- 期望表现：手机下列表工具栏应分组换行，分页、视图切换、创建按钮各自有稳定区域；筛选区应使用一列或两列响应式布局，最小宽度可读；批量操作和表头不应与筛选行视觉混杂。
- 初步原因推断：`.table-fiche-title`、`.paginationafterarrows`、`.btnTitle`、`.liste_titre_filter`、`.maxwidth*` 等规则分别处理，没有统一的列表工具栏容器模型；按钮在 `formora/btn.inc.php` 约 286-419 缩小 min-width，但没有重排策略。
- 涉及 CSS/JS 文件和大致选择器：`formora/btn.inc.php` 286-419；`formora/global.inc.php` 9310-9363；相关选择器 `.table-fiche-title`、`.paginationafterarrows`、`.btnTitle`、`.liste_titre_filter`、`.selectmassaction`、`.div-table-responsive`。
- 修复建议：建立列表页移动端 toolbar 规范：标题独占一行；分页和视图按钮一行；筛选区使用 CSS grid；批量操作固定在表格上方独立行。不要继续以单个 `.btnTitle`、单个 `.maxwidth` 硬调。
- 是否可能影响全局：是。所有 Dolibarr 列表页共享这些选择器。

### P2-3：1024x768 已进入固定左栏，但顶部菜单仍按移动/滚动逻辑处理，断点不一致

- 页面 URL：`/index.php`、`/societe/list.php?leftmenu=thirdparties&mainmenu=companies`、`/admin/modules.php?mainmenu=home&leftmenu=setup`
- 主题模式：亮色、暗色均复现
- viewport：1024x768
- 复现步骤：使用 1024x768 访问普通页面。
- 实际表现：左侧菜单固定显示，`#id-left width=260`、`.side-nav width=261`、`#id-right x=261 width=763`；同时顶部菜单仍为内部横向滚动状态。页面整体不溢出，但内容区变成窄桌面，模块页和列表页密度明显升高。
- 期望表现：1024px 应有一致策略：要么仍按平板隐藏左栏，把内容区保持宽；要么固定左栏同时让顶部菜单进入桌面稳定态，避免双重压缩。
- 初步原因推断：左栏隐藏断点来自动态 `THEME_ELDY_WITDHOFFSET_FOR_REDUC3`，顶部菜单滚动状态 JS 使用 `max-width: 1180px` 判断；两套断点没有统一。
- 涉及 CSS/JS 文件和大致选择器：`formora/global.inc.php` 2709-2768、9032-9219；`formora/formora.js` 416-438；选择器 `#id-left`、`.side-nav`、`#id-right`、`#tmenu_tooltip`。
- 修复建议：统一“平板/窄桌面/桌面”的断点定义。建议把固定左栏出现点与顶部菜单稳定点合并或明确错位区间；至少为 1024x768 列表/模块页减少左栏或提供折叠态。
- 是否可能影响全局：是。属于基础布局规则。

### P2-4：模块页筛选区背景块和控件布局在手机下显得突兀

- 页面 URL：`/admin/modules.php?mainmenu=home&leftmenu=setup`、`/admin/modules.php?mode=commonkanban`
- 主题模式：亮色、暗色均复现，亮色更明显
- viewport：390x844、430x932、768x1024、900x900
- 复现步骤：进入模块页或模块 Kanban 页，查看“可用的应用程序/模块”下方筛选与列表/卡片切换区域。
- 实际表现：手机下筛选控件包在大块灰色背景中，输入框、下拉、刷新按钮换行拥挤；列表/卡片切换按钮与筛选区域距离较散。截图：`09-light-390x844-admin-modules-php-mainmenu-home-leftmenu-setup.png`、`10-light-390x844-admin-modules-php-mode-commonkanban.png`、`50-dark-390x844-admin-modules-php-mainmenu-home-leftmenu-setup.png`。
- 期望表现：模块筛选应与普通列表筛选有一致的间距、背景和换行规则；灰色背景不应像异常块，也不应把控件压在一起。
- 初步原因推断：模块页自身结构与全局列表按钮/筛选控件样式叠加；模块卡片还受 `.box-flex-item`、`.info-box-module` 宽度规则影响。
- 涉及 CSS/JS 文件和大致选择器：`formora/info-box.inc.php` 644-680、695-710；`formora/global.inc.php` 列表/表单控件规则；选择器 `.info-box-module`、`.box-flex-container`、`.box-flex-item`、`.fiche`、`.btnTitle`。
- 修复建议：模块页可以保留局部规则，但应基于统一的列表 toolbar / filter 组件变量，而不是单独堆背景。先完成列表页统一后再处理模块页。
- 是否可能影响全局：中等。模块页特殊，但卡片样式是全局共享。

### P2-5：hover/focus/active 反馈不一致，部分 focus 完全不可见

- 页面 URL：抽样 `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`，同时适用于按钮、链接、图标按钮全局
- 主题模式：亮色抽样；暗色从截图看 hover/focus 风险同样存在
- viewport：390x844 抽样；应扩展到所有断点
- 复现步骤：移动鼠标到标题图标按钮；点击列表筛选输入框获取 focus；观察 computed style。
- 实际表现：筛选输入 focus 后 `outline: none`、`box-shadow: none`，仅边框保持原样；标题图标按钮 hover 前后 computed style 基本无变化。抽样结果：`input.flat.searchstring.maxwidth75imp` focus 后 `outline: rgb(29,29,31) none 3px`、`boxShadow: none`；`a.btnTitle.reposition.btnTitleSelected` hover 前后背景/边框/阴影无明显变化。
- 期望表现：键盘 focus 必须可见，hover/active 应在亮色和暗色都有稳定反馈，图标按钮、普通按钮、链接按钮反馈风格应一致。
- 初步原因推断：多个全局规则主动清除 outline 或只保留弱边框；按钮样式分散在 `.butAction`、`.butActionNew`、`.btnTitle`、`.ui-btn`、`.reposition` 中。
- 涉及 CSS/JS 文件和大致选择器：`formora/global.inc.php` 3325-3330、8250-8252；`formora/btn.inc.php` 203-219、328-351、404-419；`formora/search-input.inc.css`；选择器 `.btnTitle`、`.butAction`、`.butActionNew`、`.ui-focus`、`.ui-btn:focus`、`input.search-tool-input:focus`。
- 修复建议：定义统一 focus token，如 2px outline 或 box-shadow，并覆盖输入框、select2、按钮、图标按钮、tabs；hover 用背景/边框/阴影其中一种，不要让 selected/disabled/reposition 状态吞掉反馈。
- 是否可能影响全局：是。属于可访问性和交互基础规则。

### P2-6：快速新增和用户菜单在手机下可用，但弹层固定宽度和贴边策略需要微调

- 页面 URL：`/index.php`
- 主题模式：亮色抽样；暗色应复验
- viewport：390x844
- 复现步骤：点击顶部快速新增按钮；关闭后点击用户菜单。
- 实际表现：两个菜单均可打开，没有 document 溢出；快速新增弹层 `x=97 width=287 right=384`，用户菜单 `x=99 width=285 right=384`。但弹层宽度固定，右侧几乎贴边，长中文菜单项靠省略/隐藏处理。
- 期望表现：手机弹层应基于 `min(视口 - 安全边距, 设计宽度)`，左右边距更稳定；长中文菜单项可换行或有更清晰层级。
- 初步原因推断：下拉菜单宽度在 `formora/dropdown.inc.php` 中有固定值和小屏覆盖：通用 dropdown、bookmark/quickadd/user menu 混用 220/285/300/360px。
- 涉及 CSS/JS 文件和大致选择器：`formora/dropdown.inc.php` 51-54、104-124、640-660；选择器 `#topmenu-quickadd-dropdown .dropdown-menu`、`.topnav .user-menu .dropdown-menu`、`.dropdown-menu:not(.ai_dropdown)`。
- 修复建议：使用 `max-width: calc(100vw - 24px)`、`inset-inline` 和逻辑属性控制手机弹层；避免固定 `right:0` 加固定宽度造成贴边感。
- 是否可能影响全局：中等。只影响顶部弹层，但它们是全局入口。

### P2-7：TakePOS 手机可用但密度过高，桌面暗色存在大面积空格/占位格

- 页面 URL：`/takepos/index.php?idmenu=65&mainmenu=takepos&leftmenu=`
- 主题模式：亮色 390x844；暗色 1024x768
- viewport：390x844、1024x768
- 复现步骤：进入 TakePOS 首页，观察顶部栏、键盘、操作按钮、分类 tab、商品格。
- 实际表现：390px 下 document 不溢出，`scrollWidth=390`，但顶部搜索/用户/购物车图标很紧，数字键盘、操作按钮、分类 tab 和商品列表高度密集。1024 暗色下 document 不溢出，`scrollWidth=1024`，但商品网格中有大量空占位格，产品图片占位图过大，文本和价格压在格子底部。截图：`71-light-390x844-takepos.png`、`72-dark-1024x768-takepos.png`。
- 期望表现：TakePOS 应保留触控优先布局，手机下控件要有足够点击面积但减少挤压；桌面暗色商品格应减少空白占位感，图片/价格/名称层级更清楚。
- 初步原因推断：TakePOS 是特殊页面，body class 为 `bodytakepos`，`formora/formora.js` 有专门的 theme 参数传递与 iframe/colorbox patch，但视觉布局主要依赖 TakePOS 自身结构和全局按钮/输入规则。
- 涉及 CSS/JS 文件和大致选择器：`formora/formora.js` 298-401；TakePOS 页面 `body.bodytakepos`；全局按钮、输入和图标规则。
- 修复建议：不要用普通列表/表单规则硬套 TakePOS。应单独建立 `.bodytakepos` 下的 breakpoints：手机优先优化顶部栏、键盘、操作按钮、分类 tab；桌面优化商品格 aspect ratio、图片占位和空格显示。
- 是否可能影响全局：低到中。应限制在 `.bodytakepos`。

### P3-1：发票详情页整体稳定，但手机 tabs/状态/返回区仍偏紧

- 页面 URL：`/compta/facture/card.php?id=160&save_lastsearch_values=1`
- 主题模式：亮色、暗色均可用
- viewport：390x844、430x932、768x1024
- 复现步骤：进入发票详情页，查看 tabs、票据信息卡、状态 badge、返回列表/上一条下一条。
- 实际表现：无 document 溢出，卡片信息区可读；但手机顶部 tabs 只能显示少量项，`... (4)` 提示较弱，右侧返回列表/箭头和状态 badge 在窄屏上视觉密度高。截图：`08-light-390x844-compta-facture-card-php-id-160-save-lastsearch-values-1.png`、`49-dark-390x844-compta-facture-card-php-id-160-save-lastsearch-values-1.png`。
- 期望表现：详情页 tabs 在手机下应有更明确的横向滚动或折叠菜单；状态和导航按钮保持可读间距。
- 初步原因推断：详情页比创建页稳定，因为主要信息已卡片化；剩余问题来自 tabs 和标题/状态区域的通用规则。
- 涉及 CSS/JS 文件和大致选择器：`formora/global.inc.php` 4135-4297、9347-9353；`formora/badges.inc.php`；选择器 `.tabs`、`.tabsElem`、`a.tab`、`.tabBar`、`.statusref`、`.badge-status`。
- 修复建议：放到第三阶段处理。先稳定表单和列表，再统一 tabs 的移动端模式。
- 是否可能影响全局：是，但优先级低。

### P3-2：表格视觉整体可用，但线条/背景/空白风格仍不完全统一

- 页面 URL：普通列表页和模块页，尤其 `/societe/list.php?leftmenu=thirdparties&mainmenu=companies`、`/admin/modules.php?mainmenu=home&leftmenu=setup`
- 主题模式：亮色、暗色
- viewport：390x844 到 1440x900
- 复现步骤：查看列表表头、筛选行、数据行、模块列表行。
- 实际表现：没有发现明显重复外框导致不可用的问题；但不同页面的卡片背景、表格行底线、筛选块背景和按钮边框风格不完全一致。模块页灰底筛选块、列表页白底筛选行、Kanban 卡片边界呈现不统一。
- 期望表现：表格、筛选、卡片三类区域应有明确层级：页面背景、工具栏背景、表格/卡片背景、行分隔线分别使用统一 token。
- 初步原因推断：`global.inc.php`、`info-box.inc.php`、`btn.inc.php`、`badges.inc.php` 分别定义背景、圆角、边框，变量共享不足，且有较多 `!important`。
- 涉及 CSS/JS 文件和大致选择器：`formora/global.inc.php`、`formora/info-box.inc.php`、`formora/btn.inc.php`、`formora/badges.inc.php`；选择器 `table.liste`、`.liste_titre`、`.box-flex-item`、`.info-box-module`、`.btnTitle`。
- 修复建议：放到视觉打磨阶段，先不要以页面级方式消除某个边线；应在统一 token 后批量调整。
- 是否可能影响全局：是。

## 疑似全局根因

1. 断点来源不统一：顶部菜单滚动状态 JS 使用 `max-width: 1180px`，左栏隐藏/显示使用动态 `THEME_ELDY_WITDHOFFSET_FOR_REDUC3`，另有 `max-width: 1024px`、`max-width: 768px`、`max-width: 767px`、`max-width: 570px`、`max-width: 480px`。这些断点叠加后在 900-1024 区间形成“顶部仍移动、左栏已桌面”的混合状态。
2. 表单移动端改造不完整：`td` 被改成 block 后，`tr`、`tbody/table` 的高度和布局语义没有同步切换，导致移动端创建页出现重叠。
3. Kanban/card 与列表表格容器混用：Kanban 仍在 `.div-table-responsive`、列表表头和 `.box-flex-item` 固定宽度之间拉扯，document 不溢出但内部横向裁切。
4. 通用选择器命中过宽：`.btnTitle`、`.butAction`、`.ui-btn`、`.select2-container`、`.tableforfield`、`.pictofixedwidth` 在列表、表单、模块、TakePOS 等多类页面同时命中，局部修复容易影响全局。
5. `!important` 和固定宽度较多：按钮、select2、dropdown、表单、模块卡片中均可见大量 `!important`、`min-width`、`max-width`、固定 px 宽度，后续调断点时容易互相覆盖。
6. hover/focus token 缺失：不同按钮体系各自定义 hover，focus 多处被清除，亮暗模式下难以保证一致反馈。

## 不建议立刻修的问题

1. 不建议先微调单个列表页表格边框。当前列表页没有 P0/P1 级表格外框问题，先改边线会掩盖真正的列表 toolbar 和移动表单问题。
2. 不建议先给某个创建页写页面级覆盖。合作方、产品、项目创建页的重叠根因相同，应先修 `tableforfield` / 表单行模型。
3. 不建议先局部缩小 Kanban 卡片字体或价格文字。产品 Kanban 的核心问题是内部横向滚动和卡片/表头混用，单纯缩字会降低可读性。
4. 不建议马上重做 TakePOS。TakePOS 没有 document 溢出，且是特殊页面；应在基础断点、列表、表单稳定后，单独在 `.bodytakepos` 下处理。
5. 不建议先清理所有 `!important`。数量较多且来源跨文件，应该在建立新 token 和断点策略后逐步替换。

## 分阶段修复计划

### 第一阶段：基础断点 / 布局稳定

- 统一断点命名和来源，明确手机、平板、窄桌面、桌面四档。
- 协调顶部菜单滚动断点与左栏显示断点，重点处理 900-1024 区间。
- 修复移动端表单行模型：让 `tr/td/tableforfield` 在小屏下真正单列化，避免父行高度不随子块展开。
- 建立全局 focus token，恢复可见键盘焦点。

### 第二阶段：列表页统一

- 统一列表页标题、分页、视图切换、创建按钮、批量操作、筛选区布局。
- 为 `.liste_titre_filter`、`.paginationafterarrows`、`.btnTitle` 制定移动端 grid/flex 规则。
- 明确表格内部横向滚动的视觉提示，不把 document 溢出作为唯一判断。

### 第三阶段：详情页 / 表单页统一

- 在创建页、编辑页、详情页中统一 label/value/help/icon 的间距、宽度和换行。
- 统一 select2、日期框、富文本编辑器在手机/平板下的宽度和垂直间距。
- 处理详情页 tabs、状态 badge、返回列表/上一条下一条按钮的移动端布局。

### 第四阶段：特殊页面，如 TakePOS、模块页、Kanban

- 为 Kanban/card 视图脱离普通 table header 的移动端布局，优先单列卡片。
- 模块页筛选块和模块列表/卡片视图使用统一 toolbar token，但保留模块页局部结构。
- TakePOS 在 `.bodytakepos` 下单独处理，不使用普通列表/表单规则硬套。

### 第五阶段：hover/focus/微交互和视觉打磨

- 统一按钮、链接、图标按钮的 hover/focus/active 反馈。
- 统一圆角、边框、阴影、背景层级，避免模块页/列表页/卡片页风格割裂。
- 复查亮色/暗色模式下 disabled、selected、hover、focus、active 的对比度和可见性。

## 自动化覆盖清单

已覆盖 URL：

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

已覆盖 viewport：

- 390x844
- 430x932
- 768x1024
- 900x900
- 1024x768
- 1280x900
- 1440x900
- 1728x1117

已覆盖主题：

- 亮色：`THEME_DARKMODEENABLED=0`
- 暗色：`THEME_DARKMODEENABLED=2`

自动化结果摘要：

- 页面状态总数：288
- 导航错误：0
- document 级横向溢出：0
- 390/430/768/900/1024 下顶部菜单内部横向滚动：普通页面 17/18
- 手机/平板左侧菜单固定占位：未发现，`#id-left width=0` 且 `.side-nav display=none`
- 1024 起固定左栏：`#id-left width=260`，`.side-nav width=261`

