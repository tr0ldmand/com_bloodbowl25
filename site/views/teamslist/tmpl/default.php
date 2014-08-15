<?php
// No direct access to this file
defined('_JEXEC') or die;
 
JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');

echo BloodBowlHelper::addMenubar();
?>
<a name="bb" class="focus"></a>
<h1><?php echo JText::_( 'COM_BLOODBOWL_TEAMSLIST' ); ?></h1>
<?php include('formlimit.php'); ?>
<p>
<table id="teamslist" style="border:none; width:70%; padding: 1px;">
	<tr>
		<th width="20%"><a href="<?php echo JRoute::_("index.php?view=teamslist&show={$this->showteam}&order=race"); ?>"><?php echo JText::_( 'COM_BLOODBOWL_RACE'); ?></a></th>
		<th width="30%"><a href="<?php echo JRoute::_("index.php?view=teamslist&show={$this->showteam}&order=name&races={$this->showraces}"); ?>"><?php echo JText::_( 'COM_BLOODBOWL_TEAM_NAME'); ?></a></th>
		<th width="30%"><?php echo JText::_( 'COM_BLOODBOWL_COACH'); ?></th>
		<th width="20%"><a href="<?php echo JRoute::_("index.php?view=teamslist&show={$this->showteam}&order=teamvalue&races={$this->showraces}"); ?>"><?php echo JText::_( 'COM_BLOODBOWL_TEAM_INFORMATION'); ?></a></th>
	</tr>
	<?php
		foreach ($this->teams as $team)
		{
			if ($bgcolor=='WhiteSmoke') $bgcolor='#FEE6BC';
			else $bgcolor='WhiteSmoke';
			echo "<tr style=\"background-color: $bgcolor;\">\n";
				echo "<td><a href=\"". JRoute::_( 'index.php?view=teamdetail&show='. $team->id) ."\"><img src=\"media/com_bloodbowl/images/icons/{$team->icon}\" alt=\"{$team->race}\"></a></td>\n";
				echo "<td><a href=\"". JRoute::_( 'index.php?view=teamdetail&show='. $team->id) ."\">{$team->name}</a></td>";
				echo "<td><a href=\"". JRoute::_( 'index.php?view=teamslist&show='. $team->coach .'&order='. $this->showorder ) ."\">". JFactory::getUser($team->coach)->name ."</a></td>\n";
				echo "<td>TV: ". ($team->teamvalue)/10000 ."<br></td>\n";
			echo "</tr>\n";
		}
	?>
</table>
</p>
<?php 
	//include('formlimit.php'); 
	echo $this->loadTemplate('formlimit');
?>
<p><?php
echo JText::sprintf('COM_BLOODBOWL_TEAMS_UNDER_REQUIREMENTS', $this->nofteams) .".";
?></p>
<?php if (!JFactory::getUser()->guest) { ?>
	<a name="newteam"></a>
	<?php 
	//include('newteam.php'); 
	echo $this->loadTemplate('newteam');
	?>
<?php } ?>