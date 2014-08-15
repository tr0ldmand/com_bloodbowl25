<?php
// No direct access to this file
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');
?>
<form class="form-validate" action="<?php echo JRoute::_('index.php?view=teamdetail'); ?>" method="post" id="newteam" name="newteam">
<fieldset class="newteam">
	<?php 
	echo '<legend>'. JText::_('COM_BLOODBOWL_CREATE_NEW_TEAM') .':</legend>';
	echo $this->form->getInput('submitter_id',null,JFactory::getUser()->id);
	echo $this->form->getLabel('name') .': '. $this->form->getInput('name') .' ';
	echo $this->form->getLabel('race') .': '. $this->form->getInput('race') .' ';
	echo $this->form->getLabel('startval') .': '. $this->form->getInput('startval') .' ';
	?>
	<input type="hidden" name="option" value="com_bloodbowl" />
	<input type="hidden" name="view" value="teamdetail" />
	<input type="hidden" name="task" value="teamdetail.newteam" />
	<button type="submit" class="validate button"><?php echo JText::_('Submit'); ?></button>
	<?php echo JHtml::_('form.token'); ?>
</fieldset>
</form>
<div style="clear: both;"></div>
