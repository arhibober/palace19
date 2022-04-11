<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<div id="fual_edit_alias_form" style="top:0px; left:0px; width:350px;">
	<div id="feaf_div_top" style="background-color:#F0F0F0; border-bottom:1px solid #868686; line-height:20px; vertical-align:middle;">
		<span><?php echo JText::_('COM_UAM_EDIT_ALIAS'); ?></span>
		<button type="button" id="feaf_bt_cancel" onclick="javascript:fualCloseAliasForm();"><?php echo JText::_('COM_UAM_CANCEL'); ?></button>
		<button type="button" id="feaf_bt_save" onclick="javascript:fualSaveAlias();"><?php echo JText::_('COM_UAM_SAVE'); ?></button>
		<input type="hidden" id="feaf_txt_saving" value="<?php echo JText::_('COM_UAM_SAVING'); ?>" />
		<input type="hidden" id="feaf_txt_save" value="<?php echo JText::_('COM_UAM_SAVE'); ?>" />
		<input type="hidden" id="feaf_txt_error" value="<?php echo JText::_('COM_UAM_INVALID_ALIAS', true); ?>" />
		<input type="hidden" id="feaf_txt_error_save" value="<?php echo JText::_('COM_UAM_ERROR_SAVING_ALIAS', true); ?>" />
		<input type="hidden" id="feaf_txt_ok_save" value="<?php echo JText::_('COM_UAM_ALIAS_SAVED', true); ?>" />
		<input type="hidden" id="feaf_txt_edit_alias" value="<?php echo JText::_('COM_UAM_EDIT_ALIAS'); ?>" />
		<br clear="all" />
	</div>
	<div>
		ID:&nbsp;&nbsp;&nbsp;<span id="feaf_id_article"></span>
		<br />
		<?php echo JText::_('COM_UAM_TITLE'); ?>:&nbsp;&nbsp;&nbsp;<span id="feaf_title" style="margin-bottom:15px; font-weight:bold;"></span>
		<br />
		<?php echo JText::_('COM_UAM_ALIAS'); ?>:<br />
		<input type="text" id="feaf_alias" style="width:99%;" maxlength="255" /><br />
	</div>
</div>