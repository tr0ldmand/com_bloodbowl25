<?php
function showStats($table, $heading){
		echo "<h2>$heading</h2>\n";
			echo '<table style="border:1px; width:70%; padding: 1px; text-align:center;">';
			echo "<tr>\n";
			foreach ($table[0]->title as $title) {
				echo "<th>".JText::_($title)."</th>\n";
			}
			echo "</tr>\n";
			$teamlink = 'index.php?option=com_bloodbowl&view=teamdetail&show=';
			foreach ($table as $key => $entry){
				$table[$key]->teamname = '<a href="'.JRoute::_($teamlink.$table[$key]->id).'">'.$table[$key]->teamname.'</a>';
				if ($entry->date_sold!=null){
					$table[$key]->name = $table[$key]->name.'<img src="media/com_bloodbowl/images/icons/cross.gif" alt="'.JText::_(COM_BLOODBOWL_DEAD).' '.$entry->date_sold.'">';
				}
				//alternate bgcolor
				if ($bgcolor=='WhiteSmoke') {
					$bgcolor='#FEE6BC';
				} else {
				$bgcolor   = 'WhiteSmoke';
				}
				
				echo "<tr style='background-color:".$bgcolor."';>";
				
				foreach ($table[0]->titleid as $titleid) {
					echo "<td>".$entry->$titleid."</td>\n";
				}
				echo "</tr><tr>\n";
			}
			echo "</tr></table><br>\n";
	
	}
// No direct access to this file
defined('_JEXEC') or die;
 
JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');
?>
 
<h1>TRC Blood Bowl Management Statistik</h1>
<?php
echo BloodBowlHelper::addMenubar();

showStats($this->alltime_topscorers, "All time top scorers");
showStats($this->alltime_casualties, "All time casualties");
showStats($this->alltime_completions, "All time completions");
showStats($this->alltime_kills, "All time killers");
showStats($this->alltime_interceptions, "All time interceptions");
showStats($this->alltime_starplayer, "All time SPP earners");
showStats($this->alltime_teamcas, "Team casualties");
showStats($this->alltime_teamcomp, "Team completions");
showStats($this->alltime_teamdefense, "Best defense");
showStats($this->alltime_teamgate, "Team gate");
showStats($this->alltime_teamkills, "Team kills");
showStats($this->alltime_teamscore, "Team topscorers");

?>
