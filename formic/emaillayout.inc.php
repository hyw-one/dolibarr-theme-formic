<?php
if (!defined('ISLOADEDBYSTEELSHEET')) {
	die('Must be call by steelsheet');
}
?>
/* IDE Hack <style type="text/css"> */

.template-container {
	display: flex;
	justify-content: space-between;
	padding: 10px;
	background: var(--formic-surface-alt, #f5f5f5);
	border: 1px solid var(--formic-line, #d3d3d3);
	border-radius: var(--formic-radius-sm, 5px);
	color: var(--formic-text, inherit);
	margin-bottom: 15px;
  }

  .template-option {
	text-align: center;
	padding: 10px;
	margin: 4px 5px;
	background: var(--formic-surface, var(--colorbackgrey));
	border: 1px solid var(--formic-line-soft, transparent);
	border-radius: var(--formic-radius-sm, 5px);
	color: var(--formic-text, inherit);
	cursor: pointer;
	height: 60px;
	width: 60px;
	display: inline-block;
	align-items: center;
	justify-content: center;
  }

  .template-option:hover {
	font-weight: bold;
	background: var(--butactionbg);
	color: var(--textbutaction);
	border-color: transparent;
	border-radius: var(--formic-radius, 8px);
	border-collapse: collapse;
  }

  .template-option[data-template="ai"] {
	background: var(--formic-toast-success-bg, #c5f7c5);
	border-color: var(--formic-toast-success-border, var(--formic-line-soft, transparent));
	color: var(--formic-toast-success-text, var(--formic-text, inherit));
  }

  .template-option[data-template="ai"]:hover {
	font-weight: bold;
	background: var(--butactionbg);
	color: var(--textbutaction);
	border-radius: var(--formic-radius, 8px);
	border-collapse: collapse;
	border: none;
  }

  .template-option.selected {
	font-weight: bold;
	background: var(--butactionbg);
	color: var(--textbutaction);
	border-radius: var(--formic-radius, 8px);
	border-collapse: collapse;
	border: none;
}

  #template-selector {
	/* width: 100%;
	max-width: 80%; */
	height: auto;
	padding: 10px;
	background: var(--formic-surface, transparent);
	border: 1px solid var(--formic-line, #d3d3d3);
	border-radius: var(--formic-radius-sm, 5px);
	color: var(--formic-text, inherit);
	margin-bottom: 10px;
	margin-top: 10px;
	width: fit-content;
  }

  .template-option[data-template="ai"] i {
	font-size: 42px;
	display: block;
	width: 80%;
	max-height: 80px;
	margin: 0 5px;
	padding-top: 5px;
	border-radius: var(--formic-radius-sm, 5px);

}

.template-option[data-template="ai"] span {
  padding-top: 30px;
  font-size: 14px;

}

.template-option-text {
  padding-top: 3px;
  font-size: 14px;
}

#ai_input {
  display: none;
}

.template-option img {
  display: block;
  width: 80%;
  max-height: 80px;
  margin: 0 5px;
  border-radius: var(--formic-radius-sm, 7px);
}
