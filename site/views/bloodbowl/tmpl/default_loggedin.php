<?php
// No direct access to this file
defined('_JEXEC') or die;

$stylecenter = 'text-align:center;';
$styleleft = 'text-align:left;';
?>

<h2><?php echo JText::_('COM_BLOODBOWL_MY_TEAMS'); ?></h2>
<div style="float: left;"><table>
	<tr>
		<th><?php echo JText::_('COM_BLOODBOWL_TEAM_NAME'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_TEAM_READY'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_IN_TOURNEYS'); ?></th>
		<th></th>
	</tr>
	<?php
		foreach ($this->myteams as $team)
		{
			//alternate bgcolor
			if ($bgcolor=='WhiteSmoke') {
					$bgcolor='#FEE6BC';
			} else {
				$bgcolor   = 'WhiteSmoke';
			}
			echo "<tr style=\"background-color: $bgcolor;\">";
			 echo "<td style='$styleleft'><a href=\"".JRoute::_('index.php?view=teamdetail&show='. $team->id )."\">".$team->name."</a></td>\n";
			 echo "<td style='$stylecenter'>". ($team->locked==1 ? JText::_('COM_BLOODBOWL_MUST_UPDATE') : JText::_('COM_BLOODBOWL_READY')) ."</td>\n";
			 echo "<td style='$styleleft'>";
			 foreach ($team->tours AS $tour)
			 {
				echo JHtml::_('link', JRoute::_("index.php?view=tourneydetail&show={$tour->id}"), $tour->name, null) .' <br>';
			 }
			 echo "</td>\n";
			 echo "<td style='$stylecenter'>".(strlen($team->comment)>30 ? '<img src="media/com_bloodbowl/images/icons/discuss.gif" title="'. JText::_('COM_BLOODBOWL_TEAM_COMMENT') .'"/>':'&nbsp;')."</td>\n";			
			echo "</tr>\n";
		}
		if (empty($this->myteams))
		{
			echo "<tr style=\"background-color: $bgcolor;\">";
			 echo "<td style='$styleleft'>Der er ingen hold at vise.</td>\n";
			 echo "<td style='$stylecenter'> </td>\n";
			 echo "<td style='$styleleft'> </td>\n";
			 echo "<td style='$stylecenter'> </td>\n";			
			echo "</tr>\n";
		}
	?>
</table></div>

<?php 
	//include('newteam.php'); 
	echo $this->loadTemplate('newteam');
?>

<br>
<?php if (!empty($this->mymatches)) { ?>
<h2><?php echo JText::_('COM_BLOODBOWL_MY_MATCHES'); ?></h2>
<table width='100%'>
	<tr>
		<th><?php echo JText::_('COM_BLOODBOWL_TOUR_NAME'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_HOME'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_AWAY'); ?></th>
		<th style='text-align:center;' colspan="3"><?php echo JText::_('COM_BLOODBOWL_SCORE'); ?></th>
		<th></th>
		<th style='text-align:center;'><?php echo JText::_('COM_BLOODBOWL_DATE_PLAYED'); ?></th>
		<th></th>
	</tr>
	<?php
		foreach ($this->mymatches as $match)
		{
			//alternate bgcolor
			if ($bgcolor=='WhiteSmoke') {
					$bgcolor='#FEE6BC';
			} else {
				$bgcolor   = 'WhiteSmoke';
			}
			$stylecenter = 'text-align:center;';
			$styleleft = 'text-align:left;';
			echo "<tr style=\"background-color: $bgcolor;\">";
			 echo "<td style='$styleleft'><a href=\"".JRoute::_('index.php?view=tourneydetail&show='. $match->f_tour_id )."\">".$match->tour_name."</a></td>\n";
			 if ( $match->challenge==1 )
			 {
				echo "<td style='$styleleft'>". JFactory::getUser($match->team1_id)->name ."</a></td>\n";
				echo "<td style='$styleleft'>". JFactory::getUser($match->team2_id)->name ."</a></td>\n";
			 }
			 else 
			 {
				echo "<td style='$styleleft'><a href=\"". JRoute::_('index.php?view=teamdetail&show='. $match->team1_id ) ."\">".$match->team1_name."</a></td>\n";
				echo "<td style='$styleleft'><a href=\"". JRoute::_('index.php?view=teamdetail&show='. $match->team2_id ) ."\">".$match->team2_name."</a></td>\n";
			 }
			 echo "<td style='$stylecenter'>".$match->team1_score."</td>\n";
			 echo "<td style='$stylecenter'>-</td>\n";
			 echo "<td style='$stylecenter'>".$match->team2_score."</td>\n";
			 echo "<td style='$stylecenter'>".(strlen($match->comment)>30 ? '<img src="media/com_bloodbowl/images/icons/discuss.gif" title="'. JText::_('COM_BLOODBOWL_MATCH_SUMMARY') .'"/>':'&nbsp;')."</td>\n";			
			 echo "<td style='$stylecenter'>".(is_null($match->date_played) ? '' : date("d-m-Y",strtotime($match->date_played))) ."</td>\n";
			 echo "<td style='$stylecenter'><a href=\"". JRoute::_('index.php?view=matchdetail&show='. $match->match_id) ."\">". JText::_('COM_BLOODBOWL_MATCH_VIEW') ."</td>";
			echo "</tr>\n";
		}
	?>
</table>
<?php } ?>
<div style="clear: both;"><br></div>