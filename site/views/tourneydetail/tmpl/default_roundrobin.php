<?php
// No direct access to this file
defined('_JEXEC') or die;
 
?>

<p><!--Sorter efter:
	<ul>
		<li><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=win&standB=matches&standC=td_for' ); ?>"><?php echo JText::_('COM_BLOODBOWL_NUM_WON'); ?>, <?php echo JText::_('COM_BLOODBOWL_MATCHES'); ?>, <?php echo JText::_('COM_BLOODBOWL_TD_FOR'); ?></a></li>
		<li><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=win&standB=matches&standC=td_diff' ); ?>"><?php echo JText::_('COM_BLOODBOWL_NUM_WON'); ?>, <?php echo JText::_('COM_BLOODBOWL_MATCHES'); ?>, <?php echo JText::_('COM_BLOODBOWL_TD_DIFF'); ?></a></li>
		<li><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=matches&standB=win&standC=td_for' ); ?>"><?php echo JText::_('COM_BLOODBOWL_MATCHES'); ?>, <?php echo JText::_('COM_BLOODBOWL_NUM_WON'); ?>, <?php echo JText::_('COM_BLOODBOWL_TD_FOR'); ?></a></li>
		<li><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=matches&standB=win&standC=td_diff' ); ?>"><?php echo JText::_('COM_BLOODBOWL_MATCHES'); ?>, <?php echo JText::_('COM_BLOODBOWL_NUM_WON'); ?>, <?php echo JText::_('COM_BLOODBOWL_TD_DIFF'); ?></a></li>
	</ul>-->
	<table>
	<thead><tr>
		<th><?php echo JText::_('COM_BLOODBOWL_PLACE'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_TEAM_NAME'); ?></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail&show='. $this->show .'&match='. $this->matchstart .'&order='. $this->order .'&races='. $this->race .'&coach='. $this->coach .'&standA=matches' ); ?>"><?php echo JText::_('COM_BLOODBOWL_MATCHES'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=point' ); ?>"><?php echo JText::_('COM_BLOODBOWL_POINT'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=win' ); ?>"><?php echo JText::_('COM_BLOODBOWL_WON'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=draw' ); ?>"><?php echo JText::_('COM_BLOODBOWL_DRAW'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=loss' ); ?>"><?php echo JText::_('COM_BLOODBOWL_LOST'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=td_for' ); ?>"><?php echo JText::_('COM_BLOODBOWL_TD_FOR'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=td_against' ); ?>"><?php echo JText::_('COM_BLOODBOWL_TD_AGAINST'); ?></a></th>
		<th><a href="<?php echo JRoute::_('index.php?view=tourneydetail2&show='. $this->show .'&match='. $this->matchstart .'&standA=td_diff' ); ?>"><?php echo JText::_('COM_BLOODBOWL_TD_DIFF'); ?></a></th>
		<th><?php echo JText::_('COM_BLOODBOWL_RACE'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_COACH'); ?></th>
	</tr></thead>
<?php
		$i=1;
		foreach ($this->standings as $team)
		{
			if ($bgcolor=="WhiteSmoke") $bgcolor='#FEE6BC';
			else $bgcolor="WhiteSmoke";
			echo "<tr style=\"background-color: $bgcolor;\">";
				echo "<td>". $i++ .".</td>";
				echo "<td>". JHtml::_('link', JRoute::_('index.php?view=teamdetail&show='. $team->team_id ), $this->teams[$team->team_id]->team_name, null) ." (". $this->teams[$team->team_id]->teamvalue/10000 .")</td>";
				echo "<td>". $team->matches ."</td>";
				echo "<td>". $team->point ."</td>";
				echo "<td>". $team->win ."</td>";
				echo "<td>". $team->draw ."</td>";
				echo "<td>". $team->loss ."</td>";
				echo "<td>". $team->td_for ."</td>";
				echo "<td>-". $team->td_against ."</td>";
				echo "<td>". $team->td_diff ."</td>";
				echo "<td>". $this->teams[$team->team_id]->race ."</td>";
				echo "<td>". $this->teams[$team->team_id]->coach_name ."</td>";
			echo "</tr>";
		}
?>
</table></p>

