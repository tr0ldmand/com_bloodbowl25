<?php
// No direct access to this file
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');
?>
<form class="form-validate" action="<?php echo JRoute::_('index.php?view=teamdetail'); ?>" method="post" id="newteam" name="newteam">
<fieldset class="newteam" style="float: right;">
	<?php 
	echo '<legend>'. JText::_('COM_BLOODBOWL_CREATE_NEW_TEAM') .':</legend>';
	echo '<table>';
	echo "<tr><td>{$this->form->getLabel('name')}: </td><td>{$this->form->getInput('name')}</td></tr>";
	echo "<tr><td>{$this->form->getLabel('race')}: </td><td>{$this->form->getInput('race')}</td></tr>";
	echo "<tr><td>{$this->form->getLabel('startval')}: </td><td>{$this->form->getInput('startval')}</td></tr>";
	echo '</table>';
	echo $this->form->getInput('submitter_id',null,JFactory::getUser()->id);
	?>
	<input type="hidden" name="option" value="com_bloodbowl" />
	<input type="hidden" name="view" value="teamdetail" />
	<input type="hidden" name="task" value="teamdetail.newteam" />
	<button type="submit" class="validate button"><?php echo JText::_('Submit'); ?></button>
	<?php echo JHtml::_('form.token'); ?>
</fieldset>
</form>
<div style="clear: both;"></div>
