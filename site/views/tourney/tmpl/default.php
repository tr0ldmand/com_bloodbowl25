<?php
// No direct access to this file
defined('_JEXEC') or die;
JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');

echo BloodBowlHelper::addMenubar();
?>
<h1><?php echo JText::_('COM_BLOODBOWL_TOURNEYS'); ?></h1>
<p>
<table id="tourneys" style="border:none; width:70%; padding: 1px;">
	<tr>
		<th class="tpage_top" width="30%"><?php echo JText::_('COM_BLOODBOWL_TOUR_NAME'); ?></th>
		<th class="tpage_top" width="20%"><?php echo JText::_('COM_BLOODBOWL_TOUR_TYPE'); ?></th>
		<th class="tpage_top" width="15%"><?php echo JText::_('COM_BLOODBOWL_DATE_BEGIN'); ?></th>
		<th class="tpage_top" width="15%"><?php echo JText::_('COM_BLOODBOWL_DATE_END'); ?></th>
	</tr>
	<?php
		foreach ($this->tourneys as $tourney)
		{
			//alternate bgcolor
			if ($bgcolor=='WhiteSmoke')
			{
				$bgcolor='#FEE6BC';
			}
			else
			{
				$bgcolor   = 'WhiteSmoke';
			}
			echo "<tr style=\"background-color: $bgcolor;\">\n";
				//echo ($admin ? "<td><input type=text name='tourname' value='".$tourney->name."'>" : "<td><a href='".sefreltoabs($link."&tourid=".$tourney->tour_id)."'>".$tourney->name)."</td>\n";	
				echo "<td><a href='". JRoute::_('index.php?view=tourneydetail&show='. $tourney->tour_id ) ."'>". $tourney->name ."</a></td>\n";
				echo "<td>$tourney->type</td>\n";
				echo "<td>$tourney->created</td>\n";
				echo "<td>";
				echo ( $tourney->finished > 0 ? $tourney->finished : '');
				echo "</td>\n";
			echo "</tr>\n";
		}
	?>
</table>
</p>