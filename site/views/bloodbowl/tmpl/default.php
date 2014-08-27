<?php
// No direct access to this file
defined('_JEXEC') or die;
 
JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');
?>
 
<h1>TRC Blood Bowl Management</h1>
<?php
echo BloodBowlHelper::addMenubar();

if ($this->iscoach)
{
	//include('loggedin.php');
	echo $this->loadTemplate('loggedin');
}
?>

<h2><?php echo JText::_('COM_BLOODBOWL_NEWS_FROM') ." {$this->ratingtour->name}"; ?></h2>
<p>
<?php echo (strlen($this->ratingtour->news)>30 ? $this->ratingtour->news : JText::_('COM_BLOODBOWL_NO_NEWS') ); ?>
</p><br>

<h2><?php echo JText::_('COM_BLOODBOWL_MATCH_HISTORY'); ?></h2>
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
		foreach ($this->matches as $match)
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
<?php
echo BloodBowlHelper::getVersion();
