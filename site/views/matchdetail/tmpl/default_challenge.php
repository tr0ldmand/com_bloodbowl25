<?php
// No direct access to this file
defined("_JEXEC") or die('Restricted access');
?>

<h1><?php echo JText::_('COM_BLOODBOWL_CHALLENGE'); ?></h1><br>
<?php echo JText::_('COM_BLOODBOWL_UPD_CHALLENGE_NOTICE'); ?>.<br>
<form class="form-validate" action="<?php echo JRoute::_('index.php?view=matchdetail&show='. $this->match->match_id .'&toggleedit=0'); ?>" method="post" id="challenge" name="challenge">
<select name="team1">
	<option value="0"><?php echo JFactory::getUser($this->match->team1_coach)->name; ?>'s hold</option>
	<?php
	foreach ($this->teams[$this->match->team1_coach] AS $team)
	{
		echo "<option value=\"". $team->team_id ."\">". $team->team_name ." (". $team->race .") TV: ". $team->teamvalue/10000 ."</option>";
	}
	?>
</select>
VS.
<select name="team2">
	<option value="0"><?php echo JFactory::getUser($this->match->team2_coach)->name; ?>'s hold</option>
	<?php
	foreach ($this->teams[$this->match->team2_coach] AS $team)
	{
		echo "<option value=\"". $team->team_id ."\">". $team->team_name ." (". $team->race .") TV: ". $team->teamvalue/10000 ."</option>";
	}
	?>
</select>
<br>
<input type="hidden" name="option" value="com_bloodbowl" />
<input type="hidden" name="view" value="matchdetail" />
<input type="hidden" name="show" value="<?php echo $this->match->match_id; ?>" />
<input type="hidden" name="task" value="matchdetail.updchallenge" />
<button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
<?php echo JHtml::_('form.token'); ?>
</form><br>
<?php echo "<a href=\"". JRoute::_("index.php?view=tourneydetail&show={$this->match->f_tour_id}") ."\">". JText::_('COM_BLOODBOWL_BACK_TO') ." {$this->match->tour_name}</a>."; ?>
