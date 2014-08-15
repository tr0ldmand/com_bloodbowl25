<?php
// No direct access to this file
defined('_JEXEC') or die;

?>
<form class="form-validate" action="<?php echo JRoute::_('index.php?view=tourneydetail&show='. $this->showteam); ?>" method="post" id="tourneyadmin" name="tourneyadmin">
<div style="clear: both;">
	<h2><?php echo JText::_('COM_BLOODBOWL_NEWS'); ?></h2>
	<p><?php echo $this->form->getInput('news',null,$this->tourney->news); ?></p>
</div>

<div style="clear: both;">
	<h2><?php echo JText::_('COM_BLOODBOWL_RULES'); ?></h2>
	<p><?php echo $this->form->getInput('rules',null,$this->tourney->rules); ?></p>
</div>

<div style="clear: both;">
	<h2><?php echo JText::_('COM_BLOODBOWL_TOURNEY_ADMIN_ACTIONS'); ?></h2>
	<p><fieldset class="touradmin"><?php 
	echo '<legend>'. JText::_('COM_BLOODBOWL_ADD_TEAMS') .':</legend>';
	echo "<select id=\"jform_teams\" name=\"jform[teams][]\" size=\"10\" multiple". (empty($this->newteams) ? ' disabled':'') .">\n";
	if (empty($this->newteams))
	{
		echo "<option value=\"0\">Ingen hold tilgængelige</option>";
	}
	foreach ($this->newteams AS $team)
	{
		echo "<option value=\"{$team->id}\">". JFactory::getUser($team->coach)->name ." - {$team->name} ({$team->race})</option>";
	}
	echo "</select>";
	unset($team);
	?></fieldset>
	
	
	<fieldset class="touradmin"><?php 
	echo '<legend>'. JText::_('COM_BLOODBOWL_ADD_TOURNEY') .':</legend>';
	echo "<select id=\"jform_tours\" name=\"jform[tours][]\" size=\"5\" multiple". (empty($this->newtours) ? ' disabled':'') .">\n";
	if (empty($this->newtours))
	{
		echo "<option value=\"0\">Ingen turneringer tilgængelige</option>";
	}
	foreach ($this->newtours AS $tour)
	{
		echo "<option value=\"{$tour->tour_id}\">{$tour->name}</option>";
	}
	echo "</select>";
	unset($tour);
	//echo $this->form->getInput('tours');
	?></fieldset>
	
	
	<fieldset class="touradmin"><?php 
	echo '<legend>'. JText::_('COM_BLOODBOWL_DEFINE_MATCH') .':</legend>';
	echo "<select style=\"max-width: 8em;\" id=\"jform_match1\" name=\"jform[match1]\" ". (empty($this->teams) ? ' disabled':'') .">\n";
	if (empty($this->teams))
	{
		echo "<option value=\"0\">Ingen hold tilgængelige</option>";
	}
	else echo "<option value=\"0\">". JText::_('COM_BLOODBOWL_HOME_TEAM') ."</option>";
	foreach ($this->teams AS $team)
	{
		echo "<option value=\"{$team->team_id}\">{$team->coach_name} - {$team->team_name} (TV ". $team->teamvalue/10000 ." {$team->race})</option>";
	}
	echo "</select> ";
	echo JText::_('COM_BLOODBOWL_VERSUS');
	echo " <select style=\"max-width: 8em;\" name=\"jform[match2]\" id=\"jform_match2\" ". (empty($this->teams) ? ' disabled':'') .">\n";
	if (empty($this->teams))
	{
		echo "<option value=\"0\">Ingen hold tilgængelige</option>";
	}
	else echo "<option value=\"0\">". JText::_('COM_BLOODBOWL_AWAY_TEAM') ."</option>";
	foreach ($this->teams AS $team)
	{
		echo "<option value=\"{$team->team_id}\">{$team->coach_name} - {$team->team_name} (TV ". $team->teamvalue/10000 ." {$team->race})</option>";
	}
	echo "</select>";
	unset($team);
	?></fieldset>

	
	<?php if ($this->isadmin) { ?>
		<?php echo $this->form->getInput('tour_id',null,$this->show); ?>
		<?php echo $this->form->getInput('submitter_id',null,JFactory::getUser()->id); ?>
		<input type="hidden" name="option" value="com_bloodbowl" />
		<input type="hidden" name="view" value="tourneydetail" />
		<input type="hidden" name="show" value="<?php echo $this->show; ?>" />
		<input type="hidden" name="task" value="tourneydetail.admin" />
		<button type="submit" class="validate button"><?php echo JText::_('Submit'); ?></button>
		<?php echo JHtml::_('form.token'); ?>
	<?php } ?>
	</p>
</div>
</form>
<div style="clear: both;"></div>
