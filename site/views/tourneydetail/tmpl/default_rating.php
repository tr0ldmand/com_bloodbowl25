<?php
// No direct access to this file
defined('_JEXEC') or die;
 
?>

<p><table>
	<tr>
		<th>#</th>
		<th><?php echo JText::_('COM_BLOODBOWL_COACH'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_RATING'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_RATING_CHANGE'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_MATCHES'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_WINPCT'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_WON'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_DRAW'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_LOST'); ?></th>
		<th> </th>
	</tr>
<?php
	$i=1;
	foreach ($this->coaches as $coach)
	{
		if ($bgcolor=="WhiteSmoke") $bgcolor='#FEE6BC';
		else $bgcolor="WhiteSmoke";
		echo "<tr style=\"background-color: $bgcolor;\">";
			echo "<td>". $i++ .".</td>";
			echo "<td><a href=\"". JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=race&races=all&coach='. $coach->id ) ."\">". JFactory::getUser($coach->id)->name ."</a></td>";
			echo "<td style=\"text-align: center;\">". round($coach->rating) ."</td>";
			echo "<td style=\"text-align: center;\">{$coach->rchange}</td>";
			echo "<td style=\"text-align: center;\">{$coach->matches}</td>";
			echo "<td style=\"text-align: right;\">". number_format($coach->winpct,2) ."%</td>";
			echo "<td style=\"text-align: center;\">{$coach->won}</td>";
			echo "<td style=\"text-align: center;\">{$coach->draw}</td>";
			echo "<td style=\"text-align: center;\">{$coach->lost}</td>";
			echo "<td>";
			if ($coach->id!=JFactory::getUser()->id && array_key_exists(JFactory::getUser()->id, $this->coaches))
			{
				echo "<form method=\"POST\" class=\"form-validate\" id=\"matchdetail\" name=\"matchdetail\">";
				echo $this->newchallengeform->getInput('tour_id',null,$this->tourney->tour_id);
				echo $this->newchallengeform->getInput('coach_id',null,JFactory::getUser()->id);
				echo $this->newchallengeform->getInput('teamA',null,JFactory::getUser()->id);
				echo $this->newchallengeform->getInput('teamB',null,$coach->id);
				echo "<input type=\"hidden\" name=\"option\" value=\"com_bloodbowl\" />";
				echo "<input type=\"hidden\" name=\"task\" value=\"tourneydetail.newchallenge\" />";
				echo "<button type=\"submit\" class=\"button\">". JText::_('COM_BLOODBOWL_NEW_MATCH') ."</button>";
				echo JHtml::_('form.token'); 
				echo "</form>";
			}
			echo "</td>";
		echo "</tr>";
	}
	unset($coach);
?>
</table></p>

<h2><?php echo JText::_('COM_BLOODBOWL_TEAMSLIST'); ?></h2>
<p><form method="POST"><input type="hidden" name="option" value="com_bloodbowl" /><input type="hidden" name="view" value="tourneydetail"><input type="hidden" name="show" value="<?php echo $this->tourney->tour_id; ?>"><input type="hidden" name="order" value="tname">
<?php echo JText::_('COM_BLOODBOWL_LIMIT_SHOWING_TO'); ?>
<select name="coach"><option value="0"><?php echo JText::_( 'COM_BLOODBOWL_ALL_COACHES'); ?></option><?php
	foreach ($this->coaches as $coach)
	{
		echo "<option value=\"{$coach->id}\" ". ($this->coach == $coach->id ? 'selected' : '') .">". JFactory::getUser($coach->id)->name ."</option>\n";
	}
?></select> 
<select name="races"><option value="all"><?php echo JText::_( 'COM_BLOODBOWL_ALL_RACES'); ?></option><?php
	foreach ($this->races as $race)
	{
		echo "<option value=\"{$race->name}\" ". ($this->race == $race->name ? 'selected' : '') .">{$race->name}</option>\n";
	}
?></select> 
<input type="submit"></form>
<?php if ($this->order!="skjul") { ?>
<table>
	<tr>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=cname&races='. $this->race .'&coach=0' ); ?>"><?php echo JText::_('COM_BLOODBOWL_COACH'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=tname&races='. $this->race .'&coach='. $this->coach ); ?>"><?php echo JText::_('COM_BLOODBOWL_TEAM_NAME'); ?></a> (<a href="<?php echo JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=tv&races='. $this->race .'&coach='. $this->coach ); ?>">TV</a>)</th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=race&races=all&coach='. $this->coach ); ?>"><?php echo JText::_('COM_BLOODBOWL_RACE'); ?></a></th>
		<th> </th>
	</tr>
<?php
		foreach ($this->teams as $team)
		{
			if ($bgcolor=="WhiteSmoke") $bgcolor='#FEE6BC';
			else $bgcolor="WhiteSmoke";
			echo "<tr style=\"background-color: $bgcolor;\">";
				echo "<td><a href=\"". JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=race&races=all&coach='. $team->coach_id ) ."\">{$team->coach_name}</a></td>";
				echo "<td><a href=\"". JRoute::_('index.php?view=teamdetail&show='. $team->team_id .'' ) ."\">{$team->team_name} (". $team->teamvalue/10000 .")</a></td>";
				echo "<td><a href=\"". JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=tname&races='. $team->race .'&coach=0' ) ."\">{$team->race}</a></td>";
				echo "<td> </td>";
			echo "</tr>";
		}
?>
</table>
<a href="<?php echo JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->tour_id .'&order=skjul' ); ?>"><?php echo JText::_('COM_BLOODBOWL_HIDE_TEAMSLIST'); ?></a>
<?php } ?>
</p>